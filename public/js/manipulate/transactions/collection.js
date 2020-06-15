
	function getCollectionViaAjax()
	{
		axios.get(`/ajax/transactions/collections`)
			.then((response) => {
				console.log(response.data);
				getCollection(response.data);
			});
	}

	(function() { getCollectionViaAjax(); })();

	var Collection = new Array();

	function getCollection(collection){
		Collection = collection;
		console.log(Collection);
		Manipulate();
		Pagination();
	}


	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Collection.length; i++){
			if(Collection[i]['show'])//count only if able to show
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

		for(let i = 0; i < Collection.length; i++){
			if(Collection[i]['show']){
				Collection[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Collection[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Collection.length; i++)
		{

			//collection_id, dr_receipt_no & cr_receipt_no, profile_img, account_name, type, collection_date, amount, collectionHandledBy, type_of_payment

			let textMatched = false;

			if(Collection[i]['receipt_no'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Collection[i]['account']['account_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Collection[i]['account']['type'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Collection[i]['collection_date'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Collection[i]['amount'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Collection[i]['amount_format'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Collection[i]['employee']['full_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Collection[i]['show'] = true;
			else
				Collection[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();

	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	var sortDirection = {
		'id' : 'asc',
		'account_name' : 'asc',
		'full_name' : 'asc',
		'amount' : 'asc',
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
					
					Collection.sort(function(a, b){
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

					Collection.sort(function(a, b){
						if(a['account'][column_name] > b['account'][column_name])
							return -1;
						if(a['account'][column_name] < b['account'][column_name])
							return 1;
						return 0;
					});
				}
			break;

			case 'full_name':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					Collection.sort(function(a, b){
						if(a['employee'][column_name] < b['employee'][column_name])
							return -1;
						if(a['employee'][column_name] > b['employee'][column_name])
							return 1;
						return 0;
					});
				}
				
				else if(sortDirection[column_name] == 'desc')
				{
					sortDirection[column_name] = 'asc';

					Collection.sort(function(a, b){
						if(a['employee'][column_name] > b['employee'][column_name])
							return -1;
						if(a['employee'][column_name] < b['employee'][column_name])
							return 1;
						return 0;
					});
				}
			break;

			case 'id':
				if(sortDirection[column_name] == 'asc'){

					sortDirection[column_name] = 'desc';
					
					Collection.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					Collection.sort(function(a, b){
						return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
					});

				}
			break;

			case 'amount':
				if(sortDirection[column_name] == 'asc'){

					sortDirection[column_name] = 'desc';
					
					Collection.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					Collection.sort(function(a, b){
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

		let collectionTbleBody = document.querySelector('#collections-tbl-body');
		let tableContent = '';

		for(let i = 0; i < Collection.length; i++)
		{
			if(Collection[i]['show'] && Collection[i]['page'] == __pageNumber)
			{

				//collection_id, dr_receipt_no & cr_receipt_no, profile_img, account_name, type, collection_date, amount, collectionHandledBy, type_of_payment

				tableContent += '<tr>';
				tableContent += '<td><span class="green">'+Collection[i]['receipt_no']+'</span></td>';
				tableContent += '<td class="td-name">';
				tableContent += '<img src="'+Collection[i]['account']['profile_img']+'" alt="">';
				tableContent += '<div class="name">';
				tableContent += '<h2>'+Collection[i]['account']['account_name']+'</h2>';
				tableContent += '<h3>'+Collection[i]['account']['type']+'</h3>';
				tableContent += '</div>';
				tableContent += '</td>';
				tableContent += '<td>'+Collection[i]['collection_date']+'</td>';
				tableContent += '<td>'+Collection[i]['amount_format']+'</td>';
				tableContent += '<td>'+Collection[i]['employee']['full_name']+'</td>';
				tableContent += '<td class="more-alt td-btn" title="Show more details" onclick="openModal(\'collection-modal\'), orderDetails(\''+Collection[i]['id']+'\')"><i class="ti-more-alt"></i></td>';
				tableContent += '</tr>';
			}
		}
		if(__totalRows() > 0)
			collectionTbleBody.innerHTML = tableContent;
		else
			collectionTbleBody.innerHTML = '<tr><td colspan="6" style="padding:30px;">"'+__searchText+'" not found.</td></tr>';

		$('#--show-collections').html(__totalRows());
		$('#--total-collections').html(Collection.length);
		$('#--total-cost-collections').html(__totalCostOfCollection());

	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

	function __totalCostOfCollection()
	{
		let totalCost = 0;
		Collection.forEach(function(element, index , array){
			totalCost += element['amount'];
		});
		return '&#8369; '+(totalCost).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}