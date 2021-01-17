<?php

namespace App\Http\Controllers;

use App\Repositories\BookingRepository;
use App\Repositories\BookingServiceRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\ServiceRepository;
use App\Http\Requests\CreateBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use App\Helpers\Responder;
use Carbon\Carbon;
use DB;
class BookingController extends Controller
{
    public function __construct(
        BookingRepository $bookingRepository,
        CustomerRepository $customerRepository,
        BookingServiceRepository $bookingServiceRepository,
        ServiceRepository $serviceRepository,
        Responder $responder
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->customerRepository = $customerRepository;
        $this->bookingServiceRepository = $bookingServiceRepository;
        $this->serviceRepository = $serviceRepository;
        $this->responder = $responder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = $this->bookingRepository->all();
        return BookingResource::collection($bookings);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            //prepare variables
            $booking = $this->bookingRepository->show('id', $id);
            return new BookingResource($booking);
        } catch (\Exception $e) {
            return $this->responder->customResponse(500, 'Something wrong!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookingRequest $request)
    {
        DB::beginTransaction();
        try {
           //prepare variables
            $payload = $request->only('customer_id', 'car_no', 'duration', 'invoice_no');
            $payload['booking_date'] = Carbon::parse($request->booking_date)->format('Y-m-d');
            $services = $request->service_id;
           
            $invoice = $this->getInvoiceNo();
            $payload['invoice_no'] = $invoice;
            $booking = $this->bookingRepository->create($payload);

            //create booking service
            $bookingServices = [];
            foreach($services as $k){
                $service = $this->serviceRepository->show('id', $k);
                $bookingServices= [
                    'booking_id'=>$booking->id,
                    'service_id'=>$k,
                    'price'=>$service->price,
                ];
                $this->bookingServiceRepository->create($bookingServices);
            }
           
            //send sms
            $customer = $this->customerRepository->show('id', $request->customer_id);
            $phoneNo = $customer->phone_no;
            $message = "Thank you. Your invoice no is $invoiceNo";
            $this->sendSms($phoneNo, $message);

            DB::commit();
            return $this->responder->createResponse(true); 
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->createResponse(false);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        DB::beginTransaction();
        try {
           // prepare variables
            $payload = $request->only('payment_date', 'payment_amount');
            $booking = $this->bookingRepository->show('id', $id);
            $this->bookingRepository->update($id, $payload);
            $invoiceNo = $booking->invoice_no;
            $phoneNo = $booking->customer->phone_no;
            $message = "Your service with invoice number $invoiceNo is closed now.";
            $this->sendSms($phoneNo, $message);
            DB::commit();
            return $this->responder->updateResponse(true); 
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->updateResponse(false);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $booking = $this->bookingRepository->destroy($id);
            DB::commit();
            return $this->responder->deleteResponse(true); 
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->deleteResponse(false);
        }
    }

    private function sendSms($mobile, $message)
    {
        $token = config('smspoh.token');
        $getUrl = config('smspoh.msgendpoint');
        $data = array(
            "to" => $mobile,
            "message" => $message,
            "sender"=> "SMSPoh"
        );
        $data_string = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token,
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($info["http_code"] != 200) {
            Log::info(['status_code' => $info["http_code"], 'mobile' => $mobile, 'result' => $result]);
        }
    }

    private function getInvoiceNo(){
        $invoiceNo = $this->bookingRepository->getMaxInvoiceNo();
        if($invoiceNo){
            return sprintf('%05d', $invoiceNo+1);
        }else{
            return sprintf('%05d', 1);
        }
    }   
    
}
