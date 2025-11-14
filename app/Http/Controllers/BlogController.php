<?php

namespace App\Http\Controllers;

use App\UserRole;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $data = null;

        if ($request->user()->role === UserRole::Normal->value) {
            $data = Blog::select('blogs.*', 'author.name as author', 'editor.name as editor')
                ->leftJoin('users as author', 'author.id', '=', 'blogs.user_id')
                ->leftJoin('users as editor', 'editor.id', '=', 'blogs.last_update_by_user_id')
                ->where('user_id', $request->user()->id)
                ->get();
        } else {
            $data = Blog::select('blogs.*', 'author.name as author', 'editor.name as editor')
                ->leftJoin('users as author', 'author.id', '=', 'blogs.user_id')
                ->leftJoin('users as editor', 'editor.id', '=', 'blogs.last_update_by_user_id')->get();
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

            $result = Blog::create($validated);
    
            return $this->responseSuccess($result, 201);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function show(Request $request, string $id)
    {
        try {
            $blog = Blog::select('blogs.*', 'author.name as author', 'editor.name as editor')
                ->leftJoin('users as author', 'author.id', '=', 'blogs.user_id')
                ->leftJoin('users as editor', 'editor.id', '=', 'blogs.last_update_by_user_id')
                ->where('blogs.id', $id)
                ->firstOrFail();

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
                'content' => 'min: 1'
            ]);


            $blog = Blog::findOrFail($id);

            $this->checkBlogAuthorization($request->user(), $blog);

            $validated['last_update_by_user_id'] = $request->user()->id;
            $blog->update($validated);

            return $this->responseSuccess($blog);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $blog = Blog::findOrFail($id);
            
            $this->checkBlogAuthorization($request->user(), $blog);
    
            $blog->delete();
    
            return $this->responseSuccess(['message' => 'Blog '. $id .' deleted']);
        } catch (\Exception $error) {
            return $this->responseError($error);
        }
    }
}
