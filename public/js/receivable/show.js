// MODE OF PAYMENT
function modeOfPayment(mode, id){
switch(mode)
{
case 'cash':
	$('#--cheque-details').hide('fast');
break;
case 'cheque':
	$('#--cheque-details').show('fast');
break;
}
}

var orderTransactions = [];

// current order transaction that is active
var order_transaction_id;

// if checkbox is checked...
$('#--bills-html').on('change', '.--bills-checkbox-o', function(){

let checkbox_o = $(this);

order_transaction_id = $(this).data('order-transaction-id');

// if checked
if( checkbox_o.prop('checked') ) {
checkedUX( checkbox_o );
}

// if unchecked
else
{
let is_has_record = orderMedicines.some( (value) => value['order_transaction_id'] == checkbox_o.data('order-transaction-id') );

if(is_has_record) {
	// confirm if want to remove all the data
	swal({
		title: 'Are you sure, you want to delete all the datas you recorded in this bill?',
		text : 'If yes, then click the confirm button to proceed.',
		icon : 'warning',
		buttons : true,
		dangerMode: true,
	})
	.then((confirmation) => {

		// delete order medicines recorded...
		if(confirmation) {
			successAlert({data : { title : 'Successfully Removed', text : 'Successfully removed the data recorded from this bill.', }});
			// 
			uncheckedUX( checkbox_o );
			// remove from order transaction...
			remove_orderMedicines_viaOrderTransactionId_fromJson(checkbox_o);

			removeTotalPayment(current_orderTransaction_Button_object);

		}
		// if not confirmed to delete
		else{
			// 
			checkedUX( checkbox_o );
		}

	});
}

// unchecked ux
else{
	uncheckedUX( checkbox_o );
}
}

// CHECKED AND UNCHECKED UX...
function checkedUX(checkbox_o)
{
// return to dati
checkbox_o.prop('checked', true);
// checked ux
checkbox_o.closest('.--order-transaction-actions')
	.find('.--order-transaction-label')
	.removeClass('text-success bg-white')
	.addClass('text-white bg-success');
// now showing
checkbox_o.closest('.--order-transaction-actions')
	.find('.--order-medicine-payment-btn')
	.show('fast');
}

function uncheckedUX(checkbox_o)
{
// return to dati
checkbox_o.prop('checked', false);
// unchecked UX
checkbox_o.closest('.--order-transaction-actions')
		.find('.--order-transaction-label')
		.removeClass('text-white bg-success')
		.addClass('text-success bg-white');
// not showing
checkbox_o.closest('.--order-transaction-actions')
.find('.--order-medicine-payment-btn')
.hide('fast');

// remove from order transaction...
remove_orderMedicines_viaOrderTransactionId_fromJson(checkbox_o);
}

function remove_orderMedicines_viaOrderTransactionId_fromJson(checkbox_o)
{
// REMOVE ORDER TRANSACTION FROM JSON STORAGE
let removed_orderMedicines_viaOrderTransactionId = orderMedicines.filter( (value) => value['order_transaction_id'] == checkbox_o.data('order-transaction-id') );

console.log( {removed_orderMedicines_viaOrderTransactionId} );

let temp_orderMedicines = [];
$.each( orderMedicines, (key, value) => {

	let is_in_orderTransaction = removed_orderMedicines_viaOrderTransactionId.some( (sub_value) => sub_value['order_transaction_id'] == value['order_transaction_id'] );

	if( !is_in_orderTransaction )
		temp_orderMedicines.push(value);
} )
orderMedicines = temp_orderMedicines;

console.log( orderMedicines );
}
})


// SET TOTAL PAYMENT FOR THIS...
function setTotalPayment(object, orderTransactionId)
{
let local_orderMedicines = orderMedicines.filter( value => value['order_transaction_id'] == orderTransactionId );
let total = local_orderMedicines.reduce( (total, value) => total + value['total_payment_amount'], 0 );

$(object).closest('.--order-transaction-card')
.find('.--order-transaction-total-payment')
.html(`
	<hr>
	<h6 class="text-success">
		${ setPesoFormatted(total) }
	</h6>
	<h6 class="text-muted">Payment</h6>
`);
}

