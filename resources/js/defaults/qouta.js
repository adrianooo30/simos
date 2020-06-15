
	function getQoutaViaAjax()
	{
		axios.get('/ajax/users/employees/qoutas')
			.then((response) => {
				console.log(response.data);
				
				getQouta(response.data);
			})
			.catch((error) => {
				console.log(error.response);
			});
	}

	(function(){ getQoutaViaAjax(); })();

	var Qouta = new Array();
	function getQouta(qouta){
		Qouta = qouta;
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Qouta.length; i++){
			if(Qouta[i]['show'])//count only if able to show
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

		for(let i = 0; i < Qouta.length; i++){
			if(Qouta[i]['show']){
				Qouta[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Qouta[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{
		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Qouta.length; i++)
		{
			let textMatched = false;

	        //id, product_img, generic_name&strength, generic_name, brand_name, strength, product_unit, d_unit_price, total, page, show

	        if(Qouta[i]['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Qouta[i]['generic_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Qouta[i]['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Qouta[i]['strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Qouta[i]['product_unit'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Qouta[i]['unit_price_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Qouta[i]['stock_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Qouta[i]['show'] = true;
			else
				Qouta[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();
	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	////////////////ALL ABOUT SORTING/////////////////////////
	var sortDirection = {
		'generic_name&strength' : 'asc',
		'product_unit' : 'asc',
		'stock' : 'asc',
		'unit_price' : 'asc',
	};

	function SortAll(column_name)
	{
		if(column_name == 'stock' || column_name == 'unit_price'){
			if(sortDirection[column_name] == 'asc'){

				sortDirection[column_name] = 'desc';
				
				Qouta.sort(function(a, b){
					return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
				});

				__putPageNumbers();

			}
			else if(sortDirection[column_name] == 'desc'){
				
				sortDirection[column_name] = 'asc';

				Qouta.sort(function(a, b){
					return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
				});

				__putPageNumbers();

			}
		}
		else
		{

			if(sortDirection[column_name] == 'asc'){

				sortDirection[column_name] = 'desc';
				
				Qouta.sort(function(a, b){
					if(a[column_name] < b[column_name])
						return -1;
					if(a[column_name] > b[column_name])
						return 1;
					return 0;
				});

				__putPageNumbers();

			}
			else if(sortDirection[column_name] == 'desc'){
				
				sortDirection[column_name] = 'asc';

				Qouta.sort(function(a, b){
					if(a[column_name] > b[column_name])
						return -1;
					if(a[column_name] < b[column_name])
						return 1;
					return 0;
				});

				__putPageNumbers();

			}
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
		let paginationTag = document.querySelector('#pagination-qouta ul');
		let pagination = '';

		if(__greaterThan10()) {
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
		let pageLinks = document.querySelectorAll('#pagination-qouta ul li');

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
		// empty every time this method is called
		$('#qouta-tbl-body').empty();

		Qouta.forEach(function(qouta, index){

			if(qouta['show'] && qouta['page'] == __pageNumber)
			{
				$('#qouta-tbl-body').append(`
					<tr>
						<td class="text-primary">
							<i class="ti-user"></i> ${ qouta['full_name'] }
						</td>
						<td class="text-primary">${ qouta['target_amount'] }</td>
						<td class="text-primary">${ qouta['achievement_amount'] }</td>
						<td class="text-primary">${ qouta['percent'] }</td>
					</tr>
				`);
			}
		});

	}