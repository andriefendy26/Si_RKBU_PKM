<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TahunAnggaran;
use Illuminate\Auth\Access\HandlesAuthorization;

class TahunAnggaranPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TahunAnggaran');
    }

    public function view(AuthUser $authUser, TahunAnggaran $tahunAnggaran): bool
    {
        return $authUser->can('View:TahunAnggaran');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TahunAnggaran');
    }

    public function update(AuthUser $authUser, TahunAnggaran $tahunAnggaran): bool
    {
        return $authUser->can('Update:TahunAnggaran');
    }

    public function delete(AuthUser $authUser, TahunAnggaran $tahunAnggaran): bool
    {
        return $authUser->can('Delete:TahunAnggaran');
    }

    public function restore(AuthUser $authUser, TahunAnggaran $tahunAnggaran): bool
    {
        return $authUser->can('Restore:TahunAnggaran');
    }

    public function forceDelete(AuthUser $authUser, TahunAnggaran $tahunAnggaran): bool
    {
        return $authUser->can('ForceDelete:TahunAnggaran');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TahunAnggaran');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TahunAnggaran');
    }

    public function replicate(AuthUser $authUser, TahunAnggaran $tahunAnggaran): bool
    {
        return $authUser->can('Replicate:TahunAnggaran');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TahunAnggaran');
    }

}