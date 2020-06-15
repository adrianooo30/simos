	
	function getSalesViaAjax()
	{
		axios.get('/ajax/transactions/sales')
		.then((success) => {
			console.log(success.data);
			getSales(success.data);
		})
		.catch((error) => {
			console.log(error.data);
		});
	}

	(function(){ getSalesViaAjax(); })();

	var Sales = new Array();

	function getSales(sales){			
		Sales = sales;
		console.log(Sales);
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Sales.length; i++){
			if(Sales[i]['show'])//count only if able to show
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

		for(let i = 0; i < Sales.length; i++){
			if(Sales[i]['show']){
				Sales[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Sales[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Sales.length; i++)
		{
			let textMatched = false;

            //id, profile_img, account_name, type, order_date, total_cost_format, psr_assigned, page, show

			if(Sales[i]['account']['account_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Sales[i]['account']['type'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Sales[i]['deliver_transaction']['receipt_no'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Sales[i]['order_date'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Sales[i]['total_cost_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Sales[i]['employee']['full_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Sales[i]['show'] = true;
			else
				Sales[i]['show'] = false;

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
		switch(column_name)
		{
			case 'account_name':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					Sales.sort(function(a, b){
						if(a['account'][column_name] < b['account'][column_name])
							return -1;
						if(a['account'][column_name] > b['account'][column_name])
							return 1;
						return 0;
					});
				}
				
				else if(sortDirection[column_name] == 'desc')
				{
					sortDirection[column_name] = 'asc';

					Sales.sort(function(a, b){
						if(a['account'][column_name] > b['account'][column_name])
							return -1;
						if(a['account'][column_name] < b['account'][column_name])
							return 1;
						return 0;
					});
				}
			break;

			case 'id':
				if(sortDirection[column_name] == 'asc'){

					sortDirection[column_name] = 'desc';
					
					Sales.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					Sales.sort(function(a, b){
						return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
					});

				}
			break;

			case 'total_cost':
				if(sortDirection[column_name] == 'asc'){

					sortDirection[column_name] = 'desc';
					
					Sales.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					Sales.sort(function(a, b){
						return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
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

		if(__greaterThan10())
		{
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
		let salesTbleBody = document.querySelector('#sales-tbl-body');
		let tableContent = '';


		for(let i = 0; i < Sales.length; i++)
		{
			if(Sales[i]['show'] && Sales[i]['page'] == __pageNumber)
			{
				tableContent += '<tr>';
				tableContent += '<td><span class="blue">'+Sales[i]['deliver_transaction']['receipt_no']+'</span></td>';
				tableContent += '<td class="td-name">';
				tableContent += '<img src="'+Sales[i]['account']['profile_img']+'" alt="">';
				tableContent += '<div class="name">';
				tableContent += '<h2>'+Sales[i]['account']['account_name']+'</h2>';
				tableContent += '<h3>'+Sales[i]['account']['type']+'</h3>';
				tableContent += '</div>';
				tableContent += '</td>';
				tableContent += '<td>'+Sales[i]['order_date']+'</td>';
				tableContent += '<td>'+Sales[i]['total_cost_format']+'</td>';
				tableContent += '<td>';

				let statusDesign, statusText;
				if(Sales[i]['status'] == 'Delivered'){
					statusDesign = 'Not-Paid'; // style is like in Cancel
					statusText = 'Not Paid';
				}
				else if(Sales[i]['status'] == 'Balanced'){
					statusDesign = 'Balanced';// style is like in Pending
					statusText = 'Partially Paid';
				}
				else if(Sales[i]['status'] == 'Paid'){
					statusDesign = 'Paid'; // style is like in Delivered
					statusText = 'Paid';
				}

				tableContent += '<button id="stat-badge'+Sales[i]['id']+'" class="status-btn '+statusDesign+'">'+statusText+'</button>';
				tableContent += '</div>';
				tableContent += '</td>';
				tableContent += '<td class="more-alt td-btn" title="Show more details" onclick="openModal(\'sales-modal\'), orderDetails(\''+Sales[i]['id']+'\')"><i class="ti-more-alt"></i></td>';
				tableContent += '</tr>';
			}
		}
		if(__totalRows() > 0)
			salesTbleBody.innerHTML = tableContent;
		else
			salesTbleBody.innerHTML = '<tr><td colspan="6" style="padding:30px;">"'+__searchText+'" not found.</td></tr>';

		$('#--show-sales').html(__totalRows());
		$('#--total-sales').html(Sales.length);
		$('#--total-cost-sales').html(__totalCostOfSales());

	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

	function __totalCostOfSales()
	{
		let totalCost = 0;
		Sales.forEach(function(element, index , array){
			totalCost += element['total_cost'];
		});
		return '&#8369; '+(totalCost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}
