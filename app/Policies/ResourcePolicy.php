<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResourcePolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Resource $resource
     * @return bool
     */
    public function access(User $user, Resource $resource)
    {
        return $user->id === $resource->user_id;
    }
}
