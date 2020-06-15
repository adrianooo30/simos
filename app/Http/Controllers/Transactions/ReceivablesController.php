<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Account;
use App\OrderTransaction;
use App\OrderMedicine;
use App\CollectionTransaction;
use App\Cash;
use App\Cheque;
use App\Employee;

// use custom classes
use App\Dependency\OrderDependency;
use App\Dependency\EmployeeDependency;
use App\Dependency\SalesDependency;
use App\Dependency\ReceivablesDependency;

use App\Dependency\CollectionDependency;

// for plugin
use Yajra\Datatables\DataTables;

// includes
use Illuminate\Support\Facades\View;

class ReceivablesController extends Controller
{

    protected $receivablesDependency;

    protected $orderDependency;

    protected $salesDependency;

    protected $collectionDependency;

    protected $employeeDependency;

    public function __construct(ReceivablesDependency $receivablesDependency, OrderDependency $orderDependency, SalesDependency $salesDependency, EmployeeDependency $employeeDependency, CollectionDependency $collectionDependency)
    {
        $this->receivablesDependency = $receivablesDependency;

        $this->orderDependency = $orderDependency;
        $this->salesDependency = $salesDependency;

        $this->employeeDependency = $employeeDependency;

        // collection
        $this->collectionDependency = $collectionDependency;
    }

    public function index()
    {
        return DataTables::of( Account::whereHasBill() )
                ->addColumn('total_bill_format', function( $account ){
                    return '<span class="text-danger font-weight-bolder">'.$account->pesoFormat($account['total_bill']).'</span><br>
                            <sub class="text-muted font-weight-bolder">Total Bill</sub>';
                })
                ->addColumn('total_bill', function( $account ){
                    return $account['total_bill'];
                })
                ->addColumn('action', function( $account ){
                    return '<a href="'.route('transactions.receivables.show', ['account' => $account['id']]).'" class="btn btn-primary waves-effect waves-light">
                                <i class="ti-bookmark-alt"></i>
                            </a>';
                })
                // edit columns
                ->editColumn('profile_img', function( $account ){
                    return '<div align="center">
                                <img src="'.$account['profile_img'].'" class="img-thumbnail" width="50" height="50">
                            </div>';
                })
                ->editColumn('account_name', function( $account ){
                    return '<div>
                                <h5 class="text-darker">'.$account['account_name'].'</h5>
                                <h6 class="text-muted">
                                    <i class="ti-user"></i> '.$account['type'].'
                                </h6>
                            </div>';
                })
                // raw columns
                ->rawColumns(['profile_img', 'account_name', 'total_bill_format', 'action', ])
                //convert to json
                ->toJson();
    }

    public function show(Account $account)
    {
        $order_transactions = $account->orderTransaction()
                                        ->whereBills()
                                        ->withDetails()
                                        ->withTotalBill()
                                        ->get();

        return view('receivable.show', compact(['account', 'order_transactions']));
    }

    // get the order transactions.
    public function showOrderTransaction(Account $account)
    {
        return DataTables::of( $this->receivablesDependency->get_tableOfBills($account) )
                ->addColumn('checkbox', function( $orderTransaction ){
                    return '<div class="custom-control custom-checkbox" id="">
                                <input type="checkbox" class="custom-control-input sub-checkbox-o" id="sub-checkbox-o-'.$orderTransaction['id'].'" data-order-transaction-id="'.$orderTransaction['id'].'">
                                <label class="custom-control-label sub-checkbox-o-label" for="sub-checkbox-o-'.$orderTransaction['id'].'"></label>
                            </div>';
                })
                ->addColumn('receipt_no', function( $orderTransaction ){
                    return '<label for="sub-checkbox-o-'.$orderTransaction['id'].'" class="text-white badge badge-primary font-14" style="cursor:pointer;">'.$orderTransaction['deliverTransaction']['receipt_no'].'</label>';
                })
                ->addColumn('totals', function( $orderTransaction ){

                    return '<div>
                                <h5 class="text-primary">'.$orderTransaction['total_cost_format'].'</h5>
                                <sup>Total Cost</sup>   
                            </div>
                            <div>
                                <h5 class="text-danger">'.$orderTransaction['bill_amount_format'].'</h5>
                                <sup>Total Bill</sup>   
                            </div>
                            <div>
                                <h5 class="text-success">'.$orderTransaction['paid_amount_format'].'</h5>
                                <sup>Total Paid Amount</sup>    
                            </div>';

                })
                ->addColumn('inputs', 'includes.datatables.receivables-payment-form')
                // raw columns
                ->rawColumns(['checkbox', 'receipt_no', 'totals', 'inputs'])
                //convert to json
                ->toJson();
    }

