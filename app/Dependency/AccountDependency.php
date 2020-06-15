<?php

namespace App\Dependency;

// custom dependency classes
use App\Dependency\BroadDependency;

use App\Dependency\SalesDependency;

use App\Account;

class AccountDependency extends BroadDependency
{

	protected $salesDependency;

	public function __construct()
	{
		$this->salesDependency = new SalesDependency();
	}

	public function setAccount()
	{
		//
	}

	public function getAccounts()
	{
		$accounts = Account::orderBy('account_name', 'asc')->get();
		// set pages
		$this->setPages($accounts);
		// return accounts
		return $accounts;
	}

	public function getAccountDetails($account)
	{
		// add a custom attributes in account
			# code here
		// return account
		return $account;
	}

	// ***************************************************************
	// 					THIRD PARTY - TRANSACTIONS
	// ***************************************************************

	// all accounts with balance
	public function get_accountsWithBalance($accounts)
	{
		//
		$tempAccounts = array();
		foreach($accounts as $account)
		{
			// inserting account with balance - filtering the account with balance
			if($this->get_accountTotalBalance($account)['total_bill'] > 0)
				array_push($tempAccounts, $account);
		}
		// passing the account with balance
		$accounts = $tempAccounts;
		// return accounts with balance
		return $accounts;
	}

	// total balance of single account
	// return always even the balance is 0
	public function get_accountTotalBalance($account)
	{
		$account['total_bill'] = 0;
        foreach ($account->orderTransaction as $orderTransaction)
        {
            if($orderTransaction['status'] === 'Delivered' || $orderTransaction['status'] ===  'Partially Paid'){
            	$account['total_bill'] += $this->salesDependency->set_totalBill_and_excessPayment_Formatted($orderTransaction)['total_bill'];
            }
        }
        $account['total_bill_format'] = $this->currencyType.' '.number_format($account['total_bill'], 2);
        // return account with its total bills
        return $account;
	}
}