<?php

namespace App\Dependency;

use App\Dependency\BroadDependency;

// custom class dependency
use App\Dependency\ProductDependency;
use App\Dependency\AccountDependency;

// model class
use App\Price;
use App\Account;

class PriceDependency extends BroadDependency
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

	public function getPrices()
	{
		$prices = Price::all();
		foreach($prices as $price) {
			$this->get_priceDetails($price);
		}
		$this->setPages($prices);

		// return all the prices
		return $prices;
	}

	public function get_priceDetails($price)
	{
		// get the product
		$this->productDependency->setProduct_Formatted($price->product);
		// get the accounts in promo
		$price->accounts;
		// accounts not already in any promo
		$price['accounts_not_in_any_price'] = $this->__get_theAccountsNotAlreadyInAnyPriceOf($price->product);

		// return with its dependency data
		return $price;
	}

	// VERIFICATIONS

	public function get_availableProductsForCreatingPrice()
	{
		$accounts = $this->setPages( \App\Account::orderBy('account_name')->get() );
		// set page also
		$products = $this->setPages( \App\Product::orderBy('generic_name')->get() );

		// LOOP THROUGH PRODUCTS
		foreach($products as $product) {
			// EACH PRODUCTS
			$accountsNotAlreadyInAnyPrice = $this->__get_theAccountsNotAlreadyInAnyPriceOf($product);

			$count_theAccountAlreadyInPrice = 0;
			foreach($accountsNotAlreadyInAnyPrice as $accountNotAlreadyInAnyPrice) {
				if( !$accountNotAlreadyInAnyPrice['show']) { // COUNT THE TIMES OF ACCOUNT THAT IS ALREADY IN PROMO
					$count_theAccountAlreadyInPrice++;
				}
			}

			$product['available_accounts'] = $accountsNotAlreadyInAnyPrice;

			// SHOW MAKES TO FALSE
			if($count_theAccountAlreadyInPrice === count($accounts) ) {
				$product['show'] = false;
			}
		}

		return $products;
	}

	// return accounts - false the account that is already in any promo of a certain product
	public function __get_theAccountsNotAlreadyInAnyPriceOf($product)
	{
		// GET ACCOUNTS - WELL FORMATTED
		$accounts = $this->setPages( \App\Account::all() );
		// LOOP THROUGH PROMOS OF SINGLE PRODUCT
		foreach($product->prices as $price) {
			// LOOP THROUGH ALL ACCOUNTS
			foreach($accounts as $account) {
				// BOOLEAN - IF ACCOUNT NOT IN PROMO
				$isInPrice = false;
				// LOOP THROUGH ALREADY IN PROMO
				foreach($this->__get_accountsInPrice($price) as $accountInPrice) {
					// IF ACCOUNT IS ALREADY IN PROMO
					if($account['id'] === $accountInPrice['id']) {
						$isInPrice = true;
					}
				}
				// SHOW TURN TO FALSE
				if($isInPrice)
					$account['show'] = false;

				// $account['selected'] = false; // initialized default value - and index
			}
			// end foreach - product promos
		}

		return $accounts;
	}

	// GET ACCOUNTS IN SINGLE PROMO
	public function __get_accountsInPrice($price)
	{
		// REPOSITORY FOR ACCOUNTS IN PROMO
		$accountsInPromo = array();
		// LOOP THROUGH PROMO - GET EACH ACCOUNT
		foreach($price->accounts as $account) {
			// ACCOUNT IN PROMO
			array_push($accountsInPromo, $account);
		}

		return $accountsInPromo; // return all account in promo
	}

	// FOR CREATE ORDER CONTROLLER - CREATE ORDER

	// get promo of product for a certain account
	public function getPriceFor($paramAccount, $paramProduct)
	{
		// get promo of product for dedicated account
		$promoOfProductForAccount = null;
		// promos of product
		foreach($paramProduct->prices as $price) {
			$product_dedicatedPromoForAccount = false;
			// accounts in promo
			foreach($price->accounts as $account) {
				// if account is true
				if($account['id'] === $paramAccount['id']) {
					$product_dedicatedPromoForAccount = true;
				}
			}
			// insert the promo dedicated for product
			if($product_dedicatedPromoForAccount) {
				$promoOfProductForAccount = $price;
			}
		}

		if( is_null($promoOfProductForAccount) )
			return $paramProduct;
		else {
            // $this->__set_promoDetails_Formatted( $promoOfProductForAccount );
            // return the promo of product for its acount
			return $promoOfProductForAccount;
		}
	}


	// INSERTING THE APPROPRIATE PROMO FOR SPECIFIED ACCOUNT
	// public function setPricesFor( $account, $products )
	// {
	// 	// insert their promos
 //        foreach($products as $product) {
 //            // return also its promo
 //            $product['prices'] = $this->getPriceFor($account, $product);
 //        }

 //        return $products;
	// }

}