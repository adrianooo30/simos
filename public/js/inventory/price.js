// ******************************************************************************
//						SELECT BOX - FOR ADD price
// ******************************************************************************

var Products = [];
// get products
function getProductsViaAjax()
{
	axios.get('/ajax/inventory/price/products')
		.then((response) => {
			console.log('%c products to create price', 'color : green;');
			console.log(response.data);

			Products = response.data;
			set_selectBoxProducts(); // set the available products
		})
		.catch((error) => {
			console.log(error.response);
		});
}

// in background - daya mode
function getProductsViaAjax_daya()
{
	axios.get('/ajax/inventory/deals/products')
		.then((response) => {
			Products = response.data;
		})
		.catch((error) => {
			console.log(error.response);
		});
}


(function(){ getProductsViaAjax() })();

// set select box the options
function set_selectBoxProducts()
{
	let productSelectBoxes = $('.--select-box-products.for-add-price');
	// refresh the select
	$(productSelectBoxes).empty();
	// add prompt select product
	$(productSelectBoxes).append(`
		<option value="" disabled selected>--SELECT PRODUCT--</option>
	`);
	// APPENDING ALL AVAILABLE PRODUCTS IN 
	Products.forEach(function(product, index){
		if( product['show'] ) {
			$(productSelectBoxes).append(`
				<option value="${ product['id'] }">${ product['product_name'] } - ${ product['brand_name'] }</option>
			`);
		}
	});
}

var productsDynamic = [];
function copyProducts()
{
	productsDynamic = [];
	Products.forEach(function(product){
		productsDynamic.push(product);
	});
}

// ONCHANGE - OF SELECTED PRODUCTS - IMPACT ON SELECTING AVAILABLE PRODUCTS
$('.--select-box-products.for-add-price').change(function(){
	getProductsViaAjax_daya();
	//
	let selectBoxValue = parseInt( $(this).val() );

	copyProducts(); //

	productsDynamic.forEach(function(product, index){
		if( selectBoxValue == product['id'] ) { // will be true only once...

			// display the default unit price...
			$('#--unit-price-html').html(`
					<h3 class="text-primary font-weight-lighter  for-add-price">
						${ setPesoFormatted(product['unit_price'] ) }
					</h3>
					<sup class="text-muted">Default Unit Price</sup>
			`);

			set_selectBoxAccounts( product['available_accounts'] );

			// set the listing of accounts to empty
			let accountAddedList = $('.--added-account-in-price.for-add-price');
			accountAddedList.empty();
			// set again the display of account selected to - no selected accounts
			track_ifNoSelected();
		}
	});

});

var currentAccounts = [];
// set select box the options
function set_selectBoxAccounts(accounts) // SETTING OF ACCOUNTS IS DEPENDENT IN PRODUCT SELECTED
{
	currentAccounts = accounts;

	// query selector
	let accountSelectBoxes = $('.--select-box-accounts.for-add-price');
	// refresh the select
	$(accountSelectBoxes).empty();
	// add prompt select product
	$(accountSelectBoxes).append(`
		<option value="" disabled selected>--ADD ACCOUNT--</option>
	`);
	// available accounts in products - for adding of new price
	currentAccounts.forEach(function(account, index) {
		if(account['show']) {
			$(accountSelectBoxes).append(`
				<option value="${ account['id'] }">${ account['account_name'] } - ${ account['type'] }</option>
			`);
		}
	});
}
// *****************************************************************************
//						FOR DEEPER LOGICS - FOR ADDING OF ACCOUNTS
// *****************************************************************************

function addAccountToPrice()
{
	let accountAddedList = $('.--added-account-in-price.for-add-price');

	if( $('[name="no_selected"].for-add-price').val() == 'true' )
		accountAddedList.empty();

	currentAccounts.forEach(function(account, index){
		//
		if( $('[name="account_id"].for-add-price').val() == account['id']) {
			account['show'] = false; // dont show
			account['selected'] = true; // selected turn into true

			accountAddedList.append(`
				<li class="list-group-item text-primary d-flex justify-content-between card-shadow" id="--account-list-${ account['id'] }">
			    	<span>
			    		<i class="ti-user"></i> ${ account['account_name'] } - ${ account['type'] }
			    	</span>
			    	<button type="button" class="btn btn-sm btn-outline-danger" data-account-id="${ account['id'] }" onclick="removeAccountToPrice(this)">
			    		<i class="ti-close"></i>
			    	</button>
			    </li>
			`);
		}
	});

	track_ifNoSelected();
	set_selectBoxAccounts(currentAccounts); // i think this is recursion
}

// on change in selection of add price
$('.--select-box-accounts.for-add-price').on('change', addAccountToPrice);

function removeAccountToPrice(object)
{
	// return back to true the account if not selected
	currentAccounts.forEach(function(account, index) {
		if( object.dataset['accountId'] == account['id']) {
			account['show'] = true; // show in select
			account['selected'] = false; // selected turn into false

			$(`#--account-list-${ object.dataset['accountId'] }`).remove();
		}
	});

	track_ifNoSelected(); // displays no selected interface
	set_selectBoxAccounts(currentAccounts);// set select box accounts again -  // i think this is recursion
}

function track_ifNoSelected()
{
	let accountAddedList = $('.--added-account-in-price.for-add-price');
	// if no account added in list
	if( $( '.--added-account-in-price.for-add-price li').length == 0 ) {			
		$('[name="no_selected"].for-add-price').val(true);

		accountAddedList.html(`
			<li class="list-group-item text-muted text-center card-shadow">
		    	No account list in price yet.
		    </li>
		`);
	}
	else
		$('[name="no_selected"].for-add-price').val(false);
}

// ******************************************************************************
//						END OF SELECT BOX - FOR ADD price
// ******************************************************************************

// ******************************************************************************
//						price FORM - ADDING OF NEW price
// ******************************************************************************
$('#price-add-form').submit( function(e) {

	e.preventDefault();

	let accountsInprice = [];
	currentAccounts.forEach(function(account, index){
		if( account['selected'] )
			accountsInprice.push(account['id']);
	});

	console.log(accountsInprice);

	axios.post('/ajax/inventory/price', {
		'product_id' : $('[name="product_id"].for-add-price').val(),
		'unit_price' : $('[name="unit_price"].for-add-price').val(),
		'accountIds' : accountsInprice,
	})
		.then((response) => {

			console.log(response.data);

			// setting to default - refreshing
			// refresh -----
			getProductsViaAjax();
			// getpricesViaAjax();

			pricesTable.ajax.reload();

			// remove child nodes of this
			$('#--unit-price-html').empty();
			// remove input values
			$('.for-add-price').val('');
			// set again to default
			$('[name="no_selected"].for-add-price').val(true);

			// set again to no selected values
			$('.--added-account-in-price.for-add-price').html(`
				<li class="list-group-item text-muted text-center">
			    	No account list in price yet.
			    </li>`);
			// end of setting to default - refreshing

			console.log(response.data);

			// sweet alert
			successAlert( response );

		})
		.catch((error) => {
			errorAlert(error);
			console.log(error.response);

			// remove error message
			$.each( $('.error-message.for-add-price'), (key, object) => object.remove() );

			// show error messages
			$.each(error.response.data.errors, (key, value) => {
				$(`[name="${ key }"].for-add-price`).after(`<label class="error-message for-add-price imp">${ value }</label>`);
			});

		});
});

// **************************************************************
//					GET price DETAILS
// **************************************************************

var priceDetails = [];
var priceId = 0;
function getPriceDetails(id)
{
	priceId = id; // price id

	let loading = new ModalLoading('#edit-price-modal');
	loading.setLoading();

	axios.get(`/ajax/inventory/price/${ priceId }`)
		.then((response) => {

			console.log(response.data);

			loading.removeLoading();

			priceDetails = response.data;

			// product details
			$('#--product-details').html(`
				<div class="text-center">
					<img src="${ priceDetails['product']['product_img'] }" class="image-150 img-thumbnail"><br>
				
					<div class="name">
				        <h4 class="text-primary lighter">${ priceDetails['product']['product_name'] }</h4>
				        <h4 class="text-muted lighter">${ priceDetails['product']['brand_name'] }</h4>
				        <h4 class="text-primary lighter">&#8369; ${ priceDetails['product']['unit_price'] }</h4>
				        <sup class="text-muted">Default Unit Price</sup>
				    </div>
				</div>
			`);

			$('#--edit-unit-price').val(priceDetails['unit_price']);

			// select boxes for selecting of accounts
			let listOfAccountsInprice = $('.--added-account-in-price.for-edit-price');
			// empty the select box first
			$(listOfAccountsInprice).empty();

			// listing the accounts in price
			priceDetails['accounts'].forEach(function(account, index){

				// set the default seelcted price to true - THIS CODE!!!
				priceDetails['accounts_not_in_any_price'].forEach(function(accountNotInprice, index){
					if(account['id'] === accountNotInprice['id'])
						accountNotInprice['selected'] = true;
				});

				$(listOfAccountsInprice).append(`
					<li class="list-group-item text-primary d-flex justify-content-between" id="--account-edit-list-${ account['id'] }">
				    	<span>
				    		<i class="ti-user"></i> ${ account['account_name'] } - ${ account['type'] }
				    	</span>
				    	<button type="button" class="btn btn-sm btn-outline-danger" data-account-id="${ account['id'] }" onclick="removeAccountToPrice_Edit(this)">
				    		<i class="ti-close"></i>
				    	</button>
				    </li>`);

				// hiding and show
				$('#--modal-loading').hide();
				$('#--modal-content').show();
				
			});

			// SET THE ACCOUTNS IN THE SELECT BOX
			set_selectBoxAccounts_Edit(priceDetails['accounts_not_in_any_price']);

		})
		.catch((error) => {
			console.log(error.response);
		});
}

