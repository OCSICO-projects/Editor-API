<?php

namespace App\Http\Services;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;

class UserService
{
    /**
     * Send the email verification notification.
     *
     * @param User $user
     */
    public function sendEmailVerificationNotification(User $user)
    {
        $user->notify(new VerifyEmailNotification());
    }
}
