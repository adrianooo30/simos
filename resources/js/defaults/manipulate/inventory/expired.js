
	function getExpiredViaAjax()
	{
		axios.get('/ajax/inventory/expired')
			.then((response) => {
				console.log(response.data);
				getExpired(response.data);
			});
	}

	(function(){ getExpiredViaAjax(); })();

	var Expired = new Array();

	function getExpired(expired){
		Expired = expired;
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Expired.length; i++){
			if(Expired[i]['show'])//count only if able to show
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

		for(let i = 0; i < Expired.length; i++){
			if(Expired[i]['show']){
				Expired[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Expired[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{
		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Expired.length; i++)
		{
			let textMatched = false;

	        if(Expired[i]['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Expired[i]['generic_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Expired[i]['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Expired[i]['strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Expired[i]['unit_price_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Expired[i]['quantity'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Expired[i]['batch_no'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Expired[i]['exp_date'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Expired[i]['show'] = true;
			else
				Expired[i]['show'] = false;
		}

		__putPageNumbers();

		Pagination();
		Manipulate();
	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	////////////////ALL ABOUT SORTING/////////////////////////
	var sortDirection = {
		'generic_name&strength' : 'asc',
		'batch_no' : 'asc',
		'quantity' : 'asc',
	};

	function SortAll(column_name)
	{
		if(column_name == 'quantity'){
			if(sortDirection[column_name] == 'asc'){

				sortDirection[column_name] = 'desc';
				
				Expired.sort(function(a, b){
					return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
				});

				__putPageNumbers();

			}
			else if(sortDirection[column_name] == 'desc'){
				
				sortDirection[column_name] = 'asc';

				Expired.sort(function(a, b){
					return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
				});

				__putPageNumbers();

			}
		}
		else
		{

			if(sortDirection[column_name] == 'asc'){

				sortDirection[column_name] = 'desc';
				
				Expired.sort(function(a, b){
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

				Expired.sort(function(a, b){
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
		$('#expired-tbl-body').empty();

		Expired.forEach(function(expired, index){
			if(expired['show'] && expired['page'] == __pageNumber)
			{
				$('#expired-tbl-body').append(`
					<tr>
	        			<td></td>
	        			<td class="td-name">
	        				<img src="${ expired['product']['product_img'] }" alt="">
	        				<div class="name">
	        					<h2>${ expired['product']['generic_name'] } ${ expired['product']['strength'] }</h2>
	        					<h2 class="text-muted">
	        						${ expired['product']['brand_name'] }
	        					</h2>
	        					<h2>${ expired['product']['unit_price_format'] }</h2>
	        				</div>
	        			</td>
	        			<td>
							<label class="text-primary imp">${ expired['batch_no'] }</label>
	        			</td>
	        			<td>${ expired['quantity'] } pcs.</td>
	        			<td>
							<label class="text-danger imp">${ expired['exp_date'] }</label>
	        			</td>
	        			<td></td>
	        		</tr>
				`);
			}
		});

		if(__totalRows() === 0)
		{
			$('#expired-tbl-body').append(`
				<tr><td colspan="6" style="padding:30px;">${ __searchText } not found.</td></tr>
			`);
		}

		$('#--show').html(__totalRows());
		$('#--total').html(Expired.length);
		$('#--total-cost').html('&#8369; '+__totalCostOfProducts());


	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

	function __totalCostOfProducts()
	{
		let totalCost = Expired.reduce(function(total, value, index, array){
			return total += (value['total'] * value['unit_price']);
		}, 0);

		return totalCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}