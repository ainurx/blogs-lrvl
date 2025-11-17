<?php 

namespace App\Repositories\Blog;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;

interface BlogInterface {
    /**
    * @return Collection<int, Blog>
    */
    public function findAll(): Collection;
    public function findById(int $id): ?Blog;
    /**
    * @return Collection<int, Blog>
    */
    public function findByParams(array $params): Collection;
    public function findOneByParams(array $params): ?Blog;
    public function create(array $data): Blog;
    public function update(int $id, array $data): Blog;
    public function delete(int $id): bool;
}