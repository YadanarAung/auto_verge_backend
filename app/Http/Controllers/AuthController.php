<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Repositories\UserRepository;
use App\Helpers\Responder;
use DB;
use Auth;
use JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        Responder $responder,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
        $this->responder = $responder;
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    protected function tokenResponse($accessToken) {
        $payload = [
            'access_token' => $accessToken,
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ];
        return response()->json($payload);
    }

    public function login(LoginRequest $request) {

       try {
            // prepare variables
            $credentials = $request->only(['email', 'password']);
            $token = auth('api')->attempt($credentials);
            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }else{
                return $this->tokenResponse($token);
                
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responder->customResponse(500, 'Something wrong in login!');
        }
    }

    public function logout() {
        Auth::guard('api')->logout();
        return $this->responder->customResponse(200, 'Logout successfully!!');
    }

    public function me()
    {
        $user = Auth::guard('api')->user();
        $userArr = [
            'id'=>$user->id, 
            'name'=>$user->name, 
            'email'=>$user->email
        ];
        return response()->json($userArr);
    }
}
