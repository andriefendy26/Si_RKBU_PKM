<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RKBU;
use Illuminate\Auth\Access\HandlesAuthorization;

class RKBUPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RKBU');
    }

    public function view(AuthUser $authUser, RKBU $rKBU): bool
    {
        return $authUser->can('View:RKBU');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RKBU');
    }

    public function update(AuthUser $authUser, RKBU $rKBU): bool
    {
        return $authUser->can('Update:RKBU');
    }

    public function delete(AuthUser $authUser, RKBU $rKBU): bool
    {
        return $authUser->can('Delete:RKBU');
    }

    public function restore(AuthUser $authUser, RKBU $rKBU): bool
    {
        return $authUser->can('Restore:RKBU');
    }

    public function forceDelete(AuthUser $authUser, RKBU $rKBU): bool
    {
        return $authUser->can('ForceDelete:RKBU');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RKBU');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RKBU');
    }

    public function replicate(AuthUser $authUser, RKBU $rKBU): bool
    {
        return $authUser->can('Replicate:RKBU');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RKBU');
    }

}