// SET THE DATE TODAY
getDateToday('#--collection-date');

// MODE OF PAYMENT
function modeOfPayment(mode, id){
	switch(mode)
	{
		case 'cash':
			document.getElementById(id).style.display = "none";
		break;

		case 'cheque':
			document.getElementById(id).style.display = "flex";
		break;
	}
}



	// BILL TO PAID
var billsToPaid = [];




// SECTION FOR CLIENT SIDE
$(document).ready(function(){

	// INPUT VALUE MUST NOT GREATER THAN MAXIMUM VALUE
	$('#bills-table').on('keyup', '[name="amount"].for-payment', function(){
		// update index value
		if ( parseInt($(this).attr('max')) < parseInt( $(this).val() ) ) {
			// 
			$(this).val( $(this).attr('max') ).addClass('text-primary');
			// SET TYPE OF PAYMENT TO FULL
			$(this).closest('tr').find('[name="type_of_payment"]').val('full');
			// SET TO READONLY
			$(this).attr('readonly', '');
		}

		else if(parseInt($(this).val()) < 1)
			$(this).val('');
	});

	// CLIENT SIDE - SELECT BOX

	// PARENT CHECKBOX
	$('#--select-all-checkbox-o').on('change', function(){
		// NODE SUB CHECKBOX
		let subCheckboxO = $('.sub-checkbox-o');
		// CHECKED OR UNCHECKED ALL SUB CHECKBOX
		subCheckboxO.prop('checked', $(this).prop('checked'));

		// LOOP THROUGH SUB CHECKBOX O
		$.each( subCheckboxO, ( index, object ) => {
			enable_or_disable( $(object) );
		});
	});

	// ENABLE OR DISABLE - CERTAIN CHECKBOX
	$('#bills-table').on('change', '.sub-checkbox-o', function(){
		// CERTAIN SUB CHECKBOX
		enable_or_disable( $(this) );
	});

	// ENABLE OR DISABLE -CERTAIN CHECKBOX
	function enable_or_disable(object)
	{
		// CHECKED OR UNCHECKED
		if( $(object).prop('checked') ) {
			// ENABLE TEXTBOX
			$(object).closest('tr').find('.for-payment').removeAttr('disabled');
			// INSERT TO JSON
			insert_or_update_to_container( $(object) );
		}
		else {
			// REMOVE THE INPUT VALUE
			$(object).closest('tr').find('.for-payment').attr('disabled', '').val('');
			// 
			remove_unchecked_checkbox_in_container( object );
		}

		// CHECK OR UNCHECK
		check_or_uncheck_parent_checkbox();
	}

	// billsToPaid variable is in top
	function insert_or_update_to_container( object )
	{
		// call every .5s
		setTimeout(function() {
			//
			let id = $( object ).attr('data-order-transaction-id');
			let type_of_payment = $( object ).closest('tr').find('[name="type_of_payment"].for-payment').val();
			let amount = $( object ).closest('tr').find('[name="amount"].for-payment').val();

			// CHECK IF IN CONTAINER ALREADY - AND UPDATE VALUE
			let notInContainer = true;
			$.each( billsToPaid, (index, billToPaid) => {
				// BILL ID
				if( billToPaid['id'] == id ) {
					notInContainer = false;

					// UPDATE THE VALUE
					billToPaid['type_of_payment'] = type_of_payment;
					billToPaid['amount'] = parseInt( amount );
				}
			});
			// IF NOT IN CONTAINER
			if(notInContainer) {
				billsToPaid.push({
					'id' : parseInt( id ),
					'type_of_payment' : type_of_payment,
					'amount' : parseInt( amount ),
				});
			}

			console.table( billsToPaid );
		}, 500);
	}





	// *********************************************************************//
	// ********************* ONKEYUP INPUT FIELDS **************************//
	// *********************************************************************//

	// TYPE OF PAYMENT
	$('#bills-table').on('change', '[name="type_of_payment"].for-payment', function(){
		// update index value
		insert_or_update_to_container( this );
	});

	// AMOUNT TO PAY
	$('#bills-table').on('keyup', '[name="amount"].for-payment', function(){
		// update index value
		insert_or_update_to_container( this );
	});





	// *********************************************************************//
	//					VERIFICATIONS ATA TO HEHEHE
	// *********************************************************************//

	// SET FULL PAYMENT BILL
	$('#bills-table').on('change', '[name="type_of_payment"].for-payment', function(){
		// update index value
		let amountTextField = $(this).closest('tr').find('[name="amount"].for-payment');
		// 
		switch( $(this).val() )
		{
			case 'full':
				amountTextField.val( amountTextField.attr('max') ).attr('readonly', '').addClass('text-primary');
			break;

			case 'partial':
				amountTextField.val('').removeAttr('readonly').removeClass('text-primary');
			break;
		}
	});

	// CHECK OR UNCHECK PARENT CHECKBOX
	function check_or_uncheck_parent_checkbox()
	{
		// PARENT CHECKED OR UNCHECKED
		if( $('.sub-checkbox-o').length === $('.sub-checkbox-o:checked').length )
			$('#--select-all-checkbox-o').prop('checked', true);
		else
			$('#--select-all-checkbox-o').prop('checked', false);

		// track if has checked
		track_if_has_checked_checkboxes();
	}

	// TRACK IF HAS CHECKED - CHECKBOX
	function track_if_has_checked_checkboxes()
	{
		// REMOVE BILLS TO PAID
		if( $('.sub-checkbox-o:checked').length === 0 )
			billsToPaid = [];
	}

	function remove_unchecked_checkbox_in_container( object )
	{
		// turning or id into false
		$.each( billsToPaid, (i, billToPaid) => {
			// BILL TO PAID
			if( billToPaid['id'] == $(object).attr('data-order-transaction-id') ){
				billToPaid['id'] = null;
			}
		});
		// refresh the container
		billsToPaid = billsToPaid.filter( billToPaid => { return billToPaid['id'] != undefined } );

		console.table(billsToPaid);
	}


	// *********************************************************************//
	//					VERIFICATIONS ATA TO HEHEHE
	// *********************************************************************//














}); // end of document.ready

// *****************************************
// 			SECTION FOR SERVER SIDE
// *****************************************
$(document).ready( function(){

	// FOR PAYMENT FORM
	$('#payment-form').submit( function(e){

		let allErrorMessagesDOM = document.querySelectorAll('.error-message.for-payment');
		let allInputFieldsDOM = document.querySelectorAll('.input-box .for-payment');

		e.preventDefault();

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
			'bills' : billsToPaid,
		})
		.then((response) => {

			console.log(response.data);

			// REPLACE A SUCCESS COLLECITON RECORDED
			$('#--account-receivables').html(`
				<div class="container m-4">
					<h3 class="text-center text-muted lighter"> <i class="fas fa-spinner fa-spin"></i> Please wait. Returning to parent page.</h3>
				</div>
			`);

			window.location.assign('/transactions/receivables/index');

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

			try{
				console.log(error.response.data.errors);
				// remove all errors
				allErrorMessagesDOM.forEach((element) => element.remove());
				// loop through errors
				$.each(errors, (key, value) => {
					$('[name="'+key+'"]').after('<label class="error-message for-payment">'+value+'</label>');

					if(key == 'bills'){
						isNoSelected(); // checked if no selected
					}
				});

			}catch(error){
				console.warn(error);
			}

		});

	});

} );