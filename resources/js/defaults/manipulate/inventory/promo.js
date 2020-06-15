
	function getPromosViaAjax()
	{
		axios.get('/ajax/inventory/promo')
			.then((response) => {
				console.log(response.data);

				if(response.data.length > 0) {
					getPromos(response.data);

					$('#--has-existing').show();
					$('#--no-existing').hide();
					
				} else {
					$('#--no-existing').show();
					$('#--has-existing').hide();
				}

			});
	}

	(function(){ getPromosViaAjax(); })();

	var Promos = new Array();

	function getPromos(promos)
	{
		Promos = promos;
		Manipulate();
		Pagination();
	}

	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////
	function __totalRows()
	{
		let numberOfRows = 0;
		for(let i = 0; i < Promos.length; i++){
			if(Promos[i]['show'])//count only if able to show
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

		for(let i = 0; i < Promos.length; i++){
			if(Promos[i]['show']){
				Promos[i]['page'] = page;

				if(row == 10){
					row = 1;
					page++;
				}
				else
					row++;
			}
			else
				Promos[i]['page'] = 0;
		}
	}
	//////////////////// ALL ABOUT USEABLE METHODS THAT USES EVERYTIME////////////////


	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////
	var __searchText;

	function SearchAll(text)
	{
		__searchText = text;
		__pageNumber = 1;

		for(let i = 0; i < Promos.length; i++)
		{
			let textMatched = false;

	        if(Promos[i]['product']['generic_name&strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Promos[i]['product']['generic_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Promos[i]['product']['brand_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Promos[i]['product']['strength'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Promos[i]['product']['unit_price_format'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;
			else if(Promos[i]['promo_name'].toLowerCase().indexOf(text.toLowerCase()) > -1)
				textMatched = true;

			if(textMatched)
				Promos[i]['show'] = true;
			else
				Promos[i]['show'] = false;
		}

		__putPageNumbers();

		Pagination();
		Manipulate();
	}
	//////////////////////ALL ABOUT SEARCHING OF DATA///////////////////////

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
		$('#promo-list').empty();

		Promos.forEach(function(promo, index){
			if(promo['show'] && promo['page'] == __pageNumber)
			{
				$('#promo-list').append(`
					<div class="d-card">
						<div class="sections mt-3">
							<img src="${ promo['product']['product_img'] }">
						    <div class="name">
						        <h2 class="text-primary">${ promo['product']['generic_name&strength'] }</h2>
						        <h2 class="text-muted">${ promo['product']['brand_name'] }</h2>
						        <h2 class="text-primary">${ promo['product']['unit_price_format'] }</h2>
						    </div>
						</div>

						<div class="sections">
							<h4 class="text-primary text-center">${ promo['promo_name'] }</h4>	

						    <button type="submit" onclick="openModal('edit-promo-modal'), getPromoDetails(${ promo['id'] })" class="button pulse">
						        See more details
						    </button>
						</div>
					</div>
				`);
			}
		});

		// IF NOTHING IS DISPLAYED
		if(__totalRows() === 0) {
			$('#promo-list').append(`
				<div class="card not-custom-card container">
					<div class="card-body text-center">
						<span class="text-primary">"${ __searchText }"</span> not found.
					</div>
				</div>
			`);
		}

		$('#--show').html(__totalRows());
		$('#--total').html(Promos.length);
	}
	/////////////////FOR DISPLAYING OF RESULTS VIA METHODS/////////////