function removeTotalPayment(object)
{
$(object).closest('.--order-transaction-card')
.find('.--order-transaction-total-payment')
.empty();
}

// var orderTransactions = [];
var orderMedicines = [];
// get total quantity to pay for order medicine
$('#order-medicines-payables-html').on('input', '.--order-medicine-quantity', function(){

	let object = $(this);

	setTimeout(function(){

		let order_transaction_id = $(object).data('order-transaction-id');
		let order_medicine_id = $(object).data('order-medicine-id');
		let unit_price = parseInt( $(object).data('unit-price') );

		let quantity = parseInt( $(object).val() );
		let max = parseInt($(object).attr('max'));

		let total_payment_amount = 0;
		if( quantity > max )
		total_payment_amount = unit_price * max;
		else
		total_payment_amount = unit_price * quantity;

		console.log('%c total payment amount', 'color: blue; font-weight: bold');
		console.log(total_payment_amount);

		// each
		let isNotIn_storage = true;
		$.each( orderMedicines, (index, orderMedicine) => {
		if( orderMedicine['order_medicine_id'] == order_medicine_id){
			isNotIn_storage = false;
			// update all...
			orderMedicine['quantity'] = quantity;
			orderMedicine['total_payment_amount'] = total_payment_amount;
		}
		});

		// add new order medicine id
		if(isNotIn_storage)
		{
		orderMedicines.push({
			'order_transaction_id' : order_transaction_id,
			'order_medicine_id' : order_medicine_id,
			'quantity' : quantity,
			'total_payment_amount' : total_payment_amount,
		});
		}

		$(object).closest('.--order-medicine-form')
			.find('.--order-medicine-total-paid-amount')
			.val( total_payment_amount );

		// 
		setTotalPayment( current_orderTransaction_Button_object, order_transaction_id );

		console.log('%c order medicines payee', 'color: green; font-weight: bold');
		console.log( orderMedicines );

	}, 200);

})


var current_orderTransaction_Button_object;
// open details of order medicine
$('.--order-medicine-payment-btn').on('click', function(){

current_orderTransaction_Button_object = $(this);

let order_transaction_id = $(this).data('order-transaction-id');

let payment_order_medicines = orderMedicines.filter( value => value['order_transaction_id'] == order_transaction_id );

// console.log( payment_order_medicines );

let loading = $('#order-medicines-payables-loading'),
content = $('#order-medicines-payables-html');

loading.show();
content.hide();

$('#--receipt-no').empty();

axios.post(`/ajax/transactions/receivables/${ order_transaction_id }/getOrderMedicines`, {
	payment_order_medicines : payment_order_medicines,
})
.then((response) => {
	console.log( response.data );

	loading.hide();
	content.show();

	$('#--receipt-no').html( response.data['receipt_no'] );

	$('#order-medicines-payables-html').html( response.data['order_medicines_payable_html'] );
})
.catch((error) => {
	console.log( error.response );
});

});


