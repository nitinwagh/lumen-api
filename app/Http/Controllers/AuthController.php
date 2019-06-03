<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * Authenticate user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request) {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->input('email'))->first();
        if($user) {
            if(Hash::check($request->input('password'), $user->password)){
                $apikey = base64_encode(str_random(40));
                User::where('email', $request->input('email'))->update(['api_key' => "$apikey"]);
                return response()->json(['api_key' => $apikey], 200);
            }
            return response()->json(['message' => 'Password does`t match'], 400);
        }
        return response()->json(['message' => 'Invalide username/passowrd'], 400);
    }
   
    /**
     * Register new user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        try {
            $this->validate($request, [
                'name' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6'
            ]);

            $data = $request->all();
            $data['old_password'] = $data['password'];
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            if($user instanceof User){
                if(Hash::check($data['old_password'], $user->password)){
                    $apikey = base64_encode(str_random(40));
                    User::where('email', $data['email'])->update(['api_key' => "$apikey"]);
                    return response()->json(['api_key' => $apikey], 200);
                }
            }
            return response()->json(['message' => 'Something went wrong!'], 400);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Get login user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request) {
        $user = $request->user();
        return response()->json($user, 200);
    }

    /**
     * Logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        $user = $request->user();
        $user->api_key = null;
        if($user->save()){
            return response()->json([
                'message' => 'logout successfully.'
            ], 200);
        }
        return response()->json(['message' => 'Something went wrong!'], 400);
    }
   
}
