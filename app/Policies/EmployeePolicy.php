<?php

namespace App\Policies;

use App\Models\Employee;

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
    public function create(Employee $employee): bool
    {
        return $employee->is_admin == 1;
    }

    /**
     * Determine whether the employee can update the model.
     */
    public function update(Employee $employee): bool
    {
        return $employee->is_admin == 1;
    }

    /**
     * Determine whether the employee can delete the model.
     */
    public function delete(Employee $employee): bool
    {
        return $employee->is_admin == 1;
    }
}
