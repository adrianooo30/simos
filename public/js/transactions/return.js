var super_orderMedicines;

// for return
function forReturningOfProducts(response)
{
	orderMedicines = [];
	orderBatchNos = [];

	super_orderMedicines = response.data['sales_transaction']['order_medicine'];

	// console.log(super_orderMedicines);

	// sales details
	$('#return-product-form').html(response.data['return_product_html']);

}

// LOGICS

// DISABLE AND ENABLE
$('#tab-4').on('change', '.--order-batch-no-checkbox', function(){

	if( $(this).prop('checked') ){
		$(this).closest('.--parent-order-batch-no')
				.find('.--order-batch-no-forms')
				.show('fast');

		// 
		$(this).closest('.--parent-order-batch-no')
				.find('.form-control.for-return')
				.attr('required', '')
				.val('')
	}
	else{
		// 
		$(this).closest('.--parent-order-batch-no')
				.find('.--order-batch-no-forms')
				.hide('fast');
		// 
		$(this).closest('.--parent-order-batch-no')
				.find('.form-control.for-return')
				.removeAttr('required')
				.val('')
		// 
		remove_batch_number_to_return( $(this).attr('data-order-medicine-id'), $(this).attr('data-order-batch-no-id') );
	}

	// *********************************************
	// 		ORDER MEDICINE SUMMARY ENABLE - DISABLE
	// *********************************************
	let totalCheckbox =	$(this).closest('.--parent-order-medicine').find('[type="checkbox"]:checked').length;
	if(totalCheckbox > 0)
	{
		$(this).closest('.--parent-order-medicine')
			.find('.--order-medicine-summary .form-control')
			.attr('required', '')
			.removeAttr('disabled');

		// show fast
		$(this).closest('.--parent-order-medicine')
			.find('.--order-medicine-summary')
			.show('fast');
	}
	else{
		$(this).closest('.--parent-order-medicine')
				.find('.--order-medicine-summary .form-control')
				.attr('disabled', '')
				.removeAttr('required')
				.val('');

		// show fast
		$(this).closest('.--parent-order-medicine')
			.find('.--order-medicine-summary')
			.hide('fast');
	}
});

function remove_order_medicine_to_return( order_medicine_id )
{
	let temp_orderMedicines = [];
	$.each( orderMedicines, (index, orderMedicine) => {
		if( parseInt(orderMedicine['id']) != order_medicine_id)
			temp_orderMedicines.push( orderMedicine );
	});

	orderMedicines = temp_orderMedicines;

	console.table(temp_orderMedicines);
}

function remove_batch_number_to_return(order_medicine_id, order_batch_no_id)
{
	let temp_orderBatchNos = [];
	$.each( orderBatchNos, (index, orderBatchNo) => {
		if( orderBatchNo['order_batch_no_id'] != order_batch_no_id){
			// push order batch numbers
			temp_orderBatchNos.push(orderBatchNo);
		}
	});
	// lipat giraray
	orderBatchNos = temp_orderBatchNos;

	// auto update the order medicine total quantity
	set_returnedOrderMedicine(order_medicine_id);

	set_initialQuantity_return(order_medicine_id);

	console.log('%c order batch number returned - returned quantity', 'color: green; font-weight: bold');
	console.log( orderBatchNos );
}


var orderMedicines = [];
var orderBatchNos = [];
// *******************************************************

// RETURNED QUANTITY
// $('#tab-4').on('keyup', '.--returned-qty-for-batch-no', function(){
// 	set_returnedQty_batchNo( $(this) );
// });

