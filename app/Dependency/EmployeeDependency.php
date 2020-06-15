<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;
use App\Dependency\ProductDependency;

use App\Dependency\RolesDependency;

use Illuminate\Support\Facades\Auth;

// model class
use App\Employee;
use App\Product;

class EmployeeDependency extends BroadDependency
{

	protected $rolesDependency;
	protected $productDependency;

	public function __construct()
	{
		$this->rolesDependency = new RolesDependency();
		$this->productDependency = new ProductDependency();
	}

	public function setEmployee()
	{
		//
	}

	// all employees
	public function getEmployees()
	{
		$employees = Employee::orderBy('lname', 'asc')->get();
		// employees
		foreach($employees as $employee)
		{
			// set calculated attribute
			$this->setEmployee_Formatted($employee);
		}
		// set pages
		$this->setPages($employees);
		// return employees
		return $employees;
	}

	// each single employee details
	public function getEmployeeDetails($employee)
	{
		//
		$this->setEmployee_Formatted($employee);
		// target amount
		foreach( $employee->target as $target )
			$employee['latest_target'] = $target;
			
		$employee->getRoleNames()->first();
        $employee->target;

		// return target
		return $employee;
	}

	// set in row
	public function setEmployee_Formatted($employee)
	{
		// get the position
		$employee->position;
		// get the well formatted full name
		$employee['full_name'] = $this->getEmployeeNameFormat($employee);
		// return employee with well formatted string
		return $employee;
	}

	// set unified full name of employee
    public function getEmployeeNameFormat($employee)
    {
        return $employee['lname'].', '.$employee['fname'].' '.substr($employee['mname'], 0, 1).'.';
    }

    // get employee for a single module
    public function getEmployeesFor($paramModule, $paramMethod)
    {
    	$employees = array();
    	foreach($this->getEmployees() as $employee) {
    		// track if can...
    		if( $this->rolesDependency->trackIfCan($employee->position ,$paramModule, $paramMethod) ) {
    			// DETAILS
    			$this->getEmployeeDetails($employee);
    			// insert into array
    			array_push($employees, $employee);
    		}
    	}

    	return $employees;
    }

    // HOLDING PRODUCT
    public function setProductsToHold($insertType ,$employeeId, $product)
    {
    	switch($insertType) {
    		case 'store':
    			// RECORD PSR TO HOLD
		        if( is_numeric($employeeId) ) {
		            $product->employee()->attach( $employeeId, ['active' => true]);
		        }
    		break;

    		case 'update':
    			$isHasTheEmployee = false;
    			// LOOP THROUGH EMPLOYEES
		    	foreach($product->employee as $employee) {
		    		// if the employee already hold the product
		    		if($employeeId == $employee['id'])
		    			$isHasTheEmployee = true;
		    	}
		    	// IS HAS THE FORMER EMPLOYEE HOLDED BY
		    	if($isHasTheEmployee) {
		    		// SET ALL EMPLOYEE TO FALSE
		    		foreach($product->employee as $employee) {
			    		$employee->pivot->update([ 'active' => false ]);
			    	}
		    	}

		    	// RECORD PSR TO HOLD
				if( is_numeric($employeeId) ) {
				    $product->employee()->attach( $employeeId, ['active' => true]);
				}
				// IF SELECTED NO HOLDER
				else if( !is_numeric($employeeId) ){ // if $employeeId is 
					foreach($product->employee as $employee)
						$employee->pivot->update(['active' => false]);
				}
    		break;
    	}
    }

    // *************************************************************
    //				SET PRODUCT FOR HOLDING
    // *************************************************************

    // PRODUCTS NOT HOLDED YET BY
    public function getProductsForHolding($currentEmployee)
    {
    	$products = Product::orderBy('generic_name', 'asc')->get();

    	// get the products
    	foreach( $products as $product ) {

    		// set formatted dependency
    		$this->productDependency->get_unitPrice_Formatted( $product );

    		$didntExistInHolding = true;

    		// TRACK IF CURRENTLY HOLDING THE PRODUCT
    		foreach( $product->employee as $employee) {
    			if( $employee->pivot['active'] && $employee['id'] == $currentEmployee['id'] )
    				$didntExistInHolding = false;
    		}

    		$this->__track_ifNoHolder( $product );

    		if($didntExistInHolding)
    			$product['holded'] = false;
    		else
    			$product['holded'] = true;
    	}

    	// return products not holded yet
    	return $products;
    }

    public function __track_ifNoHolder($product)
    {
    	// TRACK IF NO EMPLOYEE HOLDING THE PRODUCT
		$countActive = 0;
		foreach( $product->employee as $employee) {
			if($employee->pivot['active']) {
				$countActive++;
				$product['current_holder'] = $this->setEmployee_Formatted($employee);
			}
		}

		if($countActive === 0) {
			$product['has_current_holder'] = false;
		}
		else
			$product['has_current_holder'] = true;

		// return with manipulated employee
		return $product;
    }

    // TRACKING SECTION
    public function isCurrentlyHolding( $specifiedProduct )
    {
    	// Note : Only the employee in session is currently checking if he/she currently holding the specified product.
    	foreach( Employee::find( Auth::id() )->products as $product ) {
    		// return true if holded actively
    		if( $specifiedProduct['id'] == $product['id'] && $product->pivot['active'] )
    			return true;
    	}

    	// return false if not currently holding
    	return false;
    }
}