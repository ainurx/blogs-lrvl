<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return $this->responseSuccess($users);
    }

    public function store(Request $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ];

            $userByEmail = User::where('email', $data['email'])->first();

            if ($userByEmail) {
                throw new \Exception($data['email'] . ' already used');
            }
    
            $result = User::create($data);
    
            return $this->responseSuccess($result, 201);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);

            return $this->responseSuccess($user);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'min:3',
                'password' => 'min: 4',
                'role' => [new Enum(UserRole::class)]
            ]);

            $user = User::findOrFail($id);
            $user->update($validated);

            return $this->responseSuccess($user);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return $this->responseSuccess([
                'message' => 'user ' . $id . ' deleted'
            ]);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }
}
