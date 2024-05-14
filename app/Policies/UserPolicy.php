<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk tambah Pengguna.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): Response
    {
        return $user->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk edit Pengguna.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        return $user->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk hapus Pengguna.');
    }
}
