
	// for all of the tabs their
	var tabs = ['details', 'orders', 'collections', 'returnee'];
	// end of for all of the tabs their

	getDateToday('#--returnee-date');

	var salesId;
	var selectedReturnees = new Array();

	var appendStatuses = '';

	function orderDetails(id) {

		$('#--is-has-error').hide();

		salesId = id;

		$('#--order-details').hide();
		$('#--order-loading').show();

		axios.get(`/ajax/transactions/sales/${ id }`)
			.then((success) => {

					$('#--order-details').show();
			$('#--order-loading').hide();

			console.log(success.data);

			let orderDetails = success.data;

				receipt_no = $('#--receipt-no');
				deliveryDate = $('#--delivery-date');
				deliveredBy = $('#--delivered-by');

				$('#--profile-img').attr('src', orderDetails['account']['profile_img']);
				$('#--account-name').html(orderDetails['account']['account_name']);
				$('#--type').html(orderDetails['account']['type']);
				$('#--total').html(orderDetails['total_cost_format']);

				// total bill
				if(orderDetails['total_bill'] > 0){
					$('#--is-has-bill').show(); 
					$('#--bill').html(orderDetails['total_bill_format']);
				}
				else
					$('#--is-has-bill').hide();

				// excess payment
				if(orderDetails['excess_payment'] > 0){
					$('#--is-has-excess').show(); 
					$('#--excess').html(orderDetails['excess_payment_format']);
				}
				else
					$('#--is-has-excess').hide();

				$('#--order-date').html(orderDetails['order_date']);
				$('#--psr').html(orderDetails['employee']['full_name']);

				$('#--receipt-no').html(orderDetails['deliver_transaction']['receipt_no']);
				$('#--delivery-date').html(orderDetails['deliver_transaction']['delivery_date']);
				$('#--delivered-by').html(orderDetails['employee']['full_name']);

				// set status 
				$('#--status').html(orderDetails['status']);


				// load ordered medicine table
				load_orderMedicineDatatables(id);
		        
				if(orderDetails['collection_transaction'].length > 0) {
					$('#collection-tab-btn').show();

					// load collections table
					load_collectionsDatatables(id);
				}
				else
					$('#collection-tab-btn').hide();

		})
		.catch((error) => {
			console.log(error.response);
		})

		// call interface for return products
		// forReturnProducts();
	}

	function setCurrentDate_and_LastDate_of_Month()
	{
		axios.get('/ajax/transactions/sales/currentDate')
			.then((response) => {
				console.log( response.data );

				$('[name="start_date"]').val( response.data['start_date'] );
				$('[name="end_date"]').val( response.data['end_date'] );

				// load the datatables
				load_salesDatatables();
			});
	}

	// set start date and last date of month first
	setCurrentDate_and_LastDate_of_Month();

	// form
	$('#--date-filter').on('submit', function( event ){

		event.preventDefault();

		// reload filter tables
		salesTable.ajax.data;
		// reload datatables
		salesTable.ajax.reload();

	});