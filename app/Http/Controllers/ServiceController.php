<?php

namespace App\Http\Controllers;

use App\Repositories\ServiceRepository;
use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;
use App\Helpers\Responder;
use DB;
class ServiceController extends Controller
{
    public function __construct(
        ServiceRepository $serviceRepository,
        Responder $responder
    ) {
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
        return $this->serviceRepository->all();
    }

    public function show($id)
    {
        try {
            //prepare variables
            return $this->serviceRepository->show('id', $id);
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
    public function store(CreateServiceRequest $request)
    {
        DB::beginTransaction();
        try {
           // prepare variables
            $payload = $request->only('name', 'price');
            $service = $this->serviceRepository->create($payload);
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
    public function update(UpdateServiceRequest $request, $id)
    {
        DB::beginTransaction();
        try {
           // prepare variables
            $payload = $request->only('name', 'price');
            $service = $this->serviceRepository->update($id, $payload);
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
            $service = $this->serviceRepository->destroy($id);
            DB::commit();
            return $this->responder->deleteResponse(true); 
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->deleteResponse(false);
        }
    }
}