// CHECK AND UNCHECK 
$('#order-medicines-payables-html').on('change', '.--medicines-checkbox-o', function(){

if( $(this).prop('checked') )
{
// checked ux
$(this).closest('.--order-medicine-actions')
	.find('.--order-medicine-label')
	.removeClass('text-success bg-white')
	.addClass('text-white bg-success');

// show forms
$(this).closest('.card')
	.find('.card-body')
	.show('fast');
}

else
{

let is_has_record = orderMedicines.some( (value) => value['order_medicine_id'] == $(this).data('order-medicine-id') );

if(is_has_record) {
	// confirm if want to remove all the data
	swal({
		title: 'Are you sure, you want to remove this product in paying?',
		text : 'If yes, then click the confirm button to proceed.',
		icon : 'warning',
		buttons : true,
		dangerMode: true,
	})
	.then((confirmation) => {

		// delete order medicines recorded...
		if(confirmation) {
			successAlert({data : { title : 'Successfully Removed', text : 'Successfully removed the product recorded from this bill.', }});
			// 
			// remove this from storage
			let orderMedicineId = $(this).data('order-medicine-id');
			let temp_orderMedicines = [];
			$.each( orderMedicines, (key, value) => {
				if(value['order_medicine_id'] != orderMedicineId)
					temp_orderMedicines.push( value );
			} );
			orderMedicines = temp_orderMedicines;

			// update the total payment
			setTotalPayment(current_orderTransaction_Button_object, $(this).data('order-transaction-id'));

			// unchecked UX
			$(this).closest('.--order-medicine-actions')
					.find('.--order-medicine-label')
					.removeClass('text-white bg-success')
					.addClass('text-success bg-white');

			// hide forms
			$(this).closest('.card')
				.find('.card-body')
				.hide('fast');

			// remove all values
			$(this).closest('.card')
				.find('.card-body .form-control')
				.val(0);

		}
		// if not confirmed to delete
		else{
			// checked ux
			$(this).closest('.--order-medicine-actions')
				.find('.--order-medicine-label')
				.removeClass('text-success bg-white')
				.addClass('text-white bg-success');

			// show forms
			$(this).closest('.card')
				.find('.card-body')
				.show('fast');
		}

	});
}

// unchecked ux
else{
	// unchecked UX
	$(this).closest('.--order-medicine-actions')
			.find('.--order-medicine-label')
			.removeClass('text-white bg-success')
			.addClass('text-success bg-white');

	// hide forms
	$(this).closest('.card')
		.find('.card-body')
		.hide('fast');

	// remove all values
	$(this).closest('.card')
		.find('.card-body .form-control')
		.val(0);
}
}

})

// $('.--save-payables-btn').on('click', function(){

// 	swal({
// 		title: 'Are you sure, you recorded properly the paid quantity of ordered medicines?',
// 		text : 'If yes, then click the confirm button to proceed.',
// 		icon : 'warning',
// 		buttons : true,
// 		dangerMode: true,
// 	})
// 	.then((confirmation) => {

// 		// delete order medicines recorded...
// 		if(confirmation) {
// 			successAlert({data : { title : 'Success', text : 'Successfully save in temporary storage.', }});

// 			$('#order-medicines-modal').modal('hide');
// 			$('.modal-backdrop.fade.show').remove();
// 		}
// 		// if not confirmed to delete
// 		else{
// 			// 
	
// 		}

// 	});

// });

// *****************************************
// 			SECTION FOR SERVER SIDE
// *****************************************
$(document).ready( function(){

	// FOR PAYMENT FORM
	$('#receivables-form').submit( function(e){

		e.preventDefault();

		swal({
			title: 'Are you sure, you recorded properly the payment for each products?',
			text : 'If yes, then just click the confirm button.',
			icon : 'warning',
			buttons : true,
			dangerMode: true,
		})
		.then((confirmation) => {
			if(confirmation) {
				
				let receiptNo = '';
		if( $('[name="receipt_no"].for-payment').val() !== '' )
			receiptNo = $('[name="receipt_type"].for-payment').val()+$('[name="receipt_no"].for-payment').val();

		axios.post(`/ajax/transactions/receivables/${ accountId }/payment`, {
			'_token' : $('[name="_token"]').val(),

			'receipt_no' : receiptNo,
			'collection_date' : $('[name="collection_date"]').val(),
			'employee_id' : $('[name="employee_id"]').val(),

			'mode_of_payment' : $('[name="mode_of_payment"].for-payment').val(), // cheque or cash
			'cheque_no' : $('[name="cheque_no"]').val(),
			'date_of_cheque' : $('[name="date_of_cheque"]').val(),
			'bank' : $('[name="bank"]').val(),

			// list of bills
			'paid_order_medicines' : orderMedicines,
		})
		.then((response) => {

			console.log(response.data);

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

			console.log(error.response);

			// error message 
			$('.error-message').remove();

			$.each(error.response.data.errors, (key, value) => {
				if(key == 'paid_order_medicines'){
					errorAlert( {
						response : {
							statusText : 'Human Error',
							data : {
								message : 'You haven\'t even selected any products that are going to pay.',
							}
						}
					} );
				}
				else{
					$(`[name="${ key }"].for-payment`).addClass('is-invalid');
					$(`[name="${ key }"].for-payment`).closest('.form-group').append(`
	                    <h6 class="error-message for-payment text-danger font-12 lighter">
	                        ${ value }
	                    </h6>`);
				}
			})

		});

			}
		});


	});

} );