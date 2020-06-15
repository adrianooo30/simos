<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\SalesDependency;
use App\Dependency\EmployeeDependency;

use App\Dependency\RolesDependency;

use App\Dependency\ProductDependency;

// model classes
use App\Employee;
use App\Account;

class QoutaDependency extends BroadDependency
{
	protected $salesDependency;

	protected $employeeDependency;
	protected $rolesDependency;

	protected $productDependency;

	public function __construct()
	{
		$this->salesDependency = new SalesDependency();
		$this->employeeDependency = new EmployeeDependency();

		$this->rolesDependency = new RolesDependency();
		$this->productDependency = new ProductDependency();
	}

	public function get_employeesAndQouatas($start_date, $end_date)
	{
		// employee repository
		$employees = array();

		// loop through employees
		foreach( Employee::all() as $employee ) {

			if( $employee->can('hold_product') ) {
				//
				$this->__get_allTotalsFor( $employee );
				//
				array_push( $employees, $employee );
			}

		}

		// return employees and their qoutas
		return $employees;
	}

	public function __get_allTotalsFor($employee)
	{
		$employee['achievement'] = 0;
		$employee['percent'] = '0%';

		foreach( $employee->target as $target )
			$employee['target_amount'] = $target['target_amount'];

		// ******************************
		$this->__set_qoutas_Formatted($employee);

		// ******************************
		return $employee;
	}

	//
	public function __set_qoutas_Formatted($employee)
	{
		$employee['target_amount_format'] = $this->currencyType.'  '.number_format($employee['target_amount'], 2);
		$employee['achievement_format'] = $this->currencyType.'  '.number_format($employee['achievement'], 2);

		// return with its formatted data
		return $employee;
	}

}