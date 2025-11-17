<?php 
namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserInterface {
    /**
    * @return Collection<int, User>
    */
    public function all(): Collection;
    public function create(array $data): User;
    public function findById(int $id): ?User;
    public function findOneByParams(array $params): ?User;
    public function update(int $id, array $data): ?User;
    public function delete(int $id): ?bool; 
}