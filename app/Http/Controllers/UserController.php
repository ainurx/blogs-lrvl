<?php

namespace App\Http\Controllers;

use App\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;
use App\Repositories\User\UserInterface;

class UserController extends Controller
{
    protected UserInterface $user;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
 
    public function index()
    {
        $users = $this->user->all();

        return $this->responseSuccess($users);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|min:3',
                'email' => 'required|email',
                'password' => 'required|min:4',
                'role' => Rule::enum(UserRole::class)
            ]);

            $validated['password'] = Hash::make($validated['password']);

            $params = [
                'email' => $validated['email']
            ];
            
            $userByEmail = $this->user->findOneByParams($params);

            if ($userByEmail) {
                throw new \Exception($validated['email'] . ' already used');
            }
    
            $result = $this->user->create($validated);
    
            return $this->responseSuccess($result, 201);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function show(string $id)
    {
        try {
            $user = $this->user->findById($id);

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
                'password' => 'min:4',
                'role' => [new Enum(UserRole::class)]
            ]);

            $result = $this->user->update($id, $validated);

            return $this->responseSuccess($result);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->user->delete($id);

            return $this->responseSuccess([
                'message' => 'user ' . $id . ' deleted'
            ]);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }
}
