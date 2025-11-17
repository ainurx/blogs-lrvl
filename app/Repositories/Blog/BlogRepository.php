<?php 

namespace App\Repositories\Blog;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Collection;

class BlogRepository implements BlogInterface {
    public function findAll(): Collection 
    {
        $result =  Blog::select('blogs.*', 'author.name as author', 'editor.name as editor')
                ->leftJoin('users as author', 'author.id', '=', 'blogs.user_id')
                ->leftJoin('users as editor', 'editor.id', '=', 'blogs.last_update_by_user_id')
                ->get();
        return $result;
    }

    public function findById(int $id): ?Blog
    {
        return Blog::findOrFail($id);
    }

    public function findByParams(array $params): Collection
    {
        $result = Blog::select('blogs.*', 'author.name as author', 'editor.name as editor')
                ->leftJoin('users as author', 'author.id', '=', 'blogs.user_id')
                ->leftJoin('users as editor', 'editor.id', '=', 'blogs.last_update_by_user_id')
                ->where($params)
                ->get();

        return $result;
    }

    public function findOneByParams(array $params): ?Blog
    {
        $result = Blog::select('blogs.*', 'author.name as author', 'editor.name as editor')
                ->leftJoin('users as author', 'author.id', '=', 'blogs.user_id')
                ->leftJoin('users as editor', 'editor.id', '=', 'blogs.last_update_by_user_id')
                ->where($params)
                ->firstOrFail();

        return $result;
    }

    public function create(array $data): Blog
    {
        $result = Blog::create($data);

        return $result;
    }

    public function update(int $id, array $data): Blog
    {
        $blog = Blog::findOrFail($id);
        $blog->update($data);

        return $blog;
    }

    public function delete(int $id): bool
    {
        $blog = Blog::findOrFail($id);
        
        return $blog->delete();
    }
}