$('#tab-4').on('input', '.--returned-qty-for-batch-no', function(){

	let thisInput = $(this);

	setTimeout( function(){
		let order_medicine_id = parseInt( thisInput.attr('data-order-medicine-id') ),
		order_batch_no_id = parseInt( thisInput.attr('data-order-batch-no-id') ),
		return_qty_for_batch_no = parseInt(thisInput.val());

		// each
		let isNotIn_storage = true;
		$.each( orderBatchNos, (index, orderBatchNo) => {
			if( orderBatchNo['order_batch_no_id'] == order_batch_no_id){
				isNotIn_storage = false;
				orderBatchNo['quantity'] = return_qty_for_batch_no;
			}
		});

		// add new order medicine id
		if(isNotIn_storage)
		{
			orderBatchNos.push({
				'order_medicine_id' : order_medicine_id,
				'order_batch_no_id' : order_batch_no_id,
				'quantity' : return_qty_for_batch_no,
				'reason' : '',
			});
		}

		// console.log('%c order batch number returned - returned quantity', 'color: green; font-weight: bold');
		// console.log( orderBatchNos );

		// set returned order medicine
		set_returnedOrderMedicine(order_medicine_id);

		set_initialQuantity_return(order_medicine_id);
	},  500 );
});

// RETURNED REASON
$('#tab-4').on('change', '.--returned-reason-for-batch-no', function(){

	let order_medicine_id = parseInt( $(this).attr('data-order-medicine-id') ),
		order_batch_no_id = parseInt( $(this).attr('data-order-batch-no-id') ),
		reason = $(this).val();

	// each
	let isNotIn_storage = true;
	$.each( orderBatchNos, (index, orderBatchNo) => {
		if( orderBatchNo['order_batch_no_id'] == order_batch_no_id){
			isNotIn_storage = false;
			orderBatchNo['reason'] = reason;
		}
	});

	// add new order medicine id
	if(isNotIn_storage)
	{
		orderBatchNos.push({
			'order_medicine_id' : order_medicine_id,
			'order_batch_no_id' : order_batch_no_id,
			'quantity' : '',
			'reason' : reason,
		});
	}

	// console.log('%c order batch number returned - reason of return', 'color: green; font-weight: bold');
	// console.log( orderBatchNos );

});

// set initial return quantity
function set_initialQuantity_return(order_medicine_id)
{
	let order_batch_nos = orderBatchNos.filter((value, key) => order_medicine_id == value['order_medicine_id']),
		total_return_quantity;

		total_return_quantity = 0;
		$.each( order_batch_nos, (key, value) => {
			total_return_quantity += value['quantity'];
		} );

		// INITIAL QUANTITY
		$(`.--order-medicine-summary-${ order_medicine_id }`)
			.find('.--returned-qty-for-order-medicine')
			.val(total_return_quantity);

		// FREE QUANTITY
		$(`.--order-medicine-summary-${ order_medicine_id }`)
			.find('.--returned-qty-free-for-order-medicine')
			.val(0);
}

function set_returnedOrderMedicine(order_medicine_id)
{
	let order_batch_nos = orderBatchNos.filter((value, key) => order_medicine_id == value['order_medicine_id']),
		total_return_quantity;

		total_return_quantity = 0;
		$.each( order_batch_nos, (key, value) => {
			total_return_quantity += value['quantity'];
		} );

	let isNotIn_storage = true;
	// order medicine update
	$.each( orderMedicines, (index, orderMedicine) => {
		if( orderMedicine['id'] == order_medicine_id){
			isNotIn_storage = false;
			orderMedicine['total_return_quantity'] = total_return_quantity;
		}
	});

	if(isNotIn_storage)
	{
		orderMedicines.push({
			'id' : order_medicine_id,
			'total_return_quantity' : total_return_quantity,
			'free' : 0,
			'status' : '',
		});
	}

	// // set the max quantity
	// $(`.--order-medicine-summary-${ order_medicine_id }`)
	// 	.find('.--returned-qty-for-order-medicine')
	// 	.attr('max', total_return_quantity);

	// console.log('%c order medicine returned - total return quantity', 'color: green; font-weight: bold');
	// console.log(orderMedicines);

}


// ********************************************************
// ********************************************************
// ********************************************************

// initial quantity
// $('#tab-4').on('keyup', '.--returned-qty-for-order-medicine', function(){

// 	let order_medicine_id = parseInt( $(this).attr('data-order-medicine-id') );
// 	let order_medicine = orderMedicines.find((value) => value['order_medicine_id'] == order_medicine_id);
// 	let super_orderMedicine = super_orderMedicines.find((value) => value['id'] == order_medicine_id);

