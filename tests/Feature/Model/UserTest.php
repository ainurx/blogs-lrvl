<?php

use App\UserRole;
use App\Models\User;
use App\Models\Blog;

test('Create user with <admin> role', function () {
    User::factory()->create();

    $res = User::find(1);

    expect($res->id === 1)->toBeTrue();
    expect($res->role)->toBe(UserRole::Admin->value);
});

test('Delete user should not be delete respective blog', function() {
    $user = User::factory()->create();

    Blog::factory()->create();

    $blog = Blog::find(1);

    expect($blog->id)->toBe(1);
    expect($blog->title)->tobe('one piece: romance down');
    expect($blog->user_id)->tobe(1);

    $user->delete();

    $checkUser = User::find(1);
    expect($checkUser)->toBe(null);

    $checkBlog = Blog::find(1);

    expect($checkBlog->id)->toBe(1);
    expect($checkBlog->title)->toBe('one piece: romance down');
    expect($checkBlog->user_id)->toBe(null);
    expect($checkBlog->last_update_by_user_id)->toBe(null);
});