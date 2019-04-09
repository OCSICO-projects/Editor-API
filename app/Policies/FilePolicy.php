<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param File $file
     * @return bool
     */
    public function access(User $user, File $file)
    {
        return $user->id === $file->user_id;
    }
}
