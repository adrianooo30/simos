<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

// custom dependency class
use App\Dependency\EmployeeDependency;

// facades
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $employeeDependency;

    public function __construct(EmployeeDependency $employeeDependency)
    {
        $this->employeeDependency = $employeeDependency;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // but not actually to store datas
    public function store(Request $request, $authenticated = false)
    {
        // validates the input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // searching for employee
        $theUser; // stored the employee datas
        foreach(\App\Employee::all() as $employee){
            if($employee['username'] === $request->input('username') && $employee['password'] === $request->input('password'))
            {
                $authenticated = true;
                $theUser = $employee;
            }
        }

        if($authenticated){
            // username and password and position_id is stored in session
            session([
                'username' => $request->input('username'),
                'password' => $request->input('password'),
                'position_id' => $theUser['position_id'],
            ]);

            // set employee well formatted
            $this->employeeDependency->setEmployee_Formatted($theUser);
            $theUser->position;

            // storing more user data
            session([ 'employee' => $theUser ]);

            // go to dashboard
            return redirect()->route('dashboard');
        }
        // is not authenticated
        else{
            return redirect()
                    ->route('login')
                    ->with('errorMessage', 'The username/password is incorrect.' );
        }
    }


    public function forgotPassword(Request $request, $authenticated = false)
    {

        $theUser; // stored the employee datas
        foreach(\App\Employee::all() as $employee){
            if($employee['username'] === session('username'))
            {
                $authenticated = true;
                $theUser = $employee;
            }
        }
        
        if($authenticated){
            // username and password and position_id is stored in session
            // session([
            //     'username' => $request->input('username'),
            //     'password' => $request->input('password'),
            //     'position_id' => $theUser['position_id'],
            // ]);

            // set employee well formatted
            $this->employeeDependency->setEmployee_Formatted($theUser);
            $theUser->position;

            // storing more user data
            // session([ 'employee' => $theUser ]);

            // go to dashboard
            return redirect()->route('dashboard');
        }
        
        
    }
}
