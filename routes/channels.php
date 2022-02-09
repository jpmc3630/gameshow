<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('my-channel', function ($user) {
    // return (int) $user->id === (int) $id;
    \Log::info('my-channel');
    if (Auth::check()) {
        \Log::info('user is authed');      
    }
    return true;
});

// Broadcast::channel('user.{userId}', function ($user, $userId) {
//   return $user->id === $userId;
// });
