<?php

namespace App\Policies;

use App\Models\Employee;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class EmployeePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the employee can create models.
     */
    public function create(): Response
    {
        return Auth::user()->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk tambah Pegawai.');
    }

    /**
     * Determine whether the employee can update the model.
     */
    public function update(): Response
    {
        return Auth::user()->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk edit Pegawai.');
    }

    /**
     * Determine whether the employee can delete the model.
     */
    public function delete(): Response
    {
        return Auth::user()->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk hapus Pegawai.');
    }
}
