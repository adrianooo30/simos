<?php

namespace App\Dependency;

// model class
use App\Position;

class RolesDependency
{

    protected $defaultUser = 'Admin';

	public function __construct()
	{
		//
	}

    // set default position and its roles
    public function set_positionAndRoles($positionName = 'Admin', $modulesAndTheirMethods = [])
    {

        if($positionName === $this->defaultUser)
            $modulesAndTheirMethods = $this->get_modulesAndTheirMethods();

        // CREATE DEFAULT POSITION
        $position = Position::create([ 'position_name' => $positionName ]);

        // CREATE DESIRED MODULES FOR ADMIN
        foreach($modulesAndTheirMethods as $moduleAndItsMethods)
        {
            // CREATE MODULE
            $module = $position->modules()->create([
                'module_name' => $moduleAndItsMethods['module'],
                'access_name'  => $moduleAndItsMethods['access_name'],
            ]);
            // METHODS
            foreach($moduleAndItsMethods['methods'] as $method)
            {
                // CREATE METHOD
                $module->methods()->create([ 'method_name' => $method['method'], 'allowed' => $method['allowed'] ]);
            }
        }
    }

    // get positions and their roles
    public function get_positionAndTheirRoles()
    {
        $positions = \App\Position::all();

        // get positions
        foreach($positions as $position)
            $this->get_positionAndItsRoles($position);

        // return positions and their roles
        return $positions;
    }

    // singular
    public function get_positionAndItsRoles($position)
    {
        // get the modules and their methods
        foreach($position->modules as $module)
            $module->methods;

        // return the position and its modules
        return $position;
    }

    // 
    public function trackIfCan($position ,$paramModule, $paramMethod)
    {
        // modules
        foreach($position->modules as $module) {
            // module
            if(strtolower($paramModule) == strtolower($module['module_name'])) {
                // methods
                foreach($module->methods as $method) {
                    // method
                    if(strtolower($paramMethod) == strtolower($method['method_name']) && $method['allowed']) {
                        return true;
                    }
                }
            }
        }
        // if not access then return false;
        return false;
    }

	public function get_modulesAndTheirMethods()
	{
		return [
            // for product management
            [
                'module' => 'Product Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                    
                    [ 'method' => 'Can Add New Product', 'allowed' => true, 'id' => '--id-identification' ],
                    [ 'method' => 'Can Edit Product', 'allowed' => true, 'id' => '--id-identification' ],
                    
                    [ 'method' => 'Can Add New Batch Number', 'allowed' => true, 'id' => '--id-identification' ],
                    [ 'method' => 'Can Edit Batch Number', 'allowed' => true, 'id' => '--id-identification' ],
                    
                    [ 'method' => 'Can Add New Supplier', 'allowed' => true, 'id' => '--id-identification' ],
                    
                    [ 'method' => 'Can Record Loss', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'product-mgt',
            ],

            // for product movement
            [
                'module' => 'Product Movement',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'product-movement',
            ],

            // for free management
            [
                'module' => 'Deals Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'deals-mgt',
            ],

            // for returned products
            [
                'module' => 'Returned Product',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'returned-product',
            ],

            // for loss products
            [
                'module' => 'Loss Products',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'loss-products',
            ],

            // for soon expiring products
            [
                'module' => 'Soon Expiring',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'soon-expiring',
            ],

            // for expired products
            [
                'module' => 'Expired Product',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'expired-product',
            ],


            // for sales management
            [
                'module' => 'Sales Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can Record Returnee', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'sales-mgt',
            ],

            // for account receivables
            [
                'module' => 'Account Receivable',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can Record Collection', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'receivable',
            ],

            // for collection management
            [
                'module' => 'Collection Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can Record Deposit', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'collection-mgt',
            ],

            // for creation of order
            [
                'module' => 'Create Order',
                // module methods
                'methods' => [
                    [ 'method' => 'Can Add New Account', 'allowed' => true, 'id' => '--id-identification' ],
                    [ 'method' => 'Can Add Order', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'create-order',
            ],

            // for viewing of order
            [
                'module' => 'View Order',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'view-order',
            ],

            // for tracking of order
            [
                'module' => 'Track Order',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                    [ 'method' => 'Can Deliver Products', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'track-order',
            ],

            // account management
            [
                'module' => 'Account Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can Add New Account', 'allowed' => true, 'id' => '--id-identification' ],
                    [ 'method' => 'Can Edit Account', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can View History', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'account-mgt',
            ],

            // role management
            [
                'module' => 'Role Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'role-mgt',
            ],

            // account management
            [
                'module' => 'Employee Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can Add New Employee', 'allowed' => true, 'id' => '--id-identification' ],
                    [ 'method' => 'Can Edit Employee', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can View History', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'employee-mgt',
            ],

            // account management
            [
                'module' => 'Supplier Management',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can Add New Supplier', 'allowed' => true, 'id' => '--id-identification' ],
                    [ 'method' => 'Can Edit Supplier', 'allowed' => true, 'id' => '--id-identification' ],

                    [ 'method' => 'Can View History', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'supplier-mgt',
            ],

            [
                'module' => 'Notifications',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'notification',
            ],

            [
                'module' => 'Backup / Restore',
                // module methods
                'methods' => [
                    [ 'method' => 'Can View', 'allowed' => true, 'id' => '--id-identification' ],
                ],
                // access name in js
                'access_name' => 'backup-restore',
            ],

        ];
	} // end of modules and their methods

}