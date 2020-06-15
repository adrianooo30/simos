var currentAccount;
var productsCurrentlyDisplay = [];

var productsInCart = [];

function getAccountList(page = 1)
{
	let content = $('#account-content'),
		loading = $('#account-loading');

	content.hide();
	loading.show();

	axios.get(`/ajax/order/create/account?page=${ page }`,{
		params : {
			'search' : $('[name="search_account"]').val(),
		}
	})
		.then((response) => {
			// 
			if( currentAccount != undefined )
				selectAccount( currentAccount );

			content.show();
			loading.hide();

			console.log( response.data );

			currentlyDisplayedAccounts = response.data['accounts']['data'];

			$('#account-list').html( response.data['accounts_html'] );
		})
		.catch((error) => {
			console.log(error.data);
		});
}

currentlyDisplayedAccounts = currentlyDisplayedAccounts['data'];

// pagination links for accounts
$(document).on('click', '#account-list-pagination .page-item .page-link', function(e){
	e.preventDefault();
	// get account list with the pagition
	getAccountList( $(this).attr('href').split('page=')[1] );
});

// load accounts
// getAccountList();

// search account
$('[name="search_account"]').on('keyup', function(){
	if( $(this).length > 0 )
		getAccountList();
});

// 
var currentAccount_tag;
// current account tag
$('body').on('click', '.--select-account-btn', function(){

	$(this).attr('disabled', 'true');

	currentAccount_tag = $(this);

	selectAccount( $(this).data('account-id') );
	getProductList( $(this).data('account-id') );

});

function selectAccount(account)
{
	productsInCart = [];
	reviewCart();

	let selectedAccount = currentlyDisplayedAccounts.find( (value) => account == value['id'] );

	console.log(selectedAccount);

	setAccountData(selectedAccount);
}

function setAccountData(selectedAccount)
{
	$('#--selected-account').html(selectedAccount['account_name']);
	$('#--selected-account-type').html(selectedAccount['type']);
	$('#--total-cost-of-order').html( getTotalCostOfOrder() );
}

// total cost of accounts order
function getTotalCostOfOrder()
{
	let totalCost = 0;

	totalCost = productsInCart.reduce((total, value) => {
		return total + parseInt( value['display']['total_cost'] );
	}, 0);

	return setPesoFormatted(totalCost);
}

function getProductList(account, page = 1)
{
	// set current account into global access
	currentAccount = account;

	let content = $('#product-content'),
		loading = $('#product-loading');

	content.hide();
	loading.show();

	let param_productsInCart = [];
	$.each( productsInCart, (key, product) => {
		param_productsInCart.push({
			'product_id' : product['product_id'],
			'total_quantity' : product['total_quantity'],
		});
	} );

	axios.post(`/ajax/order/create/product/${ account }?page=${ page }`,{
				'search' : $('[name="search_product"]').val(),
				'products_in_cart' : param_productsInCart,
			})
		.then((response) => {

			// remove disable button
			currentAccount_tag.removeAttr('disabled');

			content.show();
			loading.hide();

			console.log(`%c products in cart`, 'color: green; font-weight : bolder');
			console.log( response.data );

			productsCurrentlyDisplay = response.data['products']['data'];
			$('#product-list').html( response.data['products_html'] );

		})
		.catch((error) => {
			console.log(error.response);
		});
}

// pagination links for product
$(document).on('click', '#product-list-pagination .page-item .page-link', function(e){
	e.preventDefault();
	// get account list with the pagition
	getProductList( currentAccount, $(this).attr('href').split('page=')[1] );

});

// search product
$('[name="search_product"]').on('keyup', function(){
	if( $(this).length > 0 )
		getProductList( currentAccount );
});






