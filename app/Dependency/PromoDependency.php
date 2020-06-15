<?php

namespace App\Dependency;

use App\Dependency\BroadDependency;

// custom class dependency
use App\Dependency\ProductDependency;

use App\Dependency\AccountDependency;

// model class
use App\Promo;

use App\Account;

class PromoDependency extends BroadDependency
{

	protected $productDependency;

	protected $accountDependency;

	public function __construct()
	{
		$this->productDependency = new ProductDependency();
		$this->accountDependency = new AccountDependency();
	}

	public function setPromos()
	{
		//
	}

	public function getPromos()
	{
		$promos = Promo::all();
		foreach($promos as $promo) {
			$this->get_promoDetails($promo);
		}
		$this->setPages($promos);

		// return all the promos
		return $promos;
	}

	public function get_promoDetails($deal)
	{
		// get the product
		$this->productDependency->setProduct_Formatted($deal->product);
		// get the accounts in promo
		$deal->accounts;
		// accounts not already in any promo
		$deal['accounts_not_in_any_promo'] = $this->__get_theAccountsNotAlreadyInAnyPromoOf($deal->product);

		// return with its dependency data
		return $deal;
	}

	// VERIFICATIONS

	public function get_availableProductsForCreatingPromo()
	{
		$accounts = $this->setPages( \App\Account::orderBy('account_name')->get() );
		// set page also
		$products = $this->setPages( \App\Product::orderBy('generic_name', 'asc')->get() );

		// LOOP THROUGH PRODUCTS
		foreach($products as $product) {
			// EACH PRODUCTS
			$accountsNotAlreadyInAnyPromo = $this->__get_theAccountsNotAlreadyInAnyPromoOf($product);

			$count_theAccountAlreadyInPromo = 0;
			foreach($accountsNotAlreadyInAnyPromo as $accountNotAlreadyInAnyPromo) {
				if( !$accountNotAlreadyInAnyPromo['show']) { // COUNT THE TIMES OF ACCOUNT THAT IS ALREADY IN PROMO
					$count_theAccountAlreadyInPromo++;
				}
			}

			$product['available_accounts'] = $accountsNotAlreadyInAnyPromo;

			// SHOW MAKES TO FALSE
			if($count_theAccountAlreadyInPromo === count($accounts) ) {
				$product['show'] = false;
			}
		}

		return $products;
	}

	// return accounts - false the account that is already in any promo of a certain product
	public function __get_theAccountsNotAlreadyInAnyPromoOf($product)
	{
		// GET ACCOUNTS - WELL FORMATTED
		$accounts = $this->accountDependency->getAccounts();
		// LOOP THROUGH PROMOS OF SINGLE PRODUCT
		foreach($product->deals as $deal) {
			// LOOP THROUGH ALL ACCOUNTS
			foreach($accounts as $account) {
				// BOOLEAN - IF ACCOUNT NOT IN PROMO
				$isInPromo = false;
				// LOOP THROUGH ALREADY IN PROMO
				foreach($this->__get_accountsInPromo($deal) as $accountInPromo) {
					// IF ACCOUNT IS ALREADY IN PROMO
					if($account['id'] === $accountInPromo['id']) {
						$isInPromo = true;
					}
				}
				// SHOW TURN TO FALSE
				if($isInPromo)
					$account['show'] = false;

				// $account['selected'] = false; // initialized default value - and index
			}
			// end foreach - product promos
		}

		return $accounts;
	}

	// GET ACCOUNTS IN SINGLE PROMO
	public function __get_accountsInPromo($deals)
	{
		// REPOSITORY FOR ACCOUNTS IN PROMO
		$accountsInPromo = array();
		// LOOP THROUGH PROMO - GET EACH ACCOUNT
		foreach($deals->accounts as $account) {
			// ACCOUNT IN PROMO
			array_push($accountsInPromo, $account);
		}

		return $accountsInPromo; // return all account in promo
	}

	// FOR CREATE ORDER CONTROLLER - CREATE ORDER

	// get promo of product for a certain account
	public function getPromoFor($paramAccount, $paramProduct)
	{
		// get promo of product for dedicated account
		$promoOfProductForAccount = null;
		// promos of product
		foreach($paramProduct->deals as $deal) {
			$product_dedicatedPromoForAccount = false;
			// accounts in promo
			foreach($deal->accounts as $account) {
				// if account is true
				if($account['id'] === $paramAccount['id']) {
					$product_dedicatedPromoForAccount = true;
				}
			}
			// insert the promo dedicated for product
			if($product_dedicatedPromoForAccount) {
				$promoOfProductForAccount = $deal;
			}
		}

		if( is_null($promoOfProductForAccount) )
			return null;
		else {
            // $this->__set_promoDetails_Formatted( $promoOfProductForAccount );
            // return the promo of product for its acount
			return $promoOfProductForAccount;
		}
	}


	// INSERTING THE APPROPRIATE PROMO FOR SPECIFIED ACCOUNT
	// public function setPromosFor( $account, $products )
	// {
	// 	// insert their promos
 //        foreach($products as $product) {
 //            // return also its promo
 //            $product['deals'] = $this->getPromoFor($account, $product);
 //        }

 //        return $products;
	// }

}