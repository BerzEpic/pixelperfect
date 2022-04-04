<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthUsersController extends Controller
{
    public function register(Request $request) {
         // validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'role' => 'required'

        ]);
         if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->save();


        $token = $user->createToken('auth_token_issues')->plainTextToken;

        return response()->json([
                'message'=> 'User created successfully!'
            ], 201);
    }

    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'

        ]);
         if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        // Check email
        $user = User::where('email', $request->email)->first();

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }

        if($user->role_id == 1){
            $token = $user->createToken("auth_token_issues", ["create-issue", "display-issue", "display-issues", "update-issue", "update-status"])->plainTextToken;
        }else{
            $token = $user->createToken("auth_token_issues", ["create-issue", "display-issue", "update-status"])->plainTextToken;
        }

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}