// 	if( order_medicine['total_return_quantity'] > (
// 			super_orderMedicine['quantity'] - parseInt( super_orderMedicine['free'] )
// 		) && parseInt(super_orderMedicine['free']) == 0)
// 	{
// 		// find --returned-qty-free-for-order-medicine
// 		$(this).closest('.--order-medicine-summary')
// 				.find('.--free-form-group')
// 				.attr('data-show', true)
// 				.show();

// 		$(this).close
// 	}
// 	else
// 		// find --returned-qty-free-for-order-medicine
// 		$(this).closest('.--order-medicine-summary')
// 				.find('.--free-form-group')
// 				.attr('data-show', false)
// 				.hide();

// });


// free quantity
$('#tab-4').on('keyup', '.--returned-qty-free-for-order-medicine', function(){
	set_returnedFree_orderMedicine( this );
});

function set_returnedFree_orderMedicine(object){
	let order_medicine_id = parseInt( $(object).attr('data-order-medicine-id') );
	let order_medicine = orderMedicines.find((value) => value['id'] == order_medicine_id);

	if( parseInt( $(object).val() ) >= order_medicine['total_return_quantity'] )
		$(object).val(1);

	// set initial quantity
	$(object).closest('.--order-medicine-summary')
			.find('[name="return_quantity"]')
			.val( order_medicine['total_return_quantity'] - parseInt($(object).val()) );

	// set return quantity and free...
	order_medicine['free'] = parseInt( $(object).val() );

	console.log('%c order_medicine - free', 'color: blue; font-weight: bold, font-size: 20px;');
	console.log(orderMedicines);
}

// set status for ordered medicine
$('#tab-4').on('change', '.--returned-status-for-order-medicine', function() {
	// returned order medicine id...
	let order_medicine_id = parseInt( $(this).attr('data-order-medicine-id') );
	let order_medicine = orderMedicines.find((value) => value['id'] == order_medicine_id);

	// status
	order_medicine['status'] = $(this).val();
})


// submit returns in back end
$('#return-product-form').on('submit', function(e){

	e.preventDefault();

	$.each( orderMedicines, (key, orderMedicine)  => {

		let product = super_orderMedicines.find((value) => value['id'] == orderMedicine['id']);
		orderMedicine['product_id'] = product['product_id'];

		// set initial value
		orderMedicine['total_return_quantity'] -= orderMedicine['free'];
		orderMedicine['returned_date'] = moment().format('YYYY-MM-DD');

		// order batch numbers
		let order_batch_nos = [];
		$.each( orderBatchNos, (_key, orderBatchNo) => {
			if(orderMedicine['id'] == orderBatchNo['order_medicine_id'])
				order_batch_nos.push( orderBatchNo );
		})

		orderMedicine['order_batch_nos'] = order_batch_nos;
	})

	console.log('%c Returned Product', 'color: blue; font-weight: bold, font-size: 20px;');
	console.log({orderMedicines});

	if(verify_and_validate(orderMedicines))
	{
		swal({
			title: 'Are you sure, you recorded properly the returned products?',
			text : 'If yes, then just click the confirm button.',
			icon : 'warning',
			buttons : true,
			dangerMode: true,
		})
		.then((confirmation) => {
			if(confirmation) {
				axios.post(`/ajax/inventory/returns`, {
					'order_transaction_id' : salesId,
					'returned_order_medicines' : orderMedicines,
				})
					.then((response) => {

						orderDetails(salesId);
						console.log( response.data );

						// reload sales
						salesTable.ajax.data;
						salesTable.ajax.reload();
						// reload qoutas
						qoutasTable.ajax.data;
						qoutasTable.ajax.reload();
						// reload sales charts
						getSalesCharts();

						successAlert(response);

					})
					.catch((error) =>  {

						errorAlert(error);

						console.log(error.response);

					});
			}// END - willChange
		});
	}

});


// verifications and validations
function verify_and_validate(returningOrderMedicines)
{
	console.log({returningOrderMedicines});

	let error = {
		response : {
			statusText : 'Human Error',
			data : {
				message : 'You haven\'t even selected any products to return.',
			}
		}
	};

	if( orderBatchNos.length > 0 )
		return true;
	else{
		errorAlert(error);
		return false;
	}

	// notifications
	// alerts...
	// errors
}