// ***************************************************
function addAccountToPrice_Edit()
{
	let accountAddedList = $('.--added-account-in-price.for-edit-price');

	if( $('[name="no_selected"].for-edit-price').val() == 'true' )
		accountAddedList.empty();

	current_accountsNotInAnypriceOfProduct.forEach(function(account, index){
		//
		if( $('[name="account_id"].for-edit-price').val() == account['id']) {
			account['show'] = false; // dont show
			account['selected'] = true; // selected

			accountAddedList.append(`
				<li class="list-group-item text-primary d-flex justify-content-between" id="--account-edit-list-${ account['id'] }">
			    	<span>
			    		<i class="ti-user"></i> ${ account['account_name'] } - ${ account['type'] }
			    	</span>
			    	<button type="button" class="btn btn-sm btn-outline-danger" data-account-id="${ account['id'] }" onclick="removeAccountToPrice_Edit(this)">
			    		<i class="ti-close"></i>
			    	</button>
			    </li>`);
		}
	});

	// if no selected then, this function will display no selected accounts
	track_ifNoSelected_Edit();
	// set the updated options in select box
	set_selectBoxAccounts_Edit(current_accountsNotInAnypriceOfProduct);
}

// on change in selection of add price
$('.--select-box-accounts.for-edit-price').on('change', addAccountToPrice_Edit);

function track_ifNoSelected_Edit()
{
	let accountAddedList = $('.--added-account-in-price.for-edit-price');
	// if no account added in list
	if( $( '.--added-account-in-price.for-edit-price li').length == 0 ) {			
		$('[name="no_selected"].for-edit-price').val(true);

		accountAddedList.html(`
			<li class="list-group-item text-muted text-center">
		    	No account list in price yet.
		    </li>`);
	}
	else
		$('[name="no_selected"].for-edit-price').val(false);
}

var current_accountsNotInAnypriceOfProduct = [];
// set select box the options
function set_selectBoxAccounts_Edit(accountsNotInAnypriceOfProduct) // TURNING THE ACCOUNTS WHO IS ALREADY IN price TO "FALSE".
{
	current_accountsNotInAnypriceOfProduct = accountsNotInAnypriceOfProduct;
	// ***************************************************

	// ***************************************************
	//						SELECT BOXES
	// ***************************************************
	let selectBox = $('.--select-box-accounts.for-edit-price');
	// refresh always the select box
	$(selectBox).empty();
	// add select box title
	$(selectBox).append(`<option value="" disabled selected>--ADD ACCOUNT--</option>`);
	// appending in select box
	current_accountsNotInAnypriceOfProduct.forEach(function(account, index){
		if(account['show']) {
			$(selectBox).append(`<option value="${ account['id'] }">${ account['account_name'] } - ${ account['type'] }</option>`);
		}
	});
	// ***************************************************
}

// remove account in price edit
function removeAccountToPrice_Edit(object)
{
	// loop through accounts in dynamic
	current_accountsNotInAnypriceOfProduct.forEach(function(account, index) {
		if( object.dataset['accountId'] == account['id']) {
			account['show'] = true; // show in select
			account['selected'] = false; // selected
			// remove the displayed html
			$(`#--account-edit-list-${ object.dataset['accountId'] }`).remove();
		}
	});
	// track if no selected account in price
	track_ifNoSelected_Edit();
	// select boxes in edit
	set_selectBoxAccounts_Edit(current_accountsNotInAnypriceOfProduct);
}

// ******************************************************************************
//						price FORM - UPDATE OF price
// ******************************************************************************
$('#price-edit-form').submit(function(e){

	e.preventDefault();

	var accountsInprice = [];
	current_accountsNotInAnypriceOfProduct.forEach(function(account, index){
		if( account['selected'] )
			accountsInprice.push(account['id']);
	});

	console.log(accountsInprice);

	axios.patch(`/ajax/inventory/price/${ priceId }`, {
		// 'product_id' : $('[name="product_id"].for-edit-price').val(),
		'unit_price' : $('[name="unit_price"].for-edit-price').val(),
		'accountIds' : accountsInprice,
	})
		.then((response) => {

			// setting to default - refreshing
			// refresh -----
			getProductsViaAjax();
			pricesTable.ajax.reload();

			// remove error message
			// $.each( $('.error-message.for-edit-price'), (key, object) => object.remove() );

			console.log(response.data);

			// sweet alert
			successAlert(response);

		})
		.catch((error) => {
			// errorAlert(error);
			console.log(error.response);

			// remove error message
			$.each( $('.error-message.for-edit-price'), (key, object) => object.remove() );

			// show error messages
			$.each(error.response.data.errors, (key, value) => {
				$(`[name="${ key }"].for-edit-price`).after(`<label class="error-message for-edit-price imp">${ value }</label>`);
			});

			console.log(error.response.data.errors);

		});
});
