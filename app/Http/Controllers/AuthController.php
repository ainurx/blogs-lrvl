<?php

namespace App\Http\Controllers;

use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\UserInterface;

class AuthController extends Controller
{
    protected UserInterface $user;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function signin(Request $request) {
        try {
            $credential = $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);

            if (!Auth::attempt($credential)) {
                throw new \Exception('Invalid credential');
            }

            $params = [
                'email' => $request->email
            ];

            $user = $this->user->findOneByParams($params);

            $abilities = array('blog');

            if ($user->role === UserRole::Admin->value) {
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
