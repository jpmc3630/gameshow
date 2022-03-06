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

Broadcast::channel('{userRoom}', function ($user) {
  // \Log::info('user: ' + $user);
// Broadcast::channel('KUMY', function ($user) {
    // return (int) $user->id === (int) $id;
    \Log::info('Channel is called: ' . $user->room);
    // if (Auth::check()) {
    //     \Log::info('user is authed');      
    // }


    // return true; // for priveate channel

    return ['id' => $user->id, 'name' => $user->name];
    // return ['id' => 3, 'name' => 'John'];
});

// Broadcast::channel('user.{userId}', function ($user, $userId) {
//   return $user->id === $userId;
// });
