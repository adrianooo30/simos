
	function getReturneesViaAjax()
	{
		axios.get('/ajax/inventory/returnee')
		.then((response) => {
			console.log(response.data);
			getReturnees(response.data);
		});
	}

	(function(){ getReturneesViaAjax(); })();

	var Returnees = new Array();

	function getReturnees(returnees){			
		Returnees = returnees;
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Returnees.length; i++){
			if(Returnees[i]['show'])//count only if able to show
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

		for(let i = 0; i < Returnees.length; i++){
			if(Returnees[i]['show']){
				Returnees[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Returnees[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{
		__searchText = text;
		__pageNumber = 1;

		Returnees.forEach(function(returnee, index, array){

			let textMatched = false;

			if(returnee['product']['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(returnee['product']['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(returnee['batch_no']['batch_no'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(returnee['loss_date'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				returnee['show'] = true;
			else
				returnee['show'] = false;

		});

		__putPageNumbers();

		Pagination();
		Manipulate();

	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	var sortDirection = {
		'generic_name&strength' : 'asc',
		'batch_no' : 'asc',
		'id' : 'asc',
	};

	////////////////ALL ABOUT SORTING/////////////////////////
	function SortAll(column_name)
	{
		
		switch(column_name)
		{
			case 'generic_name&strength':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					Returnees.sort(function(a, b){
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

					Returnees.sort(function(a, b){
						if(a['product'][column_name] > b['product'][column_name])
							return -1;
						if(a['product'][column_name] < b['product'][column_name])
							return 1;
						return 0;
					});
				}
			break;

			case 'batch_no':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					Returnees.sort(function(a, b){
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

					Returnees.sort(function(a, b){
						if(a['batch_no'][column_name] > b['batch_no'][column_name])
							return -1;
						if(a['batch_no'][column_name] < b['batch_no'][column_name])
							return 1;
						return 0;
					});
				}
			break;

			case 'id':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					Returnees.sort(function(a, b){
						if(a[column_name] - b[column_name])
							return -1;
						if(a[column_name] - b[column_name])
							return 1;
						return 0;
					});
				}
				
				else if(sortDirection[column_name] == 'desc')
				{
					sortDirection[column_name] = 'asc';

					Returnees.sort(function(a, b){
						if(a[column_name] - b[column_name])
							return -1;
						if(a[column_name] - b[column_name])
							return 1;
						return 0;
					});
				}
			break;
		}
				
		__putPageNumbers();

		Manipulate();
	}

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

	////////////////ALL ABOUT SORTING/////////////////////////

	////////////////ALL ABOUT PAGES//////////////////////
	function Pagination()
	{

		let paginationTag = document.querySelector('#pagination ul');
		let pagination = '';

		if(__greaterThan10()){
			
			pagination += '<li class="icon-btn" onclick="__prevPage()"><i class="ti-angle-left"></i></li>';
		
			for(let i = 1; i <= __totalPages(); i++){
				if(i === 1)
					pagination += '<li onclick="Pages('+i+')" class="active page'+i+'">'+i+'</li>';
				else
					pagination += '<li onclick="Pages('+i+')" class="page'+i+'">'+i+'</li>';
			}
		
			pagination += '<li class="icon-btn" onclick="__nextPage()"><i class="ti-angle-right"></i></li>';

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

		$('#returnee-tbl-body').empty(); // refresh the table content

		Returnees.forEach(function(returnee, index, value){

			if(returnee['show'] && returnee['page'] == __pageNumber)
			{
	            $('#returnee-tbl-body').append(`
	            	<tr>
            			<td></td>
            			<td class="td-name">
            				<img src="${ returnee['product']['product_img'] }" alt="">
            				<div class="name">
            					<h2>${ returnee['product']['generic_name'] } ${ returnee['product']['strength'] }</h2>
            					<h2 class="text-muted">${ returnee['product']['brand_name'] }</h2>
            				</div>
            			</td>
            			<td class="text-danger">
            				${ returnee['batch_no']['batch_no'] } - ${ returnee['quantity'] } pcs.
            			</td>
            			<td class="text-danger">
            				${ returnee['returnee_date'] }
            			</td>
            			<td> ${ returnee['reason'] }</td>
            			<td class="more-alt td-btn" title="Show more details">
            				<i class="ti-more-alt" onclick="openModal('returnee-modal'), showDetails(${ returnee['id'] })"></i>
            			</td>
            		</tr>`);
        	}

		});

		if(__totalRows() === 0)
			$('#returnee-tbl-body').append(`<tr><td colspan="6" style="padding:30px;">"`+__searchText+`" not found.</td></tr>`);		

		$('#--show-returnees').html(__totalRows());
		$('#--total-returnees').html(Returnees.length);
		$('#--total-cost-returnees').html('&#8369; '+__totalCostOfReturnees());

	}

	function __totalCostOfReturnees()
	{
		let totalCost = 0;
		Returnees.forEach(function(returnees, index , array){
			totalCost += returnees['total_cost'];
		});
		return (totalCost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}