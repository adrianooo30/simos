
	document.querySelectorAll('[type="number"]').forEach(function(input, index, array){
		input.addEventListener('keyup', function(){
			
				if(parseInt(this.getAtrribute('max')) < parseInt(this.value))
					this.value = this.getAtrribute('max') ;

				else if(parseInt(this.value) < 1)
					this.value = 1;

				console.log('Oh please. naman.');

		});
	});

	document.querySelectorAll('input').forEach(function(input, index, array){
		input.setAttribute('autocomplete', 'off');
	});

	$('input').keyup(function(){
		if($(this).val() !== ''){
			$(this).closest('.input-field').find('label').remove();
		}
	});
	$('select').change(function(){
		if($(this).length > 0){
			$(this).closest('.input-field').find('label').remove();
		}
	});
	$('textarea').keyup(function(){
		if($(this).length > 0){
			$(this).closest('.input-field').find('label').remove();
		}
	});

	function removeErrorMessages()
	{
		$.each($('.error-message'), (key, label) => { label.remove() });
	}

	function removeInputValues(query)
	{
		$.each($(`.input-field ${ query }`), (key, input) => { input.value = '' });
	}

	function openSubNav(id, parentTab){
		$('#'+id).toggleClass('active');
		$('#'+parentTab+' i').toggleClass('active');
	}

	function toggleAccountProfile(){
		$(".account-profile").toggleClass('active');
	}

	function toggleSidebar(){
		$('#parent-sidebar').toggleClass('active');
		$('#sidebar-btn').toggleClass('active');
		$('#sidebar-bg').toggleClass('active');
		$('#container').toggleClass('blur');

		// toggleSearchBar('close');
	}

	// opening of tabs
	function openTab(tab_name, object = document.querySelector('.tab-for-med-modal li:first-child'))
	{
		tabs.forEach(function(tab, index){
			if(tab_name === tab)
				$('#'+tab).show();
			else
				$('#'+tab).hide();
		});

		object.parentElement.querySelectorAll('li').forEach(function(obj, index, array){
			obj.classList.remove('active');
		});

		object.classList.add('active');
	}


	function toUpper(obj, value){
		obj.value = value.toUpperCase();
	}
	function toLower(obj, value){
		obj.value = value.toLowerCase();
	}
	function toCaps(obj, value){
		let arr_value = value.split('');
		let Value = "";
		for(let i = 0; i < arr_value.length; i++){
			if(i == 0)
				Value += arr_value[i].toUpperCase();
			else
				Value += arr_value[i];
		}
		obj.value = Value;
	}

	function openModal(id){
		document.querySelector("#"+id+" .modal-box").classList.remove('out');
		document.querySelector("#"+id).style.display = "block";
	}
	function closeModal(id){
		document.querySelector("#"+id+" .modal-box").classList.add('out');
		setTimeout(function(){
			document.querySelector("#"+id).style.display = "none";
		}, 450);

		// $('.tab-for-med-modal li:first-child').addClass('active');

		// removeInputValues();
		// removeErrorMessages();
	}
	
	function toggleImgProduct(tag, file){
		document.querySelector('#'+tag).setAttribute('src', file);
		document.querySelector('#'+tag+'-hidden').value = file;
	}
	function toggleImgUser(tag, file){
		document.querySelector('#'+tag).setAttribute('src', file);
		document.querySelector('#'+tag+"-hidden").value = file;
	}

	function displayImgFile(event, id){

		console.log(event);

		var selectedFile = event.target.files[0];
		
		document.getElementById(id).title = selectedFile.name;
		document.getElementById(id+"-hidden").value = selectedFile.name;

		var reader = new FileReader();
		reader.onload = function(event) {
			document.getElementById(id).src = event.target.result;
		};
		reader.readAsDataURL(selectedFile);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////
	function getDateToday(tag)
	{
		var d = new Date();
		var year = d.getFullYear(),
			month = (d.getMonth() + 1).toString(),
			date = (d.getDate()).toString();
		
		if(month < 10)
			month = 0+month;
		if(date < 10)
			date = 0+date

		$(tag).val(year+'-'+month+'-'+date);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////




// ERROR MESSAGE IN FORMS
	function errorAlert(error)
	{
		swal({
			icon : 'error',
			title : error.response.statusText,
			text : error.response.data.message,
			button : {
				text : 'Okay',
			}
		});
	}

	// toggle notification

	$('#icon-bell-parent').click(function(){
		$('#noti-card').toggle();
		
		get_unreadNotifications();
	});

	// get unread notifications

	// function get_unreadNotifications()
	// {
	// 	axios.get('/ajax/notifications?type=unread')
	// 		.then((response) => {

	// 			console.log(response.data);

	// 			display_notiCardContents(response.data);

	// 			if(response.data.length > 0)
	// 				$('.--noti-badge-count').html(response.data.length);
	// 			else
	// 				$('.--noti-badge-count').empty();

	// 		})
	// 		.catch((error) => {

	// 			console.log(error.response);

	// 		});
	// }

	// (function(){ get_unreadNotifications() })();

	// function display_notiCardContents(unreadNotifications)
	// {
	// 	$('#--noti-card-contents').empty();
	// 	unreadNotifications.forEach(function(unreadNotification, index){

	// 		let unread = unreadNotification.data[0];

	// 		// put this in each designated functions

	// 		let message = '';
	// 		switch(unreadNotification.type)
	// 		{
	// 			case 'App\\Notifications\\Product\\OutOfStock':
	// 				message = `
	// 					<div>
	// 						<p class="font-12 text-danger">Is now out of stock.</p>	
	// 					</div>`;
	// 			break;

	// 			case 'App\\Notifications\\Product\\Critical':
	// 				message = `
	// 					<div>
	// 						<p class="font-12 text-danger">Is now in its critical stock of <span class="text-danger">${ unread['stock'] }</span> pcs.</p>	
	// 					</div>`;
	// 			break;
	// 		}

	// 		// append the tags
	// 		$('#--noti-card-contents').append(`
	// 			<div class="noti-content d-flex border-bottom py-2">
	// 				<img src="${ unread['product_img'] }" alt="" class="image-50">
					
	// 				<div class="noti-head-text">
						
	// 					<div class="d-flex justify-content-between">
	// 						<div class="noti-text">
	// 							<h6 class="text-primary">${ unread['generic_name'] } ${ unread['strength'] }</h6>
	// 							<sup class="text-muted noti-sub-type">${ unread['brand_name'] }</sup>	
	// 						</div>
	// 						<label class="text-muted font-10 imp">${ unreadNotification['time_ago'] }</label>	
	// 					</div>
						
	// 					${ message }
						
	// 				</div>
	// 			</div>
	// 		`);

	// 	});

	// 	if(unreadNotifications.length === 0) {
	// 		// append the tags
	// 		$('#--noti-card-contents').append(`
	// 			<p class="text-center text-primary mt-3">No notifications yet.</p>
	// 		`);
	// 	}
	// }


	// general functions
	function setPesoFormatted(number)
	{
		return '&#8369; '+__numberWithComma( parseInt(number).toFixed(2) );
	}

	function setQuantityFormatted(number)
	{
		return __numberWithComma( parseInt(number) )+' pcs.';
	}

	function __numberWithComma(x)
	{
		x = x.toString();
		var pattern = /(-?\d+)(\d{3})/;

		while(pattern.test(x))
			x = x.replace(pattern, "$1,$2");
		return x;
	}

	// set all today date
	$('.--date-today').val( moment().format('YYYY-MM-DD') );