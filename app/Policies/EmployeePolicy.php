<?php

namespace App\Policies;

use App\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any employees.
     *
     * @param  \App\Employee  $user
     * @return mixed
     */
    public function viewAny(Employee $user)
    {
        //
    }

    /**
     * Determine whether the user can view the employee.
     *
     * @param  \App\Employee  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function view(Employee $user, Employee $employee)
    {
        //
    }

    /**
     * Determine whether the user can create employees.
     *
     * @param  \App\Employee  $user
     * @return mixed
     */
    public function create(Employee $user)
    {
        //
    }

    /**
     * Determine whether the user can update the employee.
     *
     * @param  \App\Employee  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function update(Employee $user, Employee $employee)
    {
        //
    }

    /**
     * Determine whether the user can delete the employee.
     *
     * @param  \App\Employee  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function delete(Employee $user, Employee $employee)
    {
        //
    }

    /**
     * Determine whether the user can restore the employee.
     *
     * @param  \App\Employee  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function restore(Employee $user, Employee $employee)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the employee.
     *
     * @param  \App\Employee  $user
     * @param  \App\Employee  $employee
     * @return mixed
     */
    public function forceDelete(Employee $user, Employee $employee)
    {
        //
    }
}
