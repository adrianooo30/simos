var headBtns = $('#head-btns');
var sendOrderButton = $('#--send-order-btn');

sendOrderButton.css({'display' : 'none'});

function selectAccount(){

	headBtns.css({'display' : 'flex'});

	$("#account-tab-btn").addClass("active");
	$("#tab-account").css({'display' : 'block'});

	$("#product-tab-btn").removeClass("active");
	$("#tab-product").css({'display' : 'none'});

	$("#review-tab-btn").removeClass("active");
	$("#tab-review").css({'display' : 'none'});

	$('#--search-bar').val('');
	$('#--search-bar').attr('onkeyup', 'SearchAccount(this.value)');
	searchTextAccount = '';
	getAccountsViaAjax();

	sendOrderButton.css({'display' : 'none'});
}

function selectProduct()
{
	let noExisting = $('#no-existing');

	if(selectedAccountID.length == 0)
		noExisting.css({'display' : 'flex'});
	else
		noExisting.css({'display' : 'none'});

	headBtns.css({'display' : 'flex'});

	$("#product-tab-btn").addClass("active");
	$("#tab-product").css({'display' : 'block'});

	$("#account-tab-btn").removeClass("active");
	$("#tab-account").css({'display' : 'none'});

	$("#review-tab-btn").removeClass("active");
	$("#tab-review").css({'display' : 'none'});

	$('#--search-bar').val('');
	$('#--search-bar').attr('onkeyup', 'SearchProduct(this.value)');

	sendOrderButton.css({'display' : 'none'});
}

