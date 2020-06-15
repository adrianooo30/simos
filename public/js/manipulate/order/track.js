	function getOrdersViaAjax()
	{
		let xmlrequest = new XMLHttpRequest();
		xmlrequest.onreadystatechange = function(){

			if(this.readyState == 4 && this.status == 200)
			{
				let orders = JSON.parse(this.responseText);

				getOrders(orders);

			}
		}
		xmlrequest.open('GET', '/ajax/order/track', true);
		xmlrequest.send();


	}

	(function(){ getOrdersViaAjax(); })();

	var Orders = new Array();

	//profile_img, account_name, type, order_id, order_date, total, status, page, show

	function getOrders(orders){
		Orders = orders;
		console.log(Orders);
		Manipulate();
		Pagination();
	}
	
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Orders.length; i++){
			if(Orders[i]['show'])//count only if able to show
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

		for(let i = 0; i < Orders.length; i++){
			if(Orders[i]['show']){
				Orders[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Orders[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Orders.length; i++)
		{
			let textMatched = false;

			//profile_img, account_name, type, order_id, order_date, total_cost, status, page, show
			
			if(Orders[i]['account']['account_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Orders[i]['account']['type'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Orders[i]['order_date'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Orders[i]['total_cost_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Orders[i]['status'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Orders[i]['show'] = true;
			else
				Orders[i]['show'] = false;

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
					
					Orders.sort(function(a, b){
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

					Orders.sort(function(a, b){
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
					
					Orders.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					Orders.sort(function(a, b){
						return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
					});

				}
			break;

			case 'total_cost':
				if(sortDirection[column_name] == 'asc'){

					sortDirection[column_name] = 'desc';
					
					Orders.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					Orders.sort(function(a, b){
						return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
					});

				}
			break;
		}

		__putPageNumbers();

		Manipulate();
	}

	////////////////ALL ABOUT SORTING/////////////////////////

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

		let orderTbleBody = document.querySelector('#orders-tbl-body');
		let tableContent = '';

		//profile_img, account_name, type, id, order_date, total_cost, status, page, show

		for(let i = 0; i < Orders.length; i++)
		{
			if(Orders[i]['show'] && Orders[i]['page'] == __pageNumber)
			{

				tableContent += '<tr>';
				tableContent += '<td></td>';
				tableContent += '<td class="td-name">';
				tableContent += '<img src="'+Orders[i]['account']['profile_img']+'" alt="">';
				tableContent += '<div class="name">';
				tableContent += '<h2>'+Orders[i]['account']['account_name']+'</h2>';
				tableContent += '<h3>'+Orders[i]['account']['type']+'</h3>';
				tableContent += '</div>';
				tableContent += '</td>';
				tableContent += '<td>'+Orders[i]['order_date']+'</td>';
				tableContent += '<td>'+Orders[i]['total_cost_format']+'</td>';
				tableContent += '<td>';
				tableContent += '<button id="stat-badge'+Orders[i]['id']+'" class="status-btn '+Orders[i]['status']+'">'+Orders[i]['status']+'</button>';
				tableContent += '</td>';
				tableContent += '<td class="td-btn" title="Show more details" onclick="toggleStatBtns(\'stat-opt'+Orders[i]['id']+'\')">';
				
				if(Orders[i]['status'] != 'Delivered')
				{
					tableContent += '<i class="ti-layers"></i>';
				
					tableContent += '<div id="stat-opt'+Orders[i]['id']+'" class="stat-opt" title="Click again to disappear">';
			
					if(Orders[i]['status'] == 'Pending' || Orders[i]['status'] == 'Canceled')
					tableContent += '<button class="option approve" title="Approve" onclick="statBadge(\''+Orders[i]['id']+'\',\'Approved\')">A</button>';

					if(Orders[i]['status'] == 'Approved' || Orders[i]['status'] == 'Pending')
						tableContent += '<button class="option cancel" title="Cancel" onclick="statBadge(\''+Orders[i]['id']+'\',\'Canceled\')">C</button>';

					if(Orders[i]['status'] == 'Approved' || Orders[i]['status'] == 'Canceled')
						tableContent += '<button class="option pending" title="Pending" onclick="statBadge(\''+Orders[i]['id']+'\',\'Pending\')">P</button>';

					if(Orders[i]['status'] == 'Approved')
						tableContent += '<button class="option deliver" title="Deliver" onclick="statBadge(\''+Orders[i]['id']+'\',\'Delivered\')">D</button>';
				}

				tableContent += '</div>';

				tableContent += '</td>';
				tableContent += '<td class="td-btn" title="Show orders"><i class="ti-shopping-cart"  onclick="openModal(\'order-modal\'), orderDetails(\''+Orders[i]['id']+'\')"></i></td>';
				tableContent += '</tr>';

			}
		}
		if(__totalRows() > 0)
			orderTbleBody.innerHTML = tableContent;
		else
			orderTbleBody.innerHTML = '<tr><td colspan="7" style="padding:30px;">"<span style="color:#5cbcf2;">'+__searchText+'</span>" not found.</td></tr>';

		$('#--show-orders').html(__totalRows());
		$('#--total-orders').html(Orders.length);
		$('#--total-cost-orders').html('&#8369; '+__totalCostOrders());

	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

	function __totalCostOrders()
	{
		let totalCost = 0;
		Orders.forEach(function(element, index , array){
			totalCost += element['total_cost'];
		});
		return (totalCost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
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

	function __numberWithComma(x)
	{
		x = x.toString();

		var pattern = /(-?\d+)(\d{3})/;

		while(pattern.test(x))
			x = x.replace(pattern, "$1,$2");

		return x;
	}