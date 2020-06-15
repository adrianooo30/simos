<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

class PrivilegeDependency extends BroadDependency
{

	protected $salesDependency;

	public function __construct()
	{
		$this->salesDependency = new SalesDependency();
	}

	public function get_currentPositionAndItsRoles()
	{
		// roles dependency...
        $rolesDependency = new RolesDependency();

        // get the position that is logged in
        $position = session('employee.position');

        // return the current position and its roles
        return $rolesDependency->get_positionAndItsRoles($position);
	}
}