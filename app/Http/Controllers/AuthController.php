<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\UserRole;

class AuthController extends Controller
{
    public function signin(Request $request) {
        try {
            $email = $request->email;
            $password = $request->password;
    
            $userByEmail = User::where('email', $email)->first();

            if (!$userByEmail || !Hash::check($password, $userByEmail->password)) {
                throw new \Exception('Invalid credential');
            }

            return $this->responseSuccess($userByEmail);
        } catch (\Exception $error) {
            return response()->json([
                'error' => $error->getMessage()
            ], 400);
        }
    }

    public function signup(Request $request) {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ];

        $result = User::create($data);

        return $this->responseSuccess($result, 201);
    }
}