// ADD PRODUCT IN CART
$('#product-list').on('submit', '.--add-product-to-cart-form', function(e){

	e.preventDefault();
	// store to data variable
	let current_product_to_store = $(this).serializeArray();

	let productId = current_product_to_store[0].value,
		quantityToOrder = current_product_to_store[1].value;

	if( quantityToOrder.length == 0 )
		return;

	// check if in cart already
	let isInCartAlready = productsInCart.some( (product_already_in_cart) => {
		return product_already_in_cart['product_id'] == productId;
	} );

	// LOGIC IF PRODUCT IS IN CART ALREADY - THEN ADD THE QUANTITY
	if(isInCartAlready) {
		// search product in cart
		productsInCart.forEach( (product_already_in_cart) => {
			if( product_already_in_cart['product_id'] == productId )
				// add quantity of product
				product_already_in_cart['quantity'] += parseInt(quantityToOrder);
				product_already_in_cart['free'] = set_freeToGet(product_already_in_cart['display'], product_already_in_cart['quantity'] );
				product_already_in_cart['total_quantity'] = (product_already_in_cart['quantity'] + product_already_in_cart['free']);

				setTotals(product_already_in_cart['display'], product_already_in_cart['quantity']);
		} );
	}
	// LOGIC FOR INSERTING PRODUCT IN CART
	else{
		// get the deep datas of each product
		single_productData = productsCurrentlyDisplay.find( (product_currently_display) => {
			return product_currently_display['id'] == productId;
		} )

		// set action button
		setActionButton(single_productData);

		setTotals(single_productData, quantityToOrder);

		// insert new product
		productsInCart.push( {
			'product_id' : parseInt(productId),
			'quantity' : parseInt(quantityToOrder),
			'free' : set_freeToGet(single_productData, parseInt(quantityToOrder) ),
			'total_quantity' : parseInt(quantityToOrder) + set_freeToGet(single_productData, parseInt(quantityToOrder) ),
			'display' : single_productData,
		} );
	}

	let product_in_cart = productsInCart.find( product_already_in_cart => product_already_in_cart['product_id'] == productId);

	// almost for displaying of total stock
	let currentStock = parseInt( product_in_cart['display']['stock'] ) - parseInt( product_in_cart['total_quantity'] );

	// set to default
	$(this).find('[name="quantity"]').val('');
	// set again
	$(this).find('[name="quantity"]').attr('max', currentStock);
	// display current stock
	$(this).closest('.--product-card').find('.--total-stock').text( setQuantityFormatted(currentStock) );
	//  end  of almost for displaying of total stock

	// set total cost of order
	$('#--total-cost-of-order').html( getTotalCostOfOrder() );

	reviewCart();

	console.log('%c Products in Cart', 'color: green; font-weight : bolder');
	console.log( productsInCart );

});

function setActionButton(single_productData)
{
	single_productData['action'] = `
		<button type="button" class="btn btn-icon waves-effect btn-danger" onclick="removeFromCart(${ single_productData['id'] })">
            <i class="ti-trash"></i>
        </button>
	`;

	return single_productData;
}

function setTotals(product, quantity)
{
	// set total quantity and total cost
	product['total_quantity'] = `${ __numberWithComma(quantity) } + ${set_freeToGet(product, parseInt(quantity) )} pcs.`;
	product['total_cost'] = parseInt(quantity) * parseInt(product['unit_price']);
	// format
	product['total_cost_format'] = setPesoFormatted( parseInt(quantity) * parseInt(product['unit_price']) );

	console.trace();

	return product;
}

// METHOD FOR CALCULATING 
function set_freeToGet(product, quantity)
{
	if( product['approriate_deal'] != undefined )
		return Math.floor( quantity / product['approriate_deal']['buy'] ) * product['approriate_deal']['take'];
	else
		return 0;

	console.trace()
}

//display product in cart using datatable
var cartTable;
function reviewCart()
{	
	let productsInCart_html = '';

	// LOOP THROUGH THE PRODUCCTS IN CART
	$.each( productsInCart, (key, products) => {

		let productDisplay = products['display'];

		// products in cart html
		productsInCart_html += `
			<div class="col-xl-6  mx-auto --product-in-cart">
				<div class="card card-shadow">
					<div class="card-header d-flex justify-content-between">
						<div class="d-flex">
							<img src="${ productDisplay['product_img'] }" alt="" class="img-thumbnail image-50 mx-2">

							<div class="mx-2">
								<h5 class="text-primary">${ productDisplay['product_name'] }</h5>
								<h6 class="text-muted">${ productDisplay['brand_name'] }</h6>
								<h6 class="text-muted">${ productDisplay['unit_price_format'] }</h6>
							</div>
						</div>

						<button type="button" class="btn btn-icon waves-effect btn-danger" onclick="removeFromCart(${ productDisplay['id'] }, this)">
	                           <i class="ti-trash"></i>
	                    </button>

					</div>
					<div class="card-body d-flex justify-content-center">
						<div class="mx-2 text-center">
							<div class="alert alert-danger">
								<strong>${ productDisplay['total_quantity'] }</strong>
							</div>
							<sup class="font-weight-bolder">Total Quantity</sup>
						</div>
						<div class="mx-2 text-center">
							<div class="alert alert-primary">
								<strong>${ productDisplay['total_cost_format'] }</strong>
							</div>
							<sup class="font-weight-bolder">Total Cost</sup>
						</div>
					</div>
				</div>
			</div>
		`;

	} );

	// display products
	if(productsInCart_html.length > 0)
		$('#--products-in-cart').html( productsInCart_html );
	else {
		$('#--products-in-cart').html(`
			<div class="col-xl-6  mx-auto">
				<div class="card card-shadow">
					<div class="card-header d-flex justify-content-center">
						<h5 class="text-danger text-center">Empty Cart.</h5>
					</div>
				</div>
			</div>
		`);
	}
}

