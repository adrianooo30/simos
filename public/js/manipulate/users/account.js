	function getAccountsViaAjax()
	{
		axios.get(`/ajax/users/accounts`)
			.then((response) => {

				console.log(response.data);

				if(response.data.length > 0) {
					getAccounts(response.data);

					$('#--has-existing').show();
					$('#--no-existing').hide();
				}
				else {
					$('#--no-existing').show();
					$('#--has-existing').hide();
				}

			});
	}

	(function(){ getAccountsViaAjax(); })();

	var Accounts = new Array();

	function getAccounts(accounts){
		Accounts = accounts;
		Manipulate();
		Pagination();
	}


	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Accounts.length; i++){
			if(Accounts[i]['show'])//count only if able to show
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

		for(let i = 0; i < Accounts.length; i++){
			if(Accounts[i]['show']){
				Accounts[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Accounts[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Accounts.length; i++)
		{
			let textMatched = false;

            //id, account_name, type, address, contact_no, contact_person, profile_img, balance, page, show

	        if(Accounts[i]['account_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Accounts[i]['type'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Accounts[i]['address'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Accounts[i]['contact_no'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Accounts[i]['contact_person'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Accounts[i]['show'] = true;
			else
				Accounts[i]['show'] = false;

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
			
			Accounts.sort(function(a, b){
				if(a[column_name] < b[column_name])
					return -1;
				if(a[column_name] > b[column_name])
					return 1;
				return 0;
			});

		}
		else if(sortDirection[column_name] == 'desc'){
			
			sortDirection[column_name] = 'asc';

			Accounts.sort(function(a, b){
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

	function Manipulate()
	{
		$('#accounts-tbl-body').empty();

		Accounts.forEach(function(account, index){

			if(account['show'] && account['page'] == __pageNumber)
			{
				$('#accounts-tbl-body').append(`
					<tr>
						<td></td>
						<td class="td-name">
							<img src="${ account['profile_img'] }" alt="">
							<div class="name">
								<h2>${ account['account_name'] }</h2>
								<h3>${ account['type'] }</h3>
							</div>
						</td>
						<td>
							<i class="ti-home"></i> ${ account['address'] }
						</td>
						<td>
							<div class="name">
								<h2 style="font-weight: lighter;"> <i class="ti-mobile"></i> ${ account['contact_no'] } </h2>
								<h3>-${ account['contact_person'] } </h3>
							</div>
						</td>
						<td class="td-btn" title="Show transaction history">
							<a href="/account/history/${ account['id'] }">
								<i class="ti-bookmark-alt"></i>
							</a>
						</td>
						<td class="td-btn" title="Edit account details">
							<i class="ti-pencil-alt" onclick="openModal('update-account-modal'), userDetails('${ account['id'] }')"></i>
						</td>
					</tr>
				`);
			}

		});

		if(__totalRows() === 0)
			$('#accounts-tbl-body').append(`<tr><td colspan="6" style="padding:30px;">"${ __searchText }" not found.</td></tr>`);

		$('#--show-accounts').html(__totalRows());
		$('#--total-accounts').html(Accounts.length);

	}
