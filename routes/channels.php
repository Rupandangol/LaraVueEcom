<?php

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


// Route::post('/api/broadcasting/auth', function () {
//     return Broadcast::auth(request());
// });
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('blog-created', function () {
    return true;
});
Broadcast::channel('public-chat', function () {
    return true;
});
Broadcast::channel('order.status.{userId}', function (User $user,int $userId) {
    return (int)$user->id === (int)$userId;
},['guards'=>['sanctum','users']]);

Broadcast::channel('order.placed', function ($user) {
   return true;
},['guards'=>['sanctum','admins']]);