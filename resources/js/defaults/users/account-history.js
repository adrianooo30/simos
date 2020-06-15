	var typeOfHistory = 'delivered'; // delivered or order

	function getHistoryViaAjax()
	{
		axios.get('/account/history/'+accountId+'/ajax',{
			params : {
				type : typeOfHistory,
			}
		})
		.then((response) => {
			getHistory(response.data);
			console.log(response.data);
		})
		.catch((error) => {
			console.log(error);
		});
	}

	(function(){ getHistoryViaAjax(); })();

	var History = new Array();
	function getHistory(history){
		History = history;
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < History.length; i++){
			if(History[i]['show'])//count only if able to show
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

		for(let i = 0; i < History.length; i++){
			if(History[i]['show']){
				History[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				History[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////

	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < History.length; i++)
		{
			let textMatched = false;

            //id, account_name, type, address, contact_no, contact_person, profile_img, balance, page, show

	        if(History[i]['account_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(History[i]['type'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(History[i]['address'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(History[i]['contact_no'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(History[i]['contact_person'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				History[i]['show'] = true;
			else
				History[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();

	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	////////////////ALL ABOUT SORTING/////////////////////////
	var sortDirection = {
		'account_name' : 'asc',
		'address' : 'asc',
	};

	function SortAll(column_name)
	{		

		if(sortDirection[column_name] == 'asc'){

			sortDirection[column_name] = 'desc';
			
			History.sort(function(a, b){
				if(a[column_name] < b[column_name])
					return -1;
				if(a[column_name] > b[column_name])
					return 1;
				return 0;
			});

		}
		else if(sortDirection[column_name] == 'desc'){
			
			sortDirection[column_name] = 'asc';

			History.sort(function(a, b){
				if(a[column_name] > b[column_name])
					return -1;
				if(a[column_name] < b[column_name])
					return 1;
				return 0;
			});

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
	var typeOfHistory; // preserve for type of history of account

	function Manipulate()
	{

		let historyTable = $('#account-history-tbl');
		
		let tableHead = '';
		if(typeOfHistory === 'delivered')
		{
			tableHead += '<thead>';
				tableHead += '<tr>';
					tableHead += '<td></td>';
					tableHead += '<td>Date of Order  <i class="ti-arrows-vertical"></i> </td>';
					tableHead += '<td>Total Cost  <i class="ti-arrows-vertical"></i> </td>';
					tableHead += '<td>PSR Assigned  <i class="ti-arrows-vertical"></i> </td>';
					tableHead += '<td></td>';
					tableHead += '<td></td>';
					tableHead += '</tr>';
			tableHead += '</thead>';
		}
		else if(typeOfHistory === 'ordered')
		{
			tableHead += '<thead>';
				tableHead += '<tr>';
					tableHead += '<td>Date of Order  <i class="ti-arrows-vertical"></i> </td>';
					tableHead += '<td>Total Cost  <i class="ti-arrows-vertical"></i> </td>';
					tableHead += '<td>PSR Assigned  <i class="ti-arrows-vertical"></i> </td>';
					tableHead += '<td></td>';
					tableHead += '<td></td>';
				tableHead += '</tr>';
			tableHead += '</thead>';
		}

		let tableContent = '';
		tableContent += '<tbody>';
		for(let i = 0; i < History.length; i++)
		{
			if(History[i]['show'] && History[i]['page'] == __pageNumber)
			{
				tableContent += '<tr>';
				
				if(typeOfHistory === 'delivered')
					tableContent += '<td><span class="text-primary fa-xsm"><i class="ti-receipt"></i> '+History[i]['receipt_no']+'</span></td>';
				// else if(typeOfHistory == 'ordered')
				// 	tableContent += '<td><span class="text-primary fa-xsm">'+History[i]['order_id']+'</span></td>';

				tableContent += '<td><i class="ti-calendar"></i> '+History[i]['order_date']+'</td>';

				tableContent += '<td class="text-primary">&#8369; '+History[i]['total_cost_format']+'</td>';

				tableContent += '<td><i class="ti-user"></i> '+History[i]['psr_assigned']+'</td>';

				tableContent += '<td>';
				tableContent += '<button class="status-btn '+History[i]['status']+'">'+History[i]['status']+'</button>';
				tableContent += '</td>';

				tableContent += '<td class="td-btn" title="View more details">';
				tableContent += '<i class="ti-more-alt"></i>';
				tableContent += '</td>';

				tableContent += '</tr>';
			}
		}
		tableContent += '</tbody>';

		if(__totalRows() > 0){
			historyTable.append(tableHead);
			historyTable.append(tableContent);
		}
		else{
			historyTable.append(tableHead);
			historyTable.append('<tr><td colspan="6" style="padding:30px;">"'+__searchText+'" not found.</td></tr>');
		}

		$('#--show-history').html(__totalRows());
		$('#--total-history').html(History.length);
		$('#--total-cost-history').html('&#8369; '+__totalCostOfHistory());

	}

	function __totalCostOfHistory()
	{
		let totalCost = 0;
		History.forEach(function(element, index , array){
			totalCost += element['total_cost'];
		});
		return (totalCost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

		var accountId;
		function userDetails(id){

			accountId = id;

			let editAcc = document.querySelector('#--edit-account');
			let loading = document.querySelector('#--account-loading');

			editAcc.style.display = "none";
			loading.style.display = "block";

			let xmlrequest = new XMLHttpRequest();
			xmlrequest.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){

					loading.style.display = "none";
					editAcc.style.display = "block";

					let user_account = JSON.parse(this.responseText);

					document.querySelector('#--e-profile-img-hidden').value = user_account['profile_img'];
					document.querySelector('#--e-profile-img').setAttribute('src', user_account['profile_img']);
					
					document.querySelector('#id_e_account_name').value = user_account['account_name'];
					document.querySelector('#id_e_type').value = user_account['type'];

					document.querySelector('#id_e_address').value = user_account['address'];
					
					document.querySelector('#id_e_contact_no').value = user_account['contact_no'];
					document.querySelector('#id_e_contact_person').value = user_account['contact_person'];
				}
			}
			xmlrequest.open('GET', 'account/'+id, true);
			xmlrequest.send();

		}
