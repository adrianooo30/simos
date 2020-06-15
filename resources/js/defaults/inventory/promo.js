// ******************************************************************************
//						SELECT BOX - FOR ADD PROMO
// ******************************************************************************

var Products = [];
// get products
function getProductsViaAjax()
{
	axios.get('/ajax/inventory/promo/products')
		.then((response) => {
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
	axios.get('/ajax/inventory/promo/products')
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
	let productSelectBoxes = $('.--select-box-products.for-add-promo');
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
				<option value="${ product['id'] }">${ product['generic_name&strength'] } - ${ product['brand_name'] }</option>
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
$('.--select-box-products.for-add-promo').change(function(){
	getProductsViaAjax_daya();
	//
	let selectBoxValue = parseInt( $(this).val() );

	 copyProducts(); //

	productsDynamic.forEach(function(product, index){
		if( selectBoxValue == product['id'] ) { // will be true only once...
			set_selectBoxAccounts( product['available_accounts'] );

			// set the listing of accounts to empty
			let accountAddedList = $('.--added-account-in-promo.for-add-promo');
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
	let accountSelectBoxes = $('.--select-box-accounts.for-add-promo');
	// refresh the select
	$(accountSelectBoxes).empty();
	// add prompt select product
	$(accountSelectBoxes).append(`
		<option value="" disabled selected>--ADD ACCOUNT--</option>
	`);
	// available accounts in products - for adding of new promo
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

function addAccountToPromo()
{
	let accountAddedList = $('.--added-account-in-promo.for-add-promo');

	if( $('[name="no_selected"].for-add-promo').val() == 'true' )
		accountAddedList.empty();

	currentAccounts.forEach(function(account, index){
		//
		if( $('[name="account_id"].for-add-promo').val() == account['id']) {
			account['show'] = false; // dont show
			account['selected'] = true; // selected turn into true

			accountAddedList.append(`
				<li class="list-group-item text-primary d-flex justify-content-between" id="--account-list-${ account['id'] }">
			    	<span>
			    		<i class="ti-user"></i> ${ account['account_name'] } - ${ account['type'] }
			    	</span>
			    	<button type="button" class="btn btn-sm btn-outline-danger" data-account-id="${ account['id'] }" onclick="removeAccountToPromo(this)">
			    		<i class="ti-close"></i>
			    	</button>
			    </li>
			`);
		}
	});

	track_ifNoSelected();
	set_selectBoxAccounts(currentAccounts); // i think this is recursion
}

// on change in selection of add promo
$('.--select-box-accounts.for-add-promo').on('change', addAccountToPromo);

function removeAccountToPromo(object)
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
	let accountAddedList = $('.--added-account-in-promo.for-add-promo');
	// if no account added in list
	if( $( '.--added-account-in-promo.for-add-promo li').length == 0 ) {			
		$('[name="no_selected"].for-add-promo').val(true);

		accountAddedList.html(`
			<li class="list-group-item text-muted text-center">
		    	No account list in promo yet.
		    </li>
		`);
	}
	else
		$('[name="no_selected"].for-add-promo').val(false);
}

// ******************************************************************************
//						END OF SELECT BOX - FOR ADD PROMO
// ******************************************************************************

// ******************************************************************************
//						PROMO FORM - ADDING OF NEW PROMO
// ******************************************************************************
$('#promo-add-form').submit( function(e) {

	e.preventDefault();

	let accountsInPromo = [];
	currentAccounts.forEach(function(account, index){
		if( account['selected'] )
			accountsInPromo.push(account['id']);
	});

	console.log(accountsInPromo);

	axios.post('/ajax/inventory/promo', {
		'product_id' : $('[name="product_id"].for-add-promo').val(),
		'buy' : $('[name="buy"].for-add-promo').val(),
		'take' : $('[name="take"].for-add-promo').val(),
		'accountIds' : accountsInPromo,
	})
		.then((response) => {

			// setting to default - refreshing
			// refresh -----
			getProductsViaAjax();
			getPromosViaAjax();

			// remove input values
			$('.for-add-promo').val('');
			// set again to default
			$('[name="no_selected"].for-add-promo').val(true);

			// set again to no selected values
			$('.--added-account-in-promo.for-add-promo').html(`
				<li class="list-group-item text-muted text-center">
			    	No account list in promo yet.
			    </li>`);
			// end of setting to default - refreshing

			console.log(response.data);

			// sweet alert
			swal({
				title : response.data['title'],
				text : response.data['text'],
				icon : 'success',
				button : {
					text : 'Okay',
					value : true,
					visible : true,
					className : '',
					closeModal : true,
				},
			});

			// close the modal
			closeModal('add-promo-modal');

		})
		.catch((error) => {
			errorAlert(error);
			console.log(error.response);

			// remove error message
			$.each( $('.error-message.for-add-promo'), (key, object) => object.remove() );

			// show error messages
			$.each(error.response.data.errors, (key, value) => {
				$(`[name="${ key }"].for-add-promo`).after(`<label class="error-message for-add-promo imp">${ value }</label>`);
			});

		});
});

// **************************************************************
//					GET PROMO DETAILS
// **************************************************************

var promoDetails = [];
var promoId = 0;
function getPromoDetails(id)
{
	// hiding and show
	$('#--modal-content').hide();
	$('#--modal-loading').show();

	promoId = id; // promo id

	axios.get(`/ajax/inventory/promo/${ promoId }`)
		.then((response) => {

			getProductsViaAjax(); // call again the products - available products

			console.log(response.data);

			promoDetails = response.data;

			// product image
			$('#--product-img').attr('src', promoDetails['product']['product_img']);

			// name of product
			$('#--generic-name-strength').html(promoDetails['product']['generic_name&strength']);
			$('#--brand-name').html(promoDetails['product']['brand_name']);
			$('#--unit-price').html(promoDetails['product']['unit_price_format']);

			// promo name
			// $('#--promo-name').html(promoDetails['promo_name']);

			$('#--buy').val(promoDetails['buy']);
			$('#--take').val(promoDetails['take']);

			// select boxes for selecting of accounts
			let listOfAccountsInPromo = $('.--added-account-in-promo.for-edit-promo');
			// empty the select box first
			$(listOfAccountsInPromo).empty();

			// listing the accounts in promo
			promoDetails['accounts'].forEach(function(account, index){

				// set the default seelcted promo to true - THIS CODE!!!
				promoDetails['accounts_not_in_any_promo'].forEach(function(accountNotInPromo, index){
					if(account['id'] === accountNotInPromo['id'])
						accountNotInPromo['selected'] = true;
				});

				$(listOfAccountsInPromo).append(`
					<li class="list-group-item text-primary d-flex justify-content-between" id="--account-edit-list-${ account['id'] }">
				    	<span>
				    		<i class="ti-user"></i> ${ account['account_name'] } - ${ account['type'] }
				    	</span>
				    	<button type="button" class="btn btn-sm btn-outline-danger" data-account-id="${ account['id'] }" onclick="removeAccountToPromo_Edit(this)">
				    		<i class="ti-close"></i>
				    	</button>
				    </li>`);

				// hiding and show
				$('#--modal-loading').hide();
				$('#--modal-content').show();
				
			});

			// SET THE ACCOUTNS IN THE SELECT BOX
			set_selectBoxAccounts_Edit(promoDetails['accounts_not_in_any_promo']);

		})
		.catch((error) => {
			console.log(error);
		});
}

// ***************************************************
function addAccountToPromo_Edit()
{
	let accountAddedList = $('.--added-account-in-promo.for-edit-promo');

	if( $('[name="no_selected"].for-edit-promo').val() == 'true' )
		accountAddedList.empty();

	current_accountsNotInAnyPromoOfProduct.forEach(function(account, index){
		//
		if( $('[name="account_id"].for-edit-promo').val() == account['id']) {
			account['show'] = false; // dont show
			account['selected'] = true; // selected

			accountAddedList.append(`
				<li class="list-group-item text-primary d-flex justify-content-between" id="--account-edit-list-${ account['id'] }">
			    	<span>
			    		<i class="ti-user"></i> ${ account['account_name'] } - ${ account['type'] }
			    	</span>
			    	<button type="button" class="btn btn-sm btn-outline-danger" data-account-id="${ account['id'] }" onclick="removeAccountToPromo_Edit(this)">
			    		<i class="ti-close"></i>
			    	</button>
			    </li>`);
		}
	});

	// if no selected then, this function will display no selected accounts
	track_ifNoSelected_Edit();
	// set the updated options in select box
	set_selectBoxAccounts_Edit(current_accountsNotInAnyPromoOfProduct);
}

// on change in selection of add promo
$('.--select-box-accounts.for-edit-promo').on('change', addAccountToPromo_Edit);

function track_ifNoSelected_Edit()
{
	let accountAddedList = $('.--added-account-in-promo.for-edit-promo');
	// if no account added in list
	if( $( '.--added-account-in-promo.for-edit-promo li').length == 0 ) {			
		$('[name="no_selected"].for-edit-promo').val(true);

		accountAddedList.html(`
			<li class="list-group-item text-muted text-center">
		    	No account list in promo yet.
		    </li>`);
	}
	else
		$('[name="no_selected"].for-edit-promo').val(false);
}

var current_accountsNotInAnyPromoOfProduct = [];
// set select box the options
function set_selectBoxAccounts_Edit(accountsNotInAnyPromoOfProduct) // TURNING THE ACCOUNTS WHO IS ALREADY IN PROMO TO "FALSE".
{
	current_accountsNotInAnyPromoOfProduct = accountsNotInAnyPromoOfProduct;
	// ***************************************************

	// ***************************************************
	//						SELECT BOXES
	// ***************************************************
	let selectBox = $('.--select-box-accounts.for-edit-promo');
	// refresh always the select box
	$(selectBox).empty();
	// add select box title
	$(selectBox).append(`<option value="" disabled selected>--ADD ACCOUNT--</option>`);
	// appending in select box
	current_accountsNotInAnyPromoOfProduct.forEach(function(account, index){
		if(account['show']) {
			$(selectBox).append(`<option value="${ account['id'] }">${ account['account_name'] } - ${ account['type'] }</option>`);
		}
	});
	// ***************************************************
}

// remove account in promo edit
function removeAccountToPromo_Edit(object)
{
	// loop through accounts in dynamic
	current_accountsNotInAnyPromoOfProduct.forEach(function(account, index) {
		if( object.dataset['accountId'] == account['id']) {
			account['show'] = true; // show in select
			account['selected'] = false; // selected
			// remove the displayed html
			$(`#--account-edit-list-${ object.dataset['accountId'] }`).remove();
		}
	});
	// track if no selected account in promo
	track_ifNoSelected_Edit();
	// select boxes in edit
	set_selectBoxAccounts_Edit(current_accountsNotInAnyPromoOfProduct);
}

// ******************************************************************************
//						PROMO FORM - UPDATE OF PROMO
// ******************************************************************************
$('#promo-edit-form').submit(function(e){

	e.preventDefault();

	var accountsInPromo = [];
	current_accountsNotInAnyPromoOfProduct.forEach(function(account, index){
		if( account['selected'] )
			accountsInPromo.push(account['id']);
	});

	console.log(accountsInPromo);

	axios.patch(`/ajax/inventory/promo/${ promoId }`, {
		// 'product_id' : $('[name="product_id"].for-edit-promo').val(),
		'buy' : $('[name="buy"].for-edit-promo').val(),
		'take' : $('[name="take"].for-edit-promo').val(),
		'accountIds' : accountsInPromo,
	})
		.then((response) => {

			// setting to default - refreshing
			// refresh -----
			getProductsViaAjax();
			getPromosViaAjax();

			// remove error message
			$.each( $('.error-message.for-edit-promo'), (key, object) => object.remove() );

			console.log(response.data);

			// sweet alert
			swal({
				title : response.data['title'],
				text : response.data['text'],
				icon : 'success',
				button : {
					text : 'Okay',
					value : true,
					visible : true,
					className : '',
					closeModal : true,
				},
			});

			// close the modal
			closeModal('edit-promo-modal');
		})
		.catch((error) => {
			errorAlert(error);
			console.log(error.response);

			// remove error message
			$.each( $('.error-message.for-edit-promo'), (key, object) => object.remove() );

			// show error messages
			$.each(error.response.data.errors, (key, value) => {
				$(`[name="${ key }"].for-edit-promo`).after(`<label class="error-message for-edit-promo imp">${ value }</label>`);
			});

			console.log(error.response.data.errors);

		});
});
