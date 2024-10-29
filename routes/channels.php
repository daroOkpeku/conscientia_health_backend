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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{receiver_id}', function ($user, $receiver_id){
    return (int) $user->id === (int)$receiver_id;
});

Broadcast::channel('user', function($user){
     return ['id' => $user->id, 'firstname'=>$user->firstname, 'lastname'=>$user->lastname, "user_type"=>$user->user_type];
});


Broadcast::channel('typing.{otheruserId}', function ($user, $otheruserId) {
    // Assuming your chat identifier is unique to the two users
    return (int) $user->id === (int)$otheruserId;
});
