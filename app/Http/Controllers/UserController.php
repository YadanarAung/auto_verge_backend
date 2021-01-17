<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Helpers\Responder;
use Illuminate\Support\Facades\Hash;
use DB;
class UserController extends Controller
{
    public function __construct(
        UserRepository $userRepository,
        Responder $responder
    ) {
        $this->userRepository = $userRepository;
        $this->responder = $responder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->userRepository->all();
    }

    public function show($id)
    {
        try {
            //prepare variables
            return $this->userRepository->show('id', $id);
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
    public function store(CreateUserRequest $request)
    {
        DB::beginTransaction();
        try {
           // prepare variables
            $payload = $request->only('name', 'email', 'password');
            $password = Hash::make($request->password);
            $payload['password'] = $password;
            $user = $this->userRepository->create($payload);
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
    public function update(UpdateUserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
           // prepare variables
            $payload = $request->only('name', 'email');
            $user = $this->userRepository->update($id, $payload);
            DB::commit();
            return $this->responder->updateResponse(true); 
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->updateResponse(false);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->destroy($id);
            DB::commit();
            return $this->responder->deleteResponse(true); 
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->deleteResponse(false);
        }
    }
}
