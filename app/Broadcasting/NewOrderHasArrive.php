<?php

namespace App\Broadcasting;

use App\Employee;

class NewOrderHasArrive
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Employee  $user
     * @return array|bool
     */
    public function join(Employee $user)
    {
        return true;
    }
}