function removeFromCart(productId, object)
{
	swal({
		title: 'Are you sure, you want to remove this product in cart?',
		text : 'Just click the confirm button. If that is true.',
		icon : 'warning',
		buttons : true,
		dangerMode: true,
	})
	.then((confirmation) => {

		if(confirmation) {
			// remove item
			$(object).closest('.--product-in-cart').remove();

			let removedProduct = productsInCart.find( value => value.display['id'] == productId );
			$(`.--product-id-${ productId }-display`).html( setQuantityFormatted(removedProduct['display']['stock_in_order']) );
			$(`.--product-id-${ productId }-value`).attr('max', removedProduct['display']['stock_in_order'] );


			let temp_productsInCart = [];
			productsInCart.forEach( (product) => { 
				if(productId != product.display['id']){
					temp_productsInCart.push(product);
				}
			});
			// 
			productsInCart = temp_productsInCart;
			// if
			trackIfNoProductInCart();

			setTimeout(function(){
				// set the total cost
				$('#--total-cost-of-order').html( getTotalCostOfOrder() );
			}, 400)

		}// END - willChange
	});
}

// 
function trackIfNoProductInCart()
{
	if(productsInCart.length == 0) {
		$('#--products-in-cart').html(`
			<div class="col-xl-6  mx-auto">
				<div class="card card-shadow">
					<div class="card-header d-flex justify-content-center">
						<h5 class="text-danger text-center">Empty Cart.</h5>
					</div>
				</div>
			</div>
		`);
	}
}

// **********************************
//			SEND ORDER
// **********************************
$('#--send-order-form').on('submit', function(e){

	e.preventDefault();

	let orderMedicine = [];
	$.each( productsInCart, (key, product) => {
		console.log(`%c ${product['product_id']}`, 'color: green; font-weight : bolder');

		orderMedicine.push({
			'product_id' : product['product_id'],
			'quantity' : product['quantity'],
			'free' : product['free'],
		});
	} );

	swal({
		title: 'Are you sure, you recorded properly the order of the customer?',
		text : 'If yes, then just click the confirm button.',
		icon : 'warning',
		buttons : true,
		dangerMode: true,
	})
	.then((confirmation) => {
		if(confirmation) {
			// CREATE ORDER
			axios.post('/ajax/order/create', {
				'order_transaction' : {
					'account_id' : currentAccount,
					'order_date' : moment().format('YYYY-MM-DD'),
					'employee_id' : $('[name="employee_id"]').val(),
					'status' : 'Pending'
				},
				'order_medicine' : orderMedicine
			})
			.then((response) => {

				console.log(response.data);

				successAlert(response);

				// window.location.assign('/order/create');
			})
			.catch((error) => {

				errorAlert(error);

				console.log(error.response);

			});
		}// END - willChange
	});

});

// ***********************************************************************
// END of manipulation for creating of order, the functions for creating of order
// ***********************************************************************

var isAddingAccount;
$(document).ready( function() {

	$('#form-add-account').submit(function(e){

			e.preventDefault();

			axios.post('/ajax/users/accounts', new FormData( this ))
			.then((response) => {
				// 
				console.log(response.data);
				// 
				currentlyDisplayedAccounts.push( response.data['account'] );
				//
				selectAccount( response.data['account']['id'] );
				// sweet alert
				successAlert(response);
			})
			.catch((error) => {

				errorAlert(error);
				
				let errors = error.response.data.errors;

				console.log(errors);

				// error message 
				$('.error-message').remove();

				$.each(errors, (key, value) => {
					$(`[name="${ key }"].for-add-account`).addClass('is-invalid');
					$(`[name="${ key }"].for-add-account`).closest('.form-group').append(`
	                    <h6 class="error-message for-add-account text-danger font-12 lighter">
	                        ${ value }
	                    </h6>`);
				})
			});

		});

});