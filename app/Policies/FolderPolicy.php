<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Folder;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Folder $folder
     * @return bool
     */
    public function access(User $user, Folder $folder)
    {
        return $user->id === $folder->user_id;
    }

    public function update(User $user, Folder $folder, Folder $parent = null)
    {
        return $user->id === $folder->user_id && ($parent === null || $user->id === $parent->user_id );
    }

}
