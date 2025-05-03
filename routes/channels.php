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


Broadcast::channel('private-doctor.{doctorId}', function ($user, $doctorId) {
    return (int) $user->id === (int) $doctorId;
});
Broadcast::channel('private-balanceUpdated', function ($user) {
    return true;
});
Broadcast::channel('message-sended.{chatId}', function ($user,$chatId) {
    return true;
});
Broadcast::channel('new-chat.{userId}', function ($user,$userId) {
    return (int) $user->id === (int) $userId;
});