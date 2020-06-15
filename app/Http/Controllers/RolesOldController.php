<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// custom classes
use App\Dependency\RolesDependency;

use App\Dependency\EmployeeDependency;

// model class
use App\Roles;
use App\Employee;
use App\Method;

class RolesOldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $rolesDependency;

    protected $employeeDependency;

    public function __construct(RolesDependency $rolesDependency, EmployeeDependency $employeeDependency)
    {
        $this->rolesDependency = $rolesDependency;
        $this->employeeDependency = $employeeDependency;
    }

    public function index()
    {
        return $this->rolesDependency->get_positionAndTheirRoles();
    }

    public function get_modulesAndTheirMethods()
    {
        return $this->rolesDependency->get_modulesAndTheirMethods();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required',
            'assigned_roles' => 'required',
        ]);

        $this->rolesDependency->set_positionAndRoles( strtoupper( $request->input('position') ), $request->input('assigned_roles'));

        return response()->json([
            'title' => 'Successfully Added.',
            'text' => 'Successfully added new position.',
        ]);
    }

    public function updateRoles(Request $request)
    {
        foreach($request->input('methods') as $method)
            Method::find($method['id'])->update([ 'allowed' => $method['allowed'] ]);

        // update the session data stored
        // $this->__set_updateSessionStored();

        return response()->json([
            'title' => 'Successfully Updated.',
            'text' => 'Successfully updated any changes that you made.',
        ]);
    }

    // only access by this controller
    public function __set_updateSessionStored()
    {
        // update session stored
        $theUser;
        foreach(Employee::all() as $employee){
            if($employee['username'] === session('username') && $employee['password'] === session('password'))
                $theUser = $employee;
        }

        $this->employeeDependency->setEmployee_Formatted( Auth::user() );
        // get the position of the employee
        $theUser->position;
        // set the session
        session(['employee' => $theUser]);
    }

}
