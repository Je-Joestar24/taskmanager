<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tasks.user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin.tasks', function ($user) {
    return method_exists($user, 'isAdmin') ? $user->isAdmin() : false;
});



