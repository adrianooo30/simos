	
	getDateToday('--delivery-date'); // set the date of deliver in the current date

	var tabs = ['details', 'orders'];

	function toggleStatBtns(id){

		let statOpt = document.querySelectorAll('.stat-opt');

		$.each(statOpt, (key, object) => {
			if(object.id != id)
				object.classList.remove('active');
		});

		$('#'+id).toggleClass('active');
	}

	function statBadge(order_id, status) {

		if(status == "Delivered")
		{
			openModal('delivery-modal');
			getDateToday('--delivery-date');

			$('input[name="order_transaction_id"].for-delivery').val(order_id); //put the value of order_transaction
		}

		if(status != "Delivered")
			updateStatus(order_id, status);

	}

	function updateStatus(order_id, status)
	{
		axios.patch(`/ajax/order/track/status/${ order_id }`,{
			'status' : status
		})
		.then((response) => {
			// load tracking orders datatables
			trackTable.ajax.reload();
		});
	}

	$(document).ready(function(){

		$('#delivery-form').submit(function(e){

			var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-delivery');
			var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-delivery');

			e.preventDefault();

			let order_transaction_id = $('input[name="order_transaction_id"].for-delivery').val();

			let receipt_no = '';
			if($('input[name="receipt_no"]').val() != '')
				receipt_no = $('select[name="receipt_type"]').val()+$('input[name="receipt_no"]').val();

			axios.post('/ajax/order/track/delivery/'+order_transaction_id, {
				'_token' : $('input[name="_token"]').val(),
				'order_transaction_id' : $('input[name="order_transaction_id"].for-delivery').val(),
				'receipt_no' : receipt_no,
				'delivery_date' : $('input[name="delivery_date"].for-delivery').val(),
				'employee_id' : document.querySelector('[name="employee_id"].for-delivery').dataset.employeeId
			})
			.then((response) => {

				console.log(response);

				allErrorMessagesDOM.forEach((element) => element.remove() );
				allInputFieldsDOM.forEach((element) => {
					// REMOVE IF EMPLOYEE IF
					$(element).attr('name') !== 'employee_id' ? $(element).val('') : '';
				});

				updateStatus(order_transaction_id, 'Delivered');

				trackTable.ajax.reload();

				closeModal('delivery-modal');

				// sweet alert
				swal({
					title : response.data['title'],
					text : response.data['text'],
					icon : 'success',
					button : {
						text : 'Okay',
						value : true,
						visible : true,
						className : '',
						closeModal : true,
					},
				});

			})
			.catch((error) => {

				errorAlert(error);
				allErrorMessagesDOM.forEach((element) => element.remove());

				// display errors
				$.each(error.response.data['errors'], (key, value) => {
					$('[name="'+key+'"]').after(`<label class="error-message for-delivery  imp">${ value }</label>`);
				});
			});

		});

	});

	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//					SHOWING OF DEEP DETAILS
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	function orderDetails(id){
		let orderDetailsDisplay = document.querySelector('#--order-details');
		let orderLoading = document.querySelector('#--order-loading');

		orderDetailsDisplay.style.display = "none";
		orderLoading.style.display = "block";

		axios.get(`/ajax/order/track/${ id }`)
			.then((response)  => {

				orderLoading.style.display = "none";
				orderDetailsDisplay.style.display = "block";

				let orderDetails = response.data;

				let profileImg = document.getElementById('--profile-img'),
					accountName = document.getElementById('--account-name'),
					type = document.getElementById('--type'),
					total = document.getElementById('--total'),
					orderDate = document.getElementById('--order-date'),
					psr = document.getElementById('--psr');

					profileImg.setAttribute('src', orderDetails['account']['profile_img']);
					accountName.innerHTML = orderDetails['account']['account_name'];
					type.innerHTML = '<i class="ti-home"></i> '+orderDetails['account']['type'];
					total.innerHTML = orderDetails['total_cost_format'];
					orderDate.innerHTML = orderDetails['order_date'];
					psr.innerHTML = orderDetails['employee']['full_name'];


				// load ordered medicine
				load_orderMedicineDatatables(id);

			});
	}