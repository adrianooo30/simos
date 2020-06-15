	function getEmployeesViaAjax()
	{
		axios.get(`/ajax/users/employees`)
			.then((response) => {
				console.log(response.data);

				if(response.data.length > 0) {
					getEmployees(response.data);

					$('#--has-existing').show();
					$('#--no-existing').hide();
				}
				else {
					$('#--no-existing').show();
					$('#--has-existing').hide();
				}
			});
	}

	(function(){ getEmployeesViaAjax(); })();

	var Employees = new Array();

	function getEmployees(employees){
		Employees = employees;
		Manipulate();
		Pagination();
	}


	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Employees.length; i++){
			if(Employees[i]['show'])//count only if able to show
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

		for(let i = 0; i < Employees.length; i++){
			if(Employees[i]['show']){
				Employees[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Employees[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{

		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Employees.length; i++)
		{
			let textMatched = false;

	        //id, full_name, fname, mname, lname, position, contact_no, address, profile_img, page, show


	        if(Employees[i]['full_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Employees[i]['fname'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Employees[i]['mname'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Employees[i]['lname'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Employees[i]['position'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Employees[i]['contact_no'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Employees[i]['address'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Employees[i]['show'] = true;
			else
				Employees[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();

	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////



	////////////////ALL ABOUT SORTING/////////////////////////
	var sortDirection = {
		'full_name' : 'asc',
		'address' : 'asc',
	};

	function SortAll(column_name)
	{		

		if(sortDirection[column_name] == 'asc'){

			sortDirection[column_name] = 'desc';
			
			Employees.sort(function(a, b){
				if(a[column_name] < b[column_name])
					return -1;
				if(a[column_name] > b[column_name])
					return 1;
				return 0;
			});

		}
		else if(sortDirection[column_name] == 'desc'){
			
			sortDirection[column_name] = 'asc';

			Employees.sort(function(a, b){
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

		$('#employees-tbl-body').empty();

		Employees.forEach(function(employee, index){

			if(employee['show'] && employee['page'] == __pageNumber)
			{
				$('#employees-tbl-body').append(`
					<tr>
						<td></td>
						<td class="td-name">
							<img src="${ employee['profile_img'] }" alt="">
							<div class="name">
								<h2>${ employee['full_name'] }</h2>
								<h3>${ employee['position']['position_name'] }</h3>
							</div>
						</td>
						<td>
							<i class="ti-home"></i> ${ employee['address'] }
						</td>
						<td>
							<i class="ti-mobile"></i> ${ employee['contact_no'] }
						</td>
						<td class="td-btn" title="Show holding products" onclick="openModal('set-product-modal')">
							<i class="fas fa-syringe"></i>
						</td>
						<td class="td-btn" title="Edit account details" onclick="openModal('update-employee-modal'), userDetails(${ employee['id'] })">
							<i class="ti-pencil-alt"></i>
						</td>
					</tr>
				`);
			}

		});

		if(__totalRows() === 0)
			$('#employees-tbl-body').append(`
				<tr><td colspan="6" style="padding:30px;">"<span style="color:#5cbcf2;">${ __searchText }</span>" not found.</td></tr>
			`);

		$('#--show-employees').html(__totalRows());
		$('#--total-employees').html(Employees.length);

	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////