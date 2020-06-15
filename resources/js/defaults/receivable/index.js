
	function getAccountsReceivableViaAjax()
	{
		axios.get('/ajax/transactions/receivables')
			.then((response) => {
				console.log(response.data);
				getReceivables(response.data);
			});
	}

	(function(){ getAccountsReceivableViaAjax(); })();

	var Receivables = new Array();

	function getReceivables(receivables){
		Receivables = receivables;
		console.log(Receivables);
		Manipulate();
		Pagination();
	}


	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Receivables.length; i++){
			if(Receivables[i]['show'])//count only if able to show
				numberOfRows++;
		}
		return numberOfRows;
	}

	function __totalPages(){
		return Math.ceil(__totalRows() / rowsToShow);
	}

	function __greaterThan10(){
		return __totalRows() > rowsToShow; // showing numbers rows
	}


	var rowsToShow = 10; // rows to show, 10, 25, 50, 100
	function __putPageNumbers()
	{
		let row = 1, page = 1;

		for(let i = 0; i < Receivables.length; i++){
			if(Receivables[i]['show']){
				Receivables[i]['page'] = page;

				if(row == rowsToShow){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Receivables[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Receivables.length; i++)
		{
			let textMatched = false;

	        //sales_id, profile_img, account_name, type, order_date, total_cost, psr_assigned, page, show

			if(Receivables[i]['account_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Receivables[i]['type'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Receivables[i]['balance_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Receivables[i]['show'] = true;
			else
				Receivables[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();

	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	var sortDirection = {
		'account_name' : 'asc',
		'id' : 'asc',
		'total_cost' : 'asc',
	};


	////////////////ALL ABOUT SORTING/////////////////////////
	function SortAll(column_name)
	{
		if(column_name == 'id' || column_name == 'total_cost')
		{
			if(sortDirection[column_name] == 'asc')
			{
				sortDirection[column_name] = 'desc';
				Receivables.sort(function(a, b){
					return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
				});
			}
			else if(sortDirection[column_name] == 'desc')
			{
				sortDirection[column_name] = 'asc';
				Receivables.sort(function(a, b){
					return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
				});
			}
		}

		else
		{
			if(sortDirection[column_name] == 'asc')
			{
				sortDirection[column_name] = 'desc';
				Receivables.sort(function(a, b){
					if(a[column_name] < b[column_name])
						return -1;
					if(a[column_name] > b[column_name])
						return 1;
					return 0;
				});
			}
			else if(sortDirection[column_name] == 'desc')
			{	
				sortDirection[column_name] = 'asc';

				Receivables.sort(function(a, b){
					if(a[column_name] > b[column_name])
						return -1;
					if(a[column_name] < b[column_name])
						return 1;
					return 0;
				});
			}
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

		let salesTbleBody = document.querySelector('#receivables-list');
		let tableContent = '';

		for(let i = 0; i < Receivables.length; i++)
		{
			if(Receivables[i]['show'] && Receivables[i]['page'] == __pageNumber)
			{					
				tableContent += '<div class="d-card">';
				tableContent += '<div class="sections">';
				tableContent += '<img src="'+Receivables[i]['profile_img']+'">';
				tableContent += '<div class="name">';
				tableContent += '<h2 class="text-primary">'+Receivables[i]['account_name']+'</h2>';
				tableContent += '<h3>'+Receivables[i]['type']+'</h3>';
				tableContent += '</div>';
				tableContent += '</div>';
				tableContent += '<div class="sections">';
				tableContent += '<h2 class="text-warning">'+Receivables[i]['total_bill_format']+'</h2>';
				tableContent += '<h3>Balance</h3>';
				tableContent += '</div>';
				tableContent += '<a href="/transactions/receivables/'+Receivables[i]['id']+'" class="button pulse">See more details</a>';
				tableContent += '</div>';
			}
		}
		if(__totalRows() > 0)
			salesTbleBody.innerHTML = tableContent;
		else
			salesTbleBody.innerHTML = '<div class="card" style="display:flex; justify-content:center; padding:30px;">"<span style="color:#5cbcf2;">'+__searchText+'</span>" is not found.</div>';

		$('#--show-receivables').html(__totalRows());
		$('#--total-receivables').html(Receivables.length);
		$('#--total-cost-receivables').html('&#8369; '+__totalCostOfReceivable());

	}

	function __totalCostOfReceivable()
	{
		let totalCost = 0;
		Receivables.forEach(function(element, index , array){
			totalCost += element['total_bill'];
		});
		return (totalCost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}