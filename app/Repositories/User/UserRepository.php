<?php 

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserInterface {
    public function all(): Collection {
        $user = User::all();
        return $user;
    }

    public function create(array $data): User {
        return User::create($data);;
    }

    public function findById(int $id): ?User {
        return User::findOrFail($id);
    }

    public function findOneByParams(array $params): ?User {
        $user = User::where($params)->first();
        return $user;
    }

    public function update(int $id, array $data): ?User {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }

    public function delete(int $id): bool {
        $user = User::findOrFail($id);
        
        return $user->delete();
    }
}