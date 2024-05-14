<?php

namespace App\Policies;

use App\Models\Bonus;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class BonusPolicy
{

    /**
     * Determine whether the user can update the model.
     */
    public function update(): Response
    {
        return Auth::user()->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk edit Bonus.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(): Response
    {
        return Auth::user()->is_admin == 1
            ? Response::allow()
            : Response::deny('Anda tidak diizinkan untuk hapus Bonus.');
    }
}
