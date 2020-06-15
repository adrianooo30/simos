
	function getProductsViaAjax()
	{
		axios.get('/ajax/inventory/products')
			.then((response) => {
				console.log(response.data);

					getProducts(response.data);
				
				// if(response.data.length > 0) {
				// 	getProducts(response.data);

				// 	$('#--has-existing').show();
				// 	$('#--no-existing').hide();
				// }
				// else {
				// 	$('#--no-existing').show();
				// 	$('#--has-existing').hide();
				// }
				
			})
			.catch((error) => {

				console.log(error.response);

			});
	}

	(function(){ getProductsViaAjax(); })();

	var Products = new Array();

	function getProducts(products){
		Products = products;
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Products.length; i++){
			if(Products[i]['show'])//count only if able to show
				numberOfRows++;
		}
		return numberOfRows;
	}

	function __totalPages(){
		return Math.ceil(__totalRows() / 10);
	}

	function __greaterThan10(){
		return __totalRows() > 10;	
	}


	function __putPageNumbers()
	{
		let row = 1, page = 1;

		for(let i = 0; i < Products.length; i++){
			if(Products[i]['show']){
				Products[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Products[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{
		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Products.length; i++)
		{
			let textMatched = false;

	        //id, product_img, generic_name&strength, generic_name, brand_name, strength, product_unit, d_unit_price, total, page, show

	        if(Products[i]['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Products[i]['generic_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Products[i]['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Products[i]['strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Products[i]['product_unit'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Products[i]['unit_price_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Products[i]['stock_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Products[i]['show'] = true;
			else
				Products[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();
	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	////////////////ALL ABOUT SORTING/////////////////////////
	var sortDirection = {
		'generic_name&strength' : 'asc',
		'product_unit' : 'asc',
		'stock' : 'asc',
		'unit_price' : 'asc',
	};

	function SortAll(column_name)
	{
		if(column_name == 'stock' || column_name == 'unit_price'){
			if(sortDirection[column_name] == 'asc'){

				sortDirection[column_name] = 'desc';
				
				Products.sort(function(a, b){
					return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
				});

				__putPageNumbers();

			}
			else if(sortDirection[column_name] == 'desc'){
				
				sortDirection[column_name] = 'asc';

				Products.sort(function(a, b){
					return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
				});

				__putPageNumbers();

			}
		}
		else
		{

			if(sortDirection[column_name] == 'asc'){

				sortDirection[column_name] = 'desc';
				
				Products.sort(function(a, b){
					if(a[column_name] < b[column_name])
						return -1;
					if(a[column_name] > b[column_name])
						return 1;
					return 0;
				});

				__putPageNumbers();

			}
			else if(sortDirection[column_name] == 'desc'){
				
				sortDirection[column_name] = 'asc';

				Products.sort(function(a, b){
					if(a[column_name] > b[column_name])
						return -1;
					if(a[column_name] < b[column_name])
						return 1;
					return 0;
				});

				__putPageNumbers();

			}
		}

		Manipulate();
	}
	////////////////ALL ABOUT SORTING/////////////////////////

	function removeComma(x)
	{
		let number = x.split('');
		let value = '';

		for(let ii = 0; ii < number.length; ii++){
			if(number[ii] != ',')
				value += number[ii];
		}

		return Number(value);
	}

	function __numberWithComma(x)
	{
		x = x.toString();

		var pattern = /(-?\d+)(\d{3})/;

		while(pattern.test(x))
			x = x.replace(pattern, "$1,$2");
		return x;
	}

	////////////////ALL ABOUT PAGES//////////////////////
	function Pagination()
	{

		let paginationTag = document.querySelector('#pagination ul');
		let pagination = '';

		if(__greaterThan10()){
			
			// pagination += '<li class="page-item" onclick="__prevPage()"><a href="#" class="page-link"><i class="ti-angle-left"></i></a></li>';
			pagination += '<li class="page-item" onclick="__prevPage()"><a href="#" class="page-link">Prev</a></li>';
		
			for(let i = 1; i <= __totalPages(); i++){
				if(i === __pageNumber)
					pagination += '<li onclick="Pages('+i+')" class="active page'+i+' page-item"><a href="#" class="page-link">'+i+'</a></li>';
				else
					pagination += '<li onclick="Pages('+i+')" class="page'+i+' page-item"><a href="#" class="page-link">'+i+'</a></li>';
			}
		
			pagination += '<li class="page-item" onclick="__nextPage()"><a href="#" class="page-link">Next</a></li>';

		}

		paginationTag.innerHTML = pagination;
	}

	function Pages(num)
	{
		__pageNumber = num;
		let pageLinks = document.querySelectorAll('#pagination ul li');

		for(let i = 0; i < pageLinks.length; i++)
		{
			if(pageLinks[i].classList.contains('active'))
				pageLinks[i].classList.remove('active');
		}

		document.querySelector('.page'+num).classList.add('active');//put an active class in the pagenumber.

		Manipulate();
	}

	function __prevPage()
	{
		if(__pageNumber > 1)
			__pageNumber--;
		
		Pages(__pageNumber);
		Manipulate();
	}

	function __nextPage()
	{
		if(__pageNumber < __totalPages())
			__pageNumber++;
		
		Pages(__pageNumber);
		Manipulate();
	}


	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////
	var __pageNumber = 1;

	function Manipulate()
	{

		// empty every time this method is called
		$('#products-tbl-body').empty();

		Products.forEach(function(product, index){

			if(product['show'] && product['page'] == __pageNumber)
			{
				$('#products-tbl-body').append(`
					<tr>
						<td></td>
						<td class="td-name">
							<img src="${ product['product_img'] }" alt="">
							<div class="name">
								<h2>${ product['generic_name&strength'] }</h2>
								<h2 class="text-muted">${ product['brand_name'] }</h2>
							</div>
						</td>
						<td>${ product['product_unit'] }</td>
						<td>${ product['stock_format'] } pcs.</td>
						<td>${ product['unit_price_format'] }</td>
						<td class="more-alt td-btn" title="Show more details">
							<i class="ti-eye" onclick="openModal('med-modal'), productInfo('${ product['id'] }')"></i>
						</td>
					</tr>
				`);
			}
		});

		if(__totalRows() === 0)
		{
			$('#products-tbl-body').append(`
				<tr><td colspan="6" style="padding:30px;">"${ __searchText }" not found.</td></tr>
			`);
		}

		$('#--show-products').html(__totalRows());
		$('#--total-products').html(Products.length);
		$('#--total-cost-products').html('&#8369; '+__totalCostOfProducts());

	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

	function __totalCostOfProducts()
	{
		let totalCost = Products.reduce(function(total, value, index, array){
			return total += value['total_cost'];
		}, 0);

		return totalCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}