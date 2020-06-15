	function getSuppliersViaAjax()
	{
		axios.get(`/ajax/users/suppliers`)
			.then((response) => {
				if(response.data.length > 0) {
					getSupplier(response.data);

					$('#--has-existing').show();
					$('#--no-existing').hide();
				}
				else {
					$('#--no-existing').show();
					$('#--has-existing').hide();
				}
			});
	}

	(function(){ getSuppliersViaAjax(); })();

	var Supplier = new Array();

	function getSupplier(supplier){
		Supplier = supplier;
		console.log(Supplier);
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Supplier.length; i++){
			if(Supplier[i]['show'])//count only if able to show
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

		for(let i = 0; i < Supplier.length; i++){
			if(Supplier[i]['show']){
				Supplier[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Supplier[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Supplier.length; i++)
		{
			let textMatched = false;

            //id, supplier_name, address, contact_no, profile_img, page, show


	        if(Supplier[i]['supplier_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Supplier[i]['address'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Supplier[i]['contact_no'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Supplier[i]['show'] = true;
			else
				Supplier[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();

	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////



	////////////////ALL ABOUT SORTING/////////////////////////
	var sortDirection = {
		'supplier_name' : 'asc',
		'address' : 'asc',
	};

	function SortAll(column_name)
	{		

		if(sortDirection[column_name] == 'asc'){

			sortDirection[column_name] = 'desc';
			
			Supplier.sort(function(a, b){
				if(a[column_name] < b[column_name])
					return -1;
				if(a[column_name] > b[column_name])
					return 1;
				return 0;
			});

		}
		else if(sortDirection[column_name] == 'desc'){
			
			sortDirection[column_name] = 'asc';

			Supplier.sort(function(a, b){
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

		let supplierTbleBody = document.querySelector('#suppliers-tbl-body');
		let tableContent = '';

		for(let i = 0; i < Supplier.length; i++)
		{
			if(Supplier[i]['show'] && Supplier[i]['page'] == __pageNumber)
			{
            	//id, supplier_name, address, contact_no, profile_img, page, show
				tableContent += '<tr>';
				
				tableContent += '<td></td>';
				
				tableContent += '<td class="td-name">';
				tableContent += '<img src="'+Supplier[i]['profile_img']+'" alt="">';
				tableContent += '<div class="name">';
				tableContent += '<h2>'+Supplier[i]['supplier_name']+'</h2>';
				tableContent += '<h3><span class="stat-clr"></span>Active</h3>';
				tableContent += '</div>';
				tableContent += '</td>';
				
				tableContent += '<td><i class="ti-home"></i>'+Supplier[i]['address']+'</td>';

				tableContent += '<td><i class="ti-mobile"></i>'+Supplier[i]['contact_no']+'</td>';

				tableContent += '<td class="td-btn" title="Show transaction history">';
				tableContent += '<a href="#">';
				tableContent += '<i class="ti-bookmark-alt"></i>';
				tableContent += '</a>';
				tableContent += '</td>';

				tableContent += '<td class="td-btn" title="Edit account details" onclick="openModal(\'update-supplier-modal\'), userDetails(\''+Supplier[i]['id']+'\')"><i class="ti-pencil-alt"></i></td>';
				
				tableContent += '</tr>';
				
			}
		}
		if(__totalRows() > 0)
			supplierTbleBody.innerHTML = tableContent;
		else
			supplierTbleBody.innerHTML = '<tr><td colspan="6" style="padding:30px;">"<span style="color:#5cbcf2;">'+__searchText+'</span>" not found.</td></tr>';

		$('#--show-suppliers').html(__totalRows());
		$('#--total-suppliers').html(Supplier.length);

	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////