<?php

namespace App\Http\Controllers;

use App\UserRole;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Blog\BlogInterface;

class BlogController extends Controller
{
    protected BlogInterface $blog;

    public function __construct(BlogInterface $blog) 
    {
        $this->blog = $blog;
    }

    public function index(Request $request)
    {
        $data = null;

        if ($request->user()->role === UserRole::Normal->value) {
            $params = [ 
                'user_id' => $request->user()->id
            ];

            $data = $this->blog->findByParams($params);
        } else {
            $data = $this->blog->findAll();
        }

        return $this->responseSuccess($data);
    }

    public function checkBlogAuthorization(User $user, Blog $blog):void
    {
        if ($user->id !=  $blog->user_id && !in_array($user->role, [UserRole::Manager->value, UserRole::Admin->value])) {
            abort(403, 'Unauthorized');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|min:3|max:75',
                'content' => 'required',
            ]);

            $validated['user_id'] = $request->user()->id;
            $validated['last_update_by_user_id'] = $request->user()->id;

            $result = $this->blog->create($validated);
    
            return $this->responseSuccess($result, 201);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function show(Request $request, string $id)
    {
        try {
            $params = [
                'blogs.id' => $id
            ];
            $blog = $this->blog->findOneByParams($params);

            $this->checkBlogAuthorization($request->user(), $blog);

            return $this->responseSuccess($blog);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'min:3',
                'content' => 'min:1'
            ]);

            $blog = $this->blog->findById($id);

            $this->checkBlogAuthorization($request->user(), $blog);

            $validated['last_update_by_user_id'] = $request->user()->id;
            
            $result = $this->blog->update($id, $validated);

            return $this->responseSuccess($result);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $blog = $this->blog->findById($id);
            
            $this->checkBlogAuthorization($request->user(), $blog);
    
            $this->blog->delete($id);
            
            return $this->responseSuccess(['message' => 'Blog '. $id .' deleted']);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }
}
