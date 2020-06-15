<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;
use App\Employee;
use App\Supplier;
use App\Product;

use App\Returnee;

use App\OrderTransaction;
use App\OrderMedicine;

use App\BatchNo;

use App\CollectionTransaction;

// custom classes
// use App\Dependencies\BroadDependency;

class AdminController extends Controller
{

    public function __construct()
    {
        // $this->broadDependency = new BroadDependency();
    }

	public function dashboard()
    {
        $accounts = Account::all();
        $employees = Employee::all();
        $products = Product::all();

        $totalSales = $this->broadDependency->getTotalSales();
        $totalCollections = $this->broadDependency->getTotalCollections();

		return view('dashboard', compact(['accounts', 'employees', 'products', 'totalSales', 'totalCollections']));
	}

    // inventory mgt - parent tab
    public function productMgt()
    {
        $products = Product::all();
        $suppliers = Supplier::orderBy('supplier_name')->get();

    	return view('inventory.product', compact(['products', 'suppliers']));
    }

    public function freeMedicineMgt()
    {
        $products = Product::all();
        $customer_accounts = Account::all();
    
        return view('inventory.free', compact(['products', 'customer_accounts']));
    }

    public function returneeMgt()
    {
        $returnees = Returnee::all();

        return view('inventory.returnee', compact(['returnees']));
    }
    // end inventory mgt - parent tab

    public function salesMgt()
    {
        $sales = OrderTransaction::where('status', 'Delivered')
                                ->orWhere('status', 'Balanced')
                                ->orWhere('status', 'Paid')
                                ->get();

    	return view('transactions.sales', compact(['sales']));
    }

    public function accountsReceivable()
    {
        $receivables = OrderTransaction::where('status', 'Delivered')
                                        ->orWhere('status', 'Balanced')
                                        ->get();

        return view('receivable.index', compact(['receivables']));
    }

    public function collectionMgt()
    {
        $collections = OrderTransaction::where('status', 'Paid')
                                        ->orWhere('status', 'Balanced')
                                        ->get();

    	return view('transactions.collection', compact(['collections']));
    }

    public function orderMgtCreate()
    {
        $products = Product::all();
        $accounts = Account::all();

        return view('order.create', compact(['products', 'accounts']));
    }

    public function orderMgtView()
    {
        $orders = OrderTransaction::where('status', 'Pending')->get();

        return view('order.view', compact('orders'));
    }

    public function orderMgtTrack()
    {
        $orders = OrderTransaction::where('status', 'Pending')
                                    ->orWhere('status', 'Approved')
                                    ->orWhere('status', 'Canceled')
                                    ->get();

        return view('order.track', compact(['orders']));
    }

    public function userMgtAccount()
    {
        $accounts = Account::all();

        return view('users.account', compact('accounts'));
    }

    public function userMgtEmployee()
    {
        $employees = Employee::all();
        $positions = Employee::first();

        return view('users.employee', compact('employees', 'positions'));
    }

    public function userMgtSupplier()
    {
        $suppliers = Supplier::all();

        return view('users.supplier', compact('suppliers'));
    }

}