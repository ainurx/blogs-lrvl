<?php

namespace App\Http\Controllers;

use App\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signin(Request $request) {
        try {
            $credential = $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);

            if (!Auth::attempt($credential)) {
                throw new \Exception('Invalid credential');
            }

            $user = User::where('email', $request->email)->first();

            $abilities = array('blog');

            if ($user->role === UserRole::Admin) {
                $abilities[0] = '*';
            }

            $token = $user->createToken($user->role.'-token', $abilities)->plainTextToken;

            return $this->responseSuccess([
                'token' => $token
            ]);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function signout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return $this->responseSuccess([ 'message' => 'Logout successful']);
    }
}
