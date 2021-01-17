<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use App\Http\Requests\CreateCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use App\Helpers\Responder;
use DB;
class CustomerController extends Controller
{
    public function __construct(
        CustomerRepository $customerRepository,
        Responder $responder
    ) {
        $this->customerRepository = $customerRepository;
        $this->responder = $responder;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->customerRepository->all();
    }

    public function show($id)
    {
        try {
            //prepare variables
            return $this->customerRepository->show('id', $id);
           // return new BookingResource($booking);
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
    public function store(CreateCustomerRequest $request)
    {
        DB::beginTransaction();
        try {
           // prepare variables
            $payload = $request->only('name', 'phone_no');
            $customer = $this->customerRepository->create($payload);
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
    public function update(UpdateCustomerRequest $request, $id)
    {
        DB::beginTransaction();
        try {
           // prepare variables
            $payload = $request->only('name', 'phone_no');
            $customer = $this->customerRepository->update($id, $payload);
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
            $customer = $this->customerRepository->destroy($id);
            DB::commit();
            return $this->responder->deleteResponse(true); 
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->deleteResponse(false);
        }
    }
}
