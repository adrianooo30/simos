
	function getSoonExpiringViaAjax()
	{
		axios.get('/ajax/inventory/soon-expiring')
			.then((response) => {
				console.log(response.data);
				getSoonExpiring(response.data);
			});

	}

	(function(){ getSoonExpiringViaAjax(); })();

	var SoonExpiring = new Array();

	function getSoonExpiring(soonExpiring){
		SoonExpiring = soonExpiring;
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < SoonExpiring.length; i++){
			if(SoonExpiring[i]['show'])//count only if able to show
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

		for(let i = 0; i < SoonExpiring.length; i++){
			if(SoonExpiring[i]['show']){
				SoonExpiring[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				SoonExpiring[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{
		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < SoonExpiring.length; i++)
		{
			let textMatched = false;

	        //id, product_img, generic_name&strength, generic_name, brand_name, strength, product_unit, d_unit_price, total, page, show

	        if(SoonExpiring[i]['product']['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['product']['generic_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['product']['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['product']['strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['product']['unit_price_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['exp_date'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['quantity'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['quantity_format'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(SoonExpiring[i]['batch_no'].toString().toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				SoonExpiring[i]['show'] = true;
			else
				SoonExpiring[i]['show'] = false;

		}

		__putPageNumbers();

		Pagination();
		Manipulate();
	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

	////////////////ALL ABOUT SORTING/////////////////////////
	var sortDirection = {
		'generic_name&strength' : 'asc',
		'batch_no' : 'asc',
		'id' : 'asc',
	};

	function SortAll(column_name)
	{
		switch(column_name)
		{
			case 'generic_name&strength':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					SoonExpiring.sort(function(a, b){
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

					SoonExpiring.sort(function(a, b){
						if(a['product'][column_name] > b['product'][column_name])
							return -1;
						if(a['product'][column_name] < b['product'][column_name])
							return 1;
						return 0;
					});
				}
			break;

			case 'id':
				if(sortDirection[column_name] == 'asc'){

					sortDirection[column_name] = 'desc';
					
					SoonExpiring.sort(function(a, b){
						return removeComma(a[column_name].toString()) - removeComma(b[column_name].toString());
					});

				}
				else if(sortDirection[column_name] == 'desc'){
					
					sortDirection[column_name] = 'asc';

					SoonExpiring.sort(function(a, b){
						return removeComma(b[column_name].toString()) - removeComma(a[column_name].toString());
					});

				}
			break;

			case 'batch_no':
				if(sortDirection[column_name] == 'asc')
				{
					sortDirection[column_name] = 'desc';
					
					SoonExpiring.sort(function(a, b){
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

					SoonExpiring.sort(function(a, b){
						if(a[column_name] > b[column_name])
							return -1;
						if(a[column_name] < b[column_name])
							return 1;
						return 0;
					});
				}
			break;
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
		$('#soon-expiring-tbl-body').empty();

		SoonExpiring.forEach(function(soonExpire, index){
			if(soonExpire['show'] && soonExpire['page'] == __pageNumber)
			{

				let expiringWithins = '';
				if(soonExpire['soon_expire_within'].days >= 2)
					expiringWithins += `<span class="text-danger">Expiring within ${ soonExpire['soon_expire_within'].days } days.</span><br>`;
				else if(soonExpire['soon_expire_within'].days === 1)
					expiringWithins += `<span class="text-danger">Expiring within ${ soonExpire['soon_expire_within'].days } day.</span><br>`;

				if(soonExpire['soon_expire_within'].months >= 2)
					expiringWithins += `<span class="text-primary">Expiring within ${ soonExpire['soon_expire_within'].months } months.</span><br>`;
				else if(soonExpire['soon_expire_within'].months === 1)
					expiringWithins += `<span class="text-primary">Expiring within ${ soonExpire['soon_expire_within'].months } month.</span><br>`;

				if(soonExpire['soon_expire_within'].years >= 2)
					expiringWithins += `<span class="text-success">Expiring within ${ soonExpire['soon_expire_within'].years } years.</span>`;
				else if(soonExpire['soon_expire_within'].years === 1)
					expiringWithins += `<span class="text-success">Expiring within ${ soonExpire['soon_expire_within'].years } year.</span>`;

				$('#soon-expiring-tbl-body').append(`
					<tr>
	        			<td></td>
	        			<td class="td-name">
	        				<img src="${ soonExpire['product']['product_img'] }" alt="">
	        				<div class="name">
	        					<h2>${ soonExpire['product']['generic_name'] } ${ soonExpire['product']['strength'] }</h2>
	        					<h2 class="text-muted">
	        						${ soonExpire['product']['brand_name'] }
	        					</h2>
	        					<h2>${ soonExpire['product']['unit_price_format'] }</h2>
	        				</div>
	        			</td>
	        			<td>
							<label class="text-primary imp">${ soonExpire['batch_no'] } - ${ soonExpire['quantity_format'] } pcs.</label>
	        			</td>
	        			<td>${ soonExpire['exp_date'] }</td>
	        			<td>
							${ expiringWithins }
	        			</td>
	        		</tr>
				`);
			}
		});

		if(__totalRows() === 0)
		{
			$('#soon-expiring-tbl-body').append(`
				<tr><td colspan="6" style="padding:30px;">${ __searchText } not found.</td></tr>
			`);
		}

		$('#--show').html(__totalRows());
		$('#--total').html(SoonExpiring.length);
		$('#--total-cost').html('&#8369; '+__totalCostOfProducts());


	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS//////////////

	function __totalCostOfProducts()
	{
		let totalCost = SoonExpiring.reduce(function(total, value, index, array){
			return total += (value['total'] * value['unit_price']);
		}, 0);

		return totalCost.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
	}