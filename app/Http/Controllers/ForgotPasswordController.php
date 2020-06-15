<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Employee;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function checkUser($username){

    	if(Employee::where('username', $username)->exists()){


    		$employee_object = Employee::where('username', $username)->first();


    		$name = $employee_object->fname .' '. $employee_object->lname;
    		$email = $employee_object->email;

    		//codes for generating random characters
    		$recovery_code = "";
    		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    		$charArray = str_split($chars);

    		for($i = 0; $i < 6; $i++){
    			$randItem = array_rand($charArray);
    			$recovery_code  = $recovery_code.$charArray[$randItem];
    		}


    		// $recovery_code = "123"; 	
    	

    		//store values in session
    		session([
                'username' => $employee_object->username,
                'password' => $employee_object->password,
                'position_id' => $employee_object->position_id,
                'recovery_code' => $recovery_code,
                'employee' => $employee_object,

            ]);

    		// codes for sending email
    		$to_name = $name;
			$to_email = $email;

			$data = array("name"=>$name, "body" => session('recovery_code'));
			
			Mail::send("mail", $data, function($message) use ($to_name, $to_email) {
			$message->to($to_email, $to_name)
			->subject("Recovery code");
			$message->from("aprilmegz06@gmail.com", "SIMOOS");
			});


    		return json_encode("existing");

    	}


    	else{

    		return json_encode("does not exist");
    	
    	}

    	

    }


    public function checkRecoveryCode($recoverycode){

    	if($recoverycode == session('recovery_code')){

    		return json_encode('correct');
    	}

    	else{
    		return json_encode('wrong');
    	}

    }


    public function changePassword(Request $request){

    	$employee_object = Employee::where('username', session('username'))->first();



        $employee_object->password = $request->get('newpassword');
        

        $employee_object->save();

    	return redirect()->route('login.recovery');

    }


}