///////////////////////////MANIPULATION OF CUSTOMERS OR ACCOUNTS/////////////////////////////////////////

	function getAccountsViaAjax()
	{
		axios.get('/ajax/order/create/account')
		.then((response) => {
			getAccounts(response.data);
			console.log(response.data)

			if(isAddingAccount)
			{
				addAccount(accountId);
				selectProduct();
				openNotiBadgeAccount();
			}
			isAddingAccount = false; // return to false
		})
	}

	(function(){ getAccountsViaAjax(); })();

	var Accounts = new Array();
	function getAccounts(accounts){
		Accounts = accounts;
		Manipulate__Account();
		Pagination__Account();
	}

    //id, profile_img, account_name, type, balance, page, show
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows__Account()
	{
		let numberOfRows = 0;
		Accounts.forEach(function(account, index) {
			if(account['show'])//count only if able to show
				numberOfRows++;
		});
		return numberOfRows;
	}

	function __totalPages__Account(){
		return Math.ceil(__totalRows__Account() / 10);
	}

	function __greaterThan10__Account(){
		return __totalRows__Account() > 10;	
	}

	function __putPageNumbers__Account()
	{
		let row = 1, page = 1;
		Accounts.forEach(function(account, index){
			if(account['show']) {
				account['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				account['page'] = 0;
		})
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////

	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAccount(text)
	{
		__searchText = text;
		__pageNumber_Account = 1;

		Accounts.forEach(function(account, index)
		{
			let textMatched = false;
			if(account['account_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(account['type'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(account['balance_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				account['show'] = true;
			else
				account['show'] = false;
		});

		__putPageNumbers__Account();

		Pagination__Account();
		Manipulate__Account();
	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	////////////////ALL ABOUT PAGES//////////////////////
	function Pagination__Account()
	{
		let pagination = '';
		if(__greaterThan10__Account())
		{
			pagination += '<li class="page-item" onclick="__prevPage__Account()"><a href="#" class="page-link">Prev</a></li>';
		
			for(let i = 1; i <= __totalPages__Account(); i++){
				if(i === __pageNumber_Account)
					pagination += '<li onclick="Pages__Account('+i+')" class="active account-page'+i+' page-item"><a href="#" class="page-link">'+i+'</a></li>';
				else
					pagination += '<li onclick="Pages__Account('+i+')" class="account-page'+i+' page-item"><a href="#" class="page-link">'+i+'</a></li>';
			}
		
			pagination += '<li class="page-item" onclick="__nextPage__Account()"><a href="#" class="page-link">Next</a></li>';

		}

		$('#pagination-account ul').html(pagination);
	}

	function Pages__Account(num)
	{
		__pageNumber_Account = num;
		let pageLinks = document.querySelectorAll('#pagination-account ul li');

		for(let i = 0; i < pageLinks.length; i++)
		{
			if(pageLinks[i].classList.contains('active'))
				pageLinks[i].classList.remove('active');
		}

		document.querySelector('.account-page'+num).classList.add('active');//put an active class in the pagenumber.

		Manipulate__Account();
	}

	function __prevPage__Account()
	{
		if(__pageNumber_Account > 1)
			__pageNumber_Account--;
		
		Pages__Account(__pageNumber_Account);
		Manipulate__Account();
	}

	function __nextPage__Account()
	{
		if(__pageNumber_Account < __totalPages__Account())
			__pageNumber_Account++;
		
		Pages__Account(__pageNumber_Account);
		Manipulate__Account();
	}

	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////
	var __pageNumber_Account = 1;
	function Manipulate__Account()
	{
		let accountList = $('#account-list');

		accountList.empty();
		Accounts.forEach(function(account, index)
		{
			if(account['show'] && account['page'] == __pageNumber_Account)
			{
				accountList.append(`
					<div class="d-card">
						<div class="sections">
							<img src="${ account['profile_img'] }"><br>
							<div class="name">
								<h2 class="text-primary">${ account['account_name'] }</h2>
								<h3>${ account['type'] }</h3>
							</div>
						</div>
						
						<div class="sections">
							<h2 class="text-warning">${ account['total_bill_format'] }</h2>
							<h3>Balance</h3>
						</div>
						<button onclick='addAccount(${ account['id'] }),selectProduct(), openNotiBadgeAccount()' class="button pulse">Select Account</button>
					</div>
				`);
			}
		});

		if(__totalRows__Account() === 0){
			accountList.html(`
				'<div class="card" style="display:flex; justify-content:center; padding:30px;">
					"<span style="color:#5cbcf2;">'+__searchText+'</span>" is not found.
				</div>'
			`);
		}

		$('#--show-accounts').html(__totalRows__Account());
		$('#--total-accounts').html(Accounts.length);

	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////MANIPULATION OF PRODUCTS/////////////////////////////////////////

	function getProductViaAjax()
	{
		__pageNumber_Product = 1;

		let loading = $('#--product-loading');
		let existing = $('#--existing');

		loading.css({"display" : "block"});
		existing.css({"display" : "none"});

		axios.get(`/ajax/order/create/product/${ accountId }`)
		.then((response) => {
			loading.css({"display" : "none"});
			existing.css({"display" : "block"});

			console.log(response.data);
			getProducts(response.data);
		});
	}

	var Products = new Array(); // all of table data for product
	function getProducts(products){
		Products = products;
		Manipulate__Product();
		Pagination__Product();
	}
    //id, profile_img, account_name, type, balance, page, show

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows__Product()
	{
		let numberOfRows = 0;
		Products.forEach(function(item, index){
			if(item['show'])
				numberOfRows++;
		});
		return numberOfRows;
	}

	function __totalPages__Product(){
		return Math.ceil(__totalRows__Product() / 10);
	}

	function __greaterThan10__Product(){
		return __totalRows__Product() > 10;	
	}

	function __putPageNumbers__Product()
	{
		let row = 1, page = 1;
		Products.forEach(function(product, index) {
			if(product['show']){
				product['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				product['page'] = 0;
		});
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////

	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;
	function SearchProduct(text)
	{
		__searchText = text;
		__pageNumber_Product = 1;

		Products.forEach(function(product, index){
			let textMatched = false;
            //id, product_img, generic_name&strength, generic_name, brand_name, strength, unit_price, total
			if(product['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(product['generic_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(product['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(product['strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(product['unit_price_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(product['stock_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				product['show'] = true;
			else
				product['show'] = false;
		});

		__putPageNumbers__Product();

		Pagination__Product();
		Manipulate__Product();

	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	////////////////ALL ABOUT PAGES//////////////////////
	function Pagination__Product()
	{
		let pagination = '';
		if(__greaterThan10__Product())
		{
			pagination += '<li class="page-item" onclick="__prevPage__Product()"><a href="#" class="page-link">Prev</a></li>';
		
			for(let i = 1; i <= __totalPages__Product(); i++){
				if(i === __pageNumber_Product)
					pagination += '<li onclick="Pages__Product('+i+')" class="active product-page'+i+' page-item"><a href="#" class="page-link">'+i+'</a></li>';
				else
					pagination += '<li onclick="Pages__Product('+i+')" class="product-page'+i+' page-item"><a href="#" class="page-link">'+i+'</a></li>';
			}
		
			pagination += '<li class="page-item" onclick="__nextPage__Product()"><a href="#" class="page-link">Next</a></li>';
		}

		$('#pagination-product ul').html(pagination);
	}

	function Pages__Product(num)
	{
		__pageNumber_Product = num;
		let pageLinks = document.querySelectorAll('#pagination-product ul li');

		for(let i = 0; i < pageLinks.length; i++)
		{
			if(pageLinks[i].classList.contains('active'))
				pageLinks[i].classList.remove('active');
		}

		$('.product-page'+num).addClass('active');//put an active class in the pagenumber.

		Manipulate__Product();
	}

	function __prevPage__Product()
	{
		if(__pageNumber_Product > 1)
			__pageNumber_Product--;
		
		Pages__Product(__pageNumber_Product);
		Manipulate__Product();
	}

	function __nextPage__Product()
	{
		if(__pageNumber_Product < __totalPages__Product())
			__pageNumber_Product++;
		
		Pages__Product(__pageNumber_Product);
		Manipulate__Product();
	}

	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////
	var __pageNumber_Product = 1;
	function Manipulate__Product()
	{
		$('#product-list').empty();

		Products.forEach(function(product, index) {
			//
			if(product['show'] && product['page'] == __pageNumber_Product) {
               let tagInput = '';

               if(product['stock'] > 0) {
					tagInput += `
						<div class="td-qty">
							<button onclick="decrease(this.id)" id="${ product['id'] }"><i class="ti-minus"></i></button>

							<input type="number" name="" value="1" class="input-med-qty${ product['id'] } for-add-order" min="0" max="${ product['stock'] }" onkeyup="setValue(${ product['id'] }, this.value)">
							<button onclick="increase(this.id)" id="${ product['id'] }"><i class="ti-plus"></i></button>
						</div>

						<div>
							<button id="get-qty${ product['id'] }" onclick="addToCart(${ product['id'] }, this.value), decreaseMax(${ product['id'] },this.value)" value="1" type="submit" class="button pulse">
								<i class="ti-shopping-cart"></i> Add to Cart </button>
						</div>
					`;
				}
				else {
					tagInput += `
						<div style="flex:1;">
							<p style="text-align:center;" class="text-danger"><i class="ti-shopping-cart"></i>No Stock Available</p>
						</div>
					`;
				}

				// promo details
				let promoDetails = '';
				if( product['promo'] !== null )
					promoDetails = product['promo']['promo_name']; 

				// product card
				$('#product-list').append(`
					<div class="d-card">
						<div class="sections mt-3">
						<img src="${ product['product_img'] }">
							<div class="name">

								<h2 class="text-primary">${ product['generic_name&strength'] }</h2>
								<h2 class="text-muted">${ product['brand_name'] }</h2>
								<h2 class="text-primary">${ product['unit_price_format'] }</h2>
								
								<h6 class="text-warning text-center bolder m-0">${ promoDetails }</h6>	

								<div class="mt-2 mb-2">
									<h2 class="text-primary" id="--total-stock${ product['id'] }">${ product['stock_format'] }</h2>
									<sup class="bold text-muted">Total Stock</sup>
								</div>
							</div>
						</div>
						<div class="sections">
							${ tagInput }
						</div>
					</div> `);
			}

		});
		
		if(__totalRows__Product() === 0) {
			$('#product-list').html(`
				<div class="card text-center">
					<span class="text-primary">${ __searchText }</span> is not found.
				</div>`);
		}

		$('#--show-products').html(__totalRows__Product());
		$('#--total-products').html(Products.length);
	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

////////////////////////////////////////////////////////////////////////////////////////////////////

var reviewProductinCart = $('#--cart-product-list');

function selectReview(){

	checkValidation(); //for validations!

	headBtns.css({'display' : 'none'});

	// *********************************************

	$("#review-tab-btn").addClass("active");
	$("#tab-review").css({'display' : 'block'});

	$("#account-tab-btn").removeClass("active");
	$("#tab-account").css({'display' : 'none'});

	$("#product-tab-btn").removeClass("active");
	$("#tab-product").css({'display' : 'none'});

	// *********************************************

	$('#--profile-img').attr('src', selectedImage);
	
	$('#--account-name').html(selectedAccount);
	$('#--type').html(selectedAccountType);
	$('#--total-cost-order').html("&#8369; "+__numberWithComma(__getTotalCostOfOrder().toFixed(2)));

	let currentDate = new Date();
	$('#--date-of-order').html(currentDate.toDateString());
	PSRAssignedID = $('#--psr-assigned').val();

	// display in cart products
	reviewProductinCart.empty();
	Cart.forEach( function(product, index){
		reviewProductinCart.append(`
			<div class="d-card product-card d-flex flex-sm-column flex-md-column flex-lg-row flex-xl-row justify-content-around" id="product-${ product['id'] }">

				<div class="sections d-flex flex-row align-items-center justify-content-center">
					<img src="${ product['product_img'] }" class="mr-4 ml-4">
					
					<div class="name">
						<h2 class="text-primary">${ product['generic_name'] }</h2>
						<h3 class="">${ product['brand_name'] }</h3>
						<h2 class="text-primary">${ product['unit_price_format'] }</h2>
					</div>
				</div>

				<div class="sections d-flex flex-column justify-content-between">
					<h2 class="text-primary">${ setQuantityFormatted(product['quantity']) } + <span class="text-danger">${ setQuantityFormatted(product['free']) }</span></h2>
					<h3 class="mb-4 ">Total Quantity</h3>
					<h2 class="text-primary">${ setPesoFormatted(product['total_cost']) }</h2>
					<h3 class="">Total Cost</h3>
				</div>
				
				<i class="ti-trash fa-lg text-danger" onclick="deleteOrderProduct(${ product['id'] })"></i>
			</div>
		`);
	});

	// if no products in cart
	if(Cart.length === 0) {
		reviewProductinCart.html(`
			<div class="card product-card">
				<h2 class="mx-auto py-4">There are no selected order yet.</h2>
			</div>`);
	}

	sendOrderButton.css({'display' : 'flex'});

}

// ***********************************************************************
// ***********************************************************************
//
// START manipulation for creating of order, the functions for creating of order
//
// ***********************************************************************
// ***********************************************************************

// for inputted quantity must not greater than max quantity

$.each( $('[type="number"].for-add-order'), (index, object) => {

	object.addEventListener('keyup', function(){

		console.log( object );
	
		if( parseInt( $(this).val() ) > parseInt( $(this).attr('max') ) )
			$(this).val( $(this).attr('max') );

		else if( parseInt( $(this).val() ) < 0)
			$(this).val( 0 );

		// other validations

		if( isNaN( $(this).val() ) )
			$(this).val('');

	});

} );

var date = new Date();
var year = date.getFullYear(),
	month = date.getMonth() + 1,
	date = date.getDate();

var totalCostofOrder = 0;
var selectedAccountID = '',
	selectedAccount = "No Selected Account",
	selectedAccountType = "No Selected Account",
	selectedImage = "/images/users/user.png",
	dateOfOrder = year+"-"+month+"-"+date,
	PSRAssignedID = "";

var Cart = new Array();

function addToCart(id, quantity){

	var productInCart; // get product

	// getting the product details
	Products.forEach((product) => {
		if(id == product['id']) {
			productInCart = product;
		}
	});

	let newProductInCart  = true;

	// TRACK IF IN CART ALREADY
	Cart.forEach( function( product ) {
		if( (productInCart['generic_name&strength'] === product['generic_name&strength']) && (productInCart['brand_name'] === product['brand_name']) ){
			newProductInCart  = false;
			
			let updatedQuantity = parseInt(product['quantity']) + parseInt(quantity);

			product['quantity'] = updatedQuantity; // updated quantity
			product['total_cost'] = parseInt(productInCart['unit_price']) * parseInt(updatedQuantity); // update total cost
		}
	});
	// if a new product
	if( newProductInCart ) {
		// set total cost
		productInCart['quantity'] = quantity;
		productInCart['total_cost'] = productInCart['unit_price'] * quantity;
		// push in cart
		Cart.push( productInCart );
	}

	// SET FREE TO GET
	set_freeToGet(productInCart, productInCart['quantity']);

	openNotiBadgeProduct(productInCart['generic_name&strength'], productInCart['brand_name'], setPesoFormatted(productInCart['total_cost']) );
}

// METHOD FOR CALCULATING 
function set_freeToGet(product, quantity)
{
	if( product.promo !== null )
		product['free'] = Math.floor( quantity / product.promo['buy'] ) * product.promo['take'];
	else
		product['free'] = 0;

	return product;
}


// this method is already called when one product is added to cart
function decreaseMax(id, qtyLessen){
	let inputMedQty = $('.input-med-qty'+id);
	let maxQty = inputMedQty.attr('max');
	
	inputMedQty.attr('max', maxQty - qtyLessen); // put the updated max value

	if(inputMedQty.attr('max') > 0){
		inputMedQty.val(1);
		$("#get-qty"+id).val(1);
	}
	else{
		inputMedQty.val(0);
		$("#get-qty"+id).val(0);
	}

	$('#--total-stock'+id).text(
		__numberWithComma($('.input-med-qty'+id).attr('max'))
	); // settting of max quantity for total stock
}

var accountId;
function addAccount(id){

	accountId = id;

	if(id != selectedAccountID){
		Cart = [];
		totalCostofOrder = 0;
	}

    //id, account_name, type, profile_img
    for(let i = 0; i < Accounts.length; i++)
    {
    	if(id == Accounts[i]['id']) {
    		selectedAccountID = Accounts[i]['id'];
			selectedAccount = Accounts[i]['account_name'];
			selectedAccountType = Accounts[i]['type'];
			selectedImage = Accounts[i]['profile_img'];
    	}
    }

	getProductViaAjax();
}

function deleteOrderProduct(id){
	let indexToDelete;

	$("#product-"+id).remove();

	 // product id is not same on its index
	Cart.forEach(function(product, index, array){
		if(id == product['id'])
			indexToDelete = index;
	});

	openNotiBadgeDeleted(Cart[indexToDelete]['generic'], Cart[indexToDelete]['brand'], Cart[indexToDelete]['quantity']); // notify the user
	
	// update the max quantity, since it deleted 1 product
	$('.input-med-qty'+id).attr('max', Number($('.input-med-qty'+id).attr('max')) + Number(Cart[indexToDelete]['quantity']));

	Cart[indexToDelete] = undefined;// set the delete product into undefined

	let temporaryCart = new Array();
	Cart.forEach(function(product, index, array){
		if(product !== undefined) {
			temporaryCart.push(product);
		}
	});
	Cart = temporaryCart; // return back the value of temporary cart into the original cart

	 // display the updated total cost
	$('#--total-cost-order').html("&#8369; "+ ( __getTotalCostOfOrder() ));
	 // settting of max quantity for total stock
	$('#--total-stock'+id).text( __numberWithComma($('.input-med-qty'+id).attr('max')) );

	if(Cart.length == 0)
		reviewProductinCart.html('<div class="card product-card"><h2 style="margin:20px auto;">There are no selected order yet.</h2></div>');

}

function __getTotalCostOfOrder()
{
	total = 0;
	for(let i = 0; i < Cart.length; i++)
		total += Number(Cart[i]['total_cost']);
	return total;
}

var notifyBox = $("#notify");
function __notifyBoxMessage_toast(h2 ,h4, h5, icon)
{
	notifyBox.css({'display' : 'none'})
			 .removeClass('out')
			 .removeClass('active');

	notifyBox.addClass('active')
			 .css({ 'display' : 'flex'})
			 .html(`
				<div class="opacityBackground"></div>
					<i class="${ icon }"></i>
					<div class="name">
						<h2>${ h2 }</h2>
						<h4>${ h4 }</h4>
						<h5>${ h5 }</h5>
				</div>
			 `);
}

function openNotiBadgeAccount()
{
	notifyBox.removeClass('delete');
	__notifyBoxMessage_toast(selectedAccount, selectedAccountType ,'Selected', 'ti-user');
}

function openNotiBadgeDeleted(firstParam, secondParam, thirdParam)
{
	notifyBox.addClass('delete');
	__notifyBoxMessage_toast(firstParam, secondParam ,thirdParam, 'ti-trash');
}

function openNotiBadgeProduct(firstParam, secondParam, thirdParam)
{
	notifyBox.removeClass('delete');
	__notifyBoxMessage_toast(firstParam, secondParam ,thirdParam, 'fas fa-syringe');
}

function closeNotiBadge()
{
	notifyBox.css({ 'display' : 'flex' });
	notifyBox.removeClass('active');
	notifyBox.addClass('out');

	setTimeout(function(){
		notifyBox.css({ 'display' : 'none' });
	}, 400);
}

function setValue(id, value)
{
	let maxQty = $(".input-med-qty"+id).attr('max');

	if(Number(maxQty) >= Number(value))
		$("#get-qty"+id).val(value);
}

function decrease(id)
{
	let quantity = $(".input-med-qty"+id);
	let quantityValue = quantity.val();

	if(quantityValue > 0){
		quantity.val(quantityValue - 1);
		$("#get-qty"+id).val(quantityValue - 1);
	}
}

function increase(id)
{
	let quantity = $(".input-med-qty"+id);

	if( quantity.attr('max') > Number(quantity.val()))
	{
		let qtyInc = Number(quantity.val())+1;
		quantity.val(qtyInc);
		$("#get-qty"+id).val(qtyInc);
	}
}

var error_number = 0;
var error = document.querySelector('#errors');
var errAccount = document.querySelector('#err-account'),
	errPSR = document.querySelector('#err-psr'),
	errProduct = document.querySelector('#err-product');

function checkValidation(){
	if(selectedAccountID.length === 0) {
		errAccount.style.display = "block";
		error_number++;
	}
	else
		errAccount.style.display = "none";

	if(Cart.length == 0) {
		errProduct.style.display = "block";
		error_number++;
	}
	else
		errProduct.style.display = "none";

	if(error_number > 0)
		error.style.display = "block";
	else
		error.style.display = "none";
	error_number = 0;
}

function sendOrder(){

	selectReview();

	if(selectedAccountID !== '' && Cart.length > 0)
	{
		$('#all-content').css({'display' : 'none'});
		$('#head-btns').css({'display' : 'none'});
		$('#m-nav-bar').css({'display' : 'none'});
		
		$('#--create-order-loading').css({'display' : 'block'});

		let orderMedicine = new Array();
		Cart.forEach( function(product) {
			orderMedicine.push({
				'product_id' : product['id'],
				'free' : product['free'],
				'quantity' : product['quantity'],
			});
		});

		// CREATE ORDER
		axios.post('/ajax/order/create', {
			'order_transaction' : {
				'account_id' : selectedAccountID,
				'order_date' : dateOfOrder,
				'employee_id' : PSRAssignedID,
				'status' : 'Pending'
			},
			'order_medicine' : orderMedicine
		})
		.then((response) => {
			console.log(response.data);
			window.location.assign('/order/create');
		})
		.catch((error) => {
			console.log(error.response);
		});
	}

	else {
		checkValidation();
	}
}
// ***********************************************************************
// END of manipulation for creating of order, the functions for creating of order
// ***********************************************************************

var isAddingAccount;
$(document).ready( function() {

	$('#form-add-account').submit(function(e){

		e.preventDefault();

		var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add');
		var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-add');

		axios.post('/ajax/users/accounts', {
			'_token' : $('input[name="_token"]').val(),
			'profile_img' : $('input[name="profile_img"].for-add').val(),
			'account_name' : $('input[name="account_name"].for-add').val(),
			'type' : $('select[name="type"].for-add').val(),
			'address' : $('input[name="address"].for-add').val(),
			'contact_no' : $('input[name="contact_no"].for-add').val(),
			'contact_person' : $('input[name="contact_person"].for-add').val(),
		})
		.then((response) => {
			console.log(response.data);

			allErrorMessagesDOM.forEach((element) => element.remove() );
			allInputFieldsDOM.forEach((element) => element.value = '');

			accountId = response.data.account.id;

			isAddingAccount = true;
			closeModal('add-account-modal');
			getAccountsViaAjax();

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
		})
		.catch((error) => {

			errorAlert(error);

			console.log(error.response);

			let errors = error.response.data.errors;

			allErrorMessagesDOM.forEach((element) => element.remove());

			$.each(errors, (key, value) => {
				$('[name="'+key+'"].for-add').after(`<label class="error-message for-add">${ value }</label>`);
			});
		});

	});

});