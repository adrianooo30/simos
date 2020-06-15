<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;

use App\Employee;
use App\Product;
use App\OrderTransaction;

use Illuminate\Http\Request;
use App\Http\Requests\Employee\AddEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;

// custom class dependency
use App\Dependency\EmployeeDependency;
use App\Dependency\QoutaDependency;

// for plugin
use Yajra\Datatables\DataTables;

use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $employeeDependency;
    protected $qoutaDependency;

    public function __construct(EmployeeDependency $employeeDependency, QoutaDependency $qoutaDependency)
    {
        $this->employeeDependency = $employeeDependency;
        $this->qoutaDependency = $qoutaDependency;
    }

    public function index()
    {
        return DataTables::of( Employee::with(['roles'])->get() )
                // add columns
                ->addColumn('contact', 'includes.datatables.contact')
                ->addColumn('action', function($employee){

                    $actionHTML = '';

                    if(auth()->user()->can('view_employee_history ')){
                        $actionHTML .= '<a href="#" class="btn btn-primary waves-effect waves-light mx-1">
                                            <i class="ti-bookmark-alt"></i>
                                        </a>';
                    }

                    if(auth()->user()->can('edit_employee')){
                        $actionHTML .= '<button class="btn btn-warning waves-effect waves-light mx-1" data-toggle="modal" data-target="#edit-employee-modal" onclick="userDetails('.$employee['id'].')">
                                            <i class="ti-pencil-alt"></i>
                                        </button>';
                    }

                    if( auth()->user()->can('hold_product') ){
                        $actionHTML .= '<button class="btn btn-primary waves-effect waves-light mx-1" data-toggle="modal" data-target="#set-product-modal" onclick="setEmployeeProductHolding('.$employee['id'].')">
                                        <i class="fas fa-syringe"></i>
                                    </button>';
                    }

                    return $actionHTML;

                })
                // edit columns
                ->editColumn('profile_img', function( $employee ){
                    return '<div align="center">
                                <img src="'.$employee['profile_img'].'" alt="profile_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->editColumn('full_name', function($employee){
                    return '
                        <div>
                            <h5 class="text-primary">'.$employee['full_name'].'</h5>
                            <sup class="text-muted">'.$employee->getRoleNames()->first().'</sup>
                        </div>
                    ';
                })
                ->editColumn('address', 'includes.datatables.address')
                // raw columns
                ->rawColumns(['profile_img', 'full_name', 'contact', 'address', 'action'])
                //convert to json
                ->toJson();
    }

    public function store(AddEmployeeRequest $request)
    {
        $employee = Employee::create($request->except(['profile_img_hidden']));

        $this->__storeImage($employee);

        return response()->json([
            'title' => 'Successfully Added.',
            'text'  => 'Successfully added a new employee.',
        ]);
    }

    public function show(Employee $employee)
    {
        $employee->getRoleNames()->first();
        
        return $employee;
    }

    public function update(Employee $employee, UpdateEmployeeRequest $request)
    {
        $employee->update($request->except(['profile_img_hidden']));

        $this->__storeImage($employee);

        return response()->json([
            'title' => 'Successfully Updated.',
            'text'  => 'Any changes you made have been saved.',
        ]);
    }

    public function __storeImage($employee)
    {
        if( request()->hasFile('profile_img') ){
            $employee->update([
                'profile_img' => '/storage/'.request()->file('profile_img')->store('uploads', 'public'),
            ]);
        }
        else{
            $employee->update([
                'profile_img' => request()->input('profile_img_hidden'),
            ]);
        }
    }

    // ******** ALMOST MUSIC *********** //
    public function getEmployeesQoutas()
    {
        // get all employees and their qoutas
        // return $this->qoutaDependency->get_employeesAndQouatas();

        return DataTables::of( $this->qoutaDependency->get_employeesAndQouatas() )
                // add columns
                // ->addColumn('contact', 'includes.datatables.contact')
                // edit columns
                ->editColumn('profile_img', function($employee){
                    return '<div align="center">
                                <img src="'.$employee['profile_img'].'" alt="profile_img" class="image-50">
                            </div>';
                })
                ->editColumn('full_name', function($employee){
                    return '<h6 class="text-primary">'.$employee['full_name'].'</h6>';
                })
                // raw columns
                ->rawColumns(['profile_img', 'full_name'])
                //convert to json
                ->toJson();

    }

    // ****** ALL MOST TANG INA ****************//
    public function getProductsFor(Employee $employee)
    {
        // GET THE PRODUCTS NOT HOLDED BY CERTAIN EMPLOYEE
        return [
            'employee' => $this->employeeDependency->getEmployeeDetails($employee),
            'set_products' => $this->employeeDependency->getProductsForHolding( $employee ),
        ];
    }

    public function setProductsFor(Employee $employee, Request $request)
    {
        // VALIDATION
        $request->validate([
            'target_amount' => 'required',
        ]);

        // TARGET AMOUNT
        switch( $request->input('process_type_target_amount') )
        {
            case 'edit-target-amount':
                $latestTarget = '';
                foreach( $employee->target as $target )
                    $latestTarget = $target;

                $latestTarget->update([ 'target_amount' => $request->input('target_amount') ]);
            break;

            case 'change-target-amount':
                $employee->target()
                        ->create([
                            'target_amount' => $request->input('target_amount'),
                            'start_date' => $request->input('start_date'),
                        ]);
            break;

            default : 
                $employee->target()
                        ->create([
                            'target_amount' => $request->input('target_amount'),
                            'start_date' => $request->input('start_date'),
                        ]);
            break;
        }

        // UPDATE TARGET AMOUNT IF START DATE IS NOT CHANGE

        // ********************************************************************
        //                     ATTACH ALL SET PRODUCTS
        // ********************************************************************

        // PRODUCT IN REQUEST - ATTACHING OF IN REQUEST PRODUCT TO EMPLOYEE
        foreach( $request->input('products') as $inRequest_product ) {

            // CONFIRMATION IF CURRENTLY HOLDING BY EMPLOYEE
            $is_activelyHolded_byEmployee = false;

            $product = Product::find( $inRequest_product['id'] );
            // LOOP THROUGH EMPLOYEES OF - IN REQUEST PRODUCT
            foreach( $product->employee as $productsEmployee ) {
                
                if( $employee['id'] == $productsEmployee['id'] && $productsEmployee->pivot['active'] )
                    $is_activelyHolded_byEmployee = true;

                else if( $productsEmployee->pivot['active'] )
                    $productsEmployee->pivot->update( ['active' => false] );

            }

            // IF NOT ACTIVELY HOLDED BY CERTAIN EMPLOYEE
            if( !$is_activelyHolded_byEmployee ) {
                $employee->products()->attach( $inRequest_product['id'], ['active' => true] );
            }
        }

        // SECTION FOR REMOVING IF THERE ARE TO REMOVE PRODUCT IN HOLD
        foreach( $employee->products as $employeesProduct ) {
            //
            $notInHold = true;
            foreach( $request->input('products') as $inRequest_product ) {
                if( $employeesProduct['id'] == $inRequest_product['id'] )
                    $notInHold = false;
            }

            // if not in hold then update the ACTIVE attribute to FALSE
            if ( $notInHold )
                $employeesProduct->pivot->update( ['active' => false] );
        }

        // RETURN CONFIRMATION
        return response()->json([
            'title' => 'Success!',
            'text'  => 'Successfully set the products.',
        ]);
    }


    // QOUTA
    public function qoutas()
    {
        // query to get the employee with order transactions
        $employees = Employee::whereHas('orderTransaction', function($query){
                return $query->whereSales()
                            ->withDetails()
                            ->whereBetween('delivery_date', [
                                request()->query('from_date'),
                                request()->query('to_date')
                            ]);
            })
            ->with(['orderTransaction'  => function($query){
                return $query->whereSales()
                            ->withDetails()
                            ->whereBetween('delivery_date', [
                                request()->query('from_date'),
                                request()->query('to_date')
                            ]);
            }])
            ->get();

        // get total sales and get only the needed data...
        $employees = $employees->each( function($employee){
                // get total sales...
                $employee['total_sales'] = $employee['orderTransaction']
                                            ->reduce( function($carry, $value){
                                                return $carry + $value['total_cost'];
                                            }, 0);
            } )
            ->map( function($employee){
                // return only the needed data
                return [
                    'profile_img' => $employee['profile_img'],
                    'full_name' => $employee['full_name'],
                    'role' => $employee->getRoleNames()->first(),
                    'total_sales' => $employee['total_sales'],
                    'total_sales_format' => '&#8369; '.number_format($employee['total_sales'], 2),
                ];
            } );
        
        // return the datatables
        return DataTables::of( $employees )
               
                // edit columns
                // edit columns
                ->editColumn('profile_img', function( $employee ){
                    return '<div align="center">
                                <img src="'.$employee['profile_img'].'" alt="profile_img" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->editColumn('full_name', function($employee){
                    return '
                        <div>
                            <h5 class="text-primary">'.$employee['full_name'].'</h5>
                            <sup class="text-muted">'.$employee['role'].'</sup>
                        </div>
                    ';
                })
                ->editColumn('total_sales_format', function($employee){
                    return '<span class="text-primary font-weight-bolder">'.$employee['total_sales_format'].'</span>';
                })
                // raw columns
                ->rawColumns([ 'profile_img', 'full_name', 'total_sales_format', ])
                //convert to json
                ->toJson();
        
    }


}
