<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoginRegisterController extends Controller
{
    //register user

    function register(Request $request)
    {
        
        try{
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'contact' => 'required|numeric',
                'password' => 'required',
                'city' => 'required',
            ]);
            if ($validate->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validate->errors()->all(),
                ]);
            }
            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->city = $request->city;
            $user->email = $request->email;
            $user->contact = $request->contact;
            $user->password = Hash::make($request->password);
            $user->save();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'User Registered Successfully',
                'access_token' => $token,
                'token_type' => 'Bearer',
                "user" => ['username'=>$user->username,
                            'name'=>$user->name,
                            'city'=>$user->city,
                            'email'=>$user->email,
                            'contact'=>$user->contact,
                            'created'=>Carbon::parse($user->created_at)->format('d/m/Y')]
            ], 200);
        }
        catch(Exception){
            return response()->json([
                'status' => false,
                'message' => ['Something Went Wrong, Plz Try Again Later']
            ],200);
        }
    }

    //login user


    function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'credential' => 'required',
            'password' => 'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()->all(),
            ]);
        }

        try{
            // check if user entered username or email

            $email = User::where('email', $request->credential)->first();
            $username = User::where('username', $request->credential)->first();

                //authenticating if user entered email

            if ($email) {
                if (!Auth::attempt(['email' => $request->credential, 'password' => $request->password])) {
                    return response()->json([
                        "status" => false,
                        'message' => 'Password Does Not Match'
                    ], 200);
                }
                $token = $email->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'User Registered Successfully',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    "user" => ['username'=>$email->username,
                                'name'=>$email->name,
                                'city'=>$email->city,
                                'email'=>$email->email,
                                'contact'=>$email->contact,
                                'created'=>Carbon::parse($email->created_at)->format('d/m/Y')]
                ], 200);
            }

                //authenticating if user entered username

            if ($username) {
                if (!Auth::attempt(['username' => $request->credential, 'password' => $request->password])) {
                    return response()->json([
                        "status" => false,
                        'message' => ['Password Does Not Match']
                    ], 200);
                }
                $token = $username->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'User Registered Successfully',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    "user" => ['username'=>$username->username,
                                'name'=>$username->name,
                                'city'=>$username->city,
                                'email'=>$username->email,
                                'contact'=>$username->contact,
                                'created'=>Carbon::parse($username->created_at)->format('d/m/Y')]
                ], 200);
            }

                    //user not found

            return response()->json([
                "status" => false,
                'message' => ['User Does Not Exist']
            ], 200);
        }
        catch(Exception){
            return response()->json([
                "status" => false,
                'message' => ['Something Went Wrong, Please Try Again Later']
            ], 200);
        }
    }
}