    public function getOrderMedicines(OrderTransaction $orderTransaction)
    {
        // order transaction
        $receivables = $orderTransaction->fresh()
                            ->withDetails()
                            ->find($orderTransaction['id']);
        // order medicine
        $receivables['order_medicine'] = $orderTransaction
                                        ->orderMedicine()
                                        ->withDetails()
                                        ->get();

        // payment order medicine
        $payment_order_medicines = request()->get('payment_order_medicines');
        // return
        return response()->json([
            'order_medicines_payable_html' => View::make('includes.receivables.order-medicines-to-pay', compact(['payment_order_medicines', 'receivables'] ))->render(),
            'receipt_no' => $orderTransaction->deliverTransaction['receipt_no'],
        ]);
    }

    public function receivablePayment(Account $account, Request $request)
    {
        // VALIDATION FOR COLLECTION TRANSACTION
        $validatedCollectionTransaction = $request->validate([
            'receipt_no' => 'required|unique:collection_transactions',
            'collection_date' => 'required',
            'mode_of_payment' => '',
            'employee_id' => '',
        ]);

        // VALIDATION FOR CHEQUE
        if($request->input('mode_of_payment') == 'cheque') {
            $request->validate([
                'cheque_no' => 'required',
                'date_of_cheque' => 'required|date',
                'bank' => 'required',
            ]);
        }

        // VALIDATION FOR BILLS TO PAID 
        $request->validate(['paid_order_medicines' => 'required']);

        // INSERT COLLECTION TRANSACTION
        $collectionTransaction = CollectionTransaction::create($validatedCollectionTransaction);

        // CREATE CHEQUE DATAS
        if($request->input('mode_of_payment') == 'cheque')
        {
            $collectionTransaction->cheque()->create([
                'cheque_no' => $request->input('cheque_no'),
                'date_of_cheque' => $request->input('date_of_cheque'),
                'bank' => $request->input('bank'),
            ]);
        }

        // ORDER TRANSACTIONS IN COLLECTION TRANSACTION
        foreach ($request->input('paid_order_medicines') as $paid_order_medicine) {
            // ORDER MEDICINE FIND
            $orderMedicine = OrderMedicine::find($paid_order_medicine['order_medicine_id']);
            // ATTACH
            $collectionTransaction->orderMedicines()->attach([
                $paid_order_medicine['order_medicine_id'] => [
                    'paid_quantity' => $paid_order_medicine['quantity'],
                    'paid_amount' => $paid_order_medicine['quantity'] * $orderMedicine['unit_price'],
                ],
            ]);// attach, detach, sync, syncWithoutDetaching
        }

        // UPDATE STATUS OF SINGLE ORDER TRANSACTION
        $involved_order_transactions = collect($request->get('paid_order_medicines'))
                                    ->pluck('order_transaction_id')
                                    ->unique();

        // UPDATE STATUS OF A SINGLE TRANSACTION
        foreach($involved_order_transactions as $id)
            OrderTransaction::find($id)->trackStatus();

        // set total bill of account...
        $account->setTotalBill();

        // RETURN SUCCESS RESPONSE
        return response()->json([
            'title' => 'Successfully Recorded',
            'text' => 'Successfully recorded payment for this account.',
        ]);
    }

}
