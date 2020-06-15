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
			// else if(account['balance_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
			// 	textMatched = true;

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
					"<span style="color:#5cbcf2;">${ __searchText }</span>" is not found.
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

	// boolean
	var isNoProduct;

	var Products = new Array(); // all of table data for product
	function getProducts(products){
		Products = products;

		if(Products.length > 0)
			isNoProduct = false;
		else
			isNoProduct = true;

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
		
		if(isNoProduct)
		{
			$('#product-list').html(`
				<div class="card text-center py-4">
					<span class="text-primary">There are no products set already for you. :(
				</div>`);
		}
		else if(__totalRows__Product() === 0) {
			$('#product-list').html(`
				<div class="card text-center">
					<span class="text-primary">${ __searchText }</span> is not found.
				</div>`);
		}

		$('#--show-products').html(__totalRows__Product());
		$('#--total-products').html(Products.length);
	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////