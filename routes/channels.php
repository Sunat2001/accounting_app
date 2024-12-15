<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('transaction-created', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
