
	function getLossesViaAjax()
	{
		axios.get('/ajax/inventory/loss')
			.then((response) => {
				console.log(response.data);
				getLosses(response.data);
			});

	}

	(function(){ getLossesViaAjax(); })();

	var Losses = new Array();

	function getLosses(losses){
		Losses = losses;
		Manipulate();
		Pagination();
	}


	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Losses.length; i++){
			if(Losses[i]['show'])//count only if able to show
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

		for(let i = 0; i < Losses.length; i++){
			if(Losses[i]['show']){
				Losses[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Losses[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{
		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Losses.length; i++)
		{
			let textMatched = false;

	        //id, product_img, generic_name&strength, generic_name, brand_name, strength, product_unit, d_unit_price, total, page, show

	        if(Losses[i]['product']['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Losses[i]['product']['generic_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Losses[i]['product']['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Losses[i]['product']['strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Losses[i]['product']['unit_price_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Losses[i]['product']['stock_format'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Losses[i]['batch_no']['batch_no'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Losses[i]['loss_date'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Losses[i]['show'] = true;
			else
				Losses[i]['show'] = false;

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
		'id' : 'asc',
	};

	function SortAll(column_name)
	{
		switch(column_name)
		{
			case 'generic_name&strength':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					Losses.sort(function(a, b){
						if(a['product'][column_name] < b['product'][column_name])
							return -1;
						if(a['product'][column_name] > b['product'][column_name])
							return 1;
						return 0;
					});
				}
				
				else if(sortDirection[column_name] == 'desc')
				{
					sortDirection[column_name] = 'asc';

					Losses.sort(function(a, b){
						if(a['product'][column_name] > b['product'][column_name])
							return -1;
						if(a['product'][column_name] < b['product'][column_name])
							return 1;
						return 0;
					});
				}
			break;

			case 'id':
				if(sortDirection[column_name] == 'asc'){

					sortDirection[column_name] = 'desc';
					
					Losses.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					Losses.sort(function(a, b){
						return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
					});

				}
			break;

			case 'batch_no':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					Losses.sort(function(a, b){
						if(a['batch_no'][column_name] < b['batch_no'][column_name])
							return -1;
						if(a['batch_no'][column_name] > b['batch_no'][column_name])
							return 1;
						return 0;
					});
				}
				
				else if(sortDirection[column_name] == 'desc')
				{
					sortDirection[column_name] = 'asc';

					Losses.sort(function(a, b){
						if(a['batch_no'][column_name] > b['batch_no'][column_name])
							return -1;
						if(a['batch_no'][column_name] < b['batch_no'][column_name])
							return 1;
						return 0;
					});
				}
			break;
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
		$('#loss-tbl-body').empty();

		Losses.forEach(function(loss, index){
			if(loss['show'] && loss['page'] == __pageNumber)
			{
				$('#loss-tbl-body').append(`
					<tr>
	        			<td></td>
	        			<td class="td-name">
	        				<img src="${ loss['product']['product_img'] }" alt="">
	        				<div class="name">
	        					<h2>${ loss['product']['generic_name'] } ${ loss['product']['strength'] }</h2>
	        					<h2 class="text-muted">
	        						${ loss['product']['brand_name'] }
	        					</h2>
	        					<h2>${ loss['product']['unit_price_format'] }</h2>
	        				</div>
	        			</td>
	        			<td>
							<label class="text-primary imp">${ loss['batch_no']['batch_no'] }</label>
							<span> - ${ loss['quantity'] } pcs.</span>
	        			</td>
	        			<td>${ loss['loss_date'] }</td>
	        			<td>
							<label class="text-danger imp">${ loss['reason'] }</label>
	        			</td>
	        			<td></td>
	        		</tr>
				`);
			}
		});

		if(__totalRows() === 0)
		{
			$('#loss-tbl-body').append(`
				<tr><td colspan="6" style="padding:30px;">${ __searchText } not found.</td></tr>
			`);
		}

		$('#--show').html(__totalRows());
		$('#--total').html(Losses.length);
		$('#--total-cost').html('&#8369; '+__totalCostOfProducts());


	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

	function __totalCostOfProducts()
	{
		let totalCost = Losses.reduce(function(total, value, index, array){
			return total += (value['total'] * value['unit_price']);
		}, 0);

		return totalCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}