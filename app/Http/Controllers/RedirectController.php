<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{

    public function __construct()
    {
    	//
    }

    public function inventoryProduct(Request $request)
    {
    	return $request->input();

    	return view('inventory.product');
    }

}
