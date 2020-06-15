		
	// for all of the tabs their
	var tabs = ['details', 'bills', 'deposit'];
	// end of for all of the tabs their

	$(document).ready(function(){

		$('#deposit-form').submit(function(e){

			e.preventDefault();

			let collection_transaction_id = $('input[name="collection_transaction_id"]').val();

			axios.post('/ajax/transactions/collections/'+collection_transaction_id+'/deposit?'+urlParameters, {
				'_token' : $('input[name="_token"]').val(),
				'collection_transaction_id' : collection_transaction_id,
				'bank' : $('input[name="bank"].for-deposit').val(),
				'date_of_deposit' : $('input[name="date_of_deposit"].for-deposit').val(),
				'employee_id' : $('select[name="employee_id"].for-deposit').val(),
			})
			.then((response) => {

				console.log(response.data);

				getCollectionViaAjax();
				orderDetails(collection_transaction_id);
				openTab('details');

				removeInputValues(); // removes all input values
				removeErrorMessages(); // removes all error message

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

				let errors = error.response.data.errors;

				removeErrorMessages(); // removes all error messages

				$.each(errors, (key, value) => {
					let DOM = document.querySelector('[name="'+key+'"]');
					DOM.insertAdjacentHTML('afterend', '<label class="error-message for-deposit">'+value+'</label>');
				});

			});

		});

	});


	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//					SHOWING OF DEEP DETAILS
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	var collectionDetails;
	var urlParameters;

	function orderDetails(id){

		$('.--is-cheque').hide();
		$('.--has-deposit').hide(); //auto hide the deposit details
		$('input').val('');//return back the inputs into null
		$('select').val('');//return back the inputs into null
		removeErrorMessages(); // removes all error messages

		getDateToday('--deposit_date');// set the date on current date

		$('input[name="collection_transaction_id"]').val(id);

		let orderDetailsDisplay = document.querySelector('#--order-details');
		let orderLoading = document.querySelector('#--order-loading');

		orderDetailsDisplay.style.display = "none";
		orderLoading.style.display = "block";

		axios.get(`/ajax/transactions/collections/${ id }`)
			.then((response) => {

				orderLoading.style.display = "none";
				orderDetailsDisplay.style.display = "block";

				collectionDetails = response.data;

				let profileImg = document.getElementById('--profile-img'),
					accountName = document.getElementById('--account-name'),
					type = document.getElementById('--type'),
					modeOfPayment = document.getElementById('--mode-of-payment'),
					receiptNo = document.getElementById('--receipt-no'),
					amount = document.getElementById('--amount-collected'),
					collectionDate = document.getElementById('--date-of-collection'),
					receiptNos = document.getElementById('--receipt-nos'),
					collectedBy = document.getElementById('--collected-by');

					profileImg.setAttribute('src', collectionDetails['account']['profile_img']);
					accountName.innerHTML = collectionDetails['account']['account_name'];
					type.innerHTML = '<i class="ti-home"></i> '+collectionDetails['account']['type'];
					receiptNo.innerHTML = collectionDetails['receipt_no'];
					amount.innerHTML = collectionDetails['collected_amount_format'];
					collectionDate.innerHTML = collectionDetails['collection_date'];
					collectedBy.innerHTML = collectionDetails['employee']['full_name'];

					//mode of payment
					if(collectionDetails['mode_of_payment'] == 'cheque')
						modeOfPayment.innerHTML = '<span class="badge badge-success badge-sm text-white">Cheque</span>';
					else // this is always for Cash
						modeOfPayment.innerHTML = '<span class="badge badge-success badge-sm text-white">Cash</span>';


					//ADD DATAS, TITLE
					$('#--title-deposit').html('<h4 class="text-primary lighter">Add Deposit Details</h4>');

					urlParameters = 'add=true';

					if(collectionDetails['deposit'] != null)
					{
						$('.--has-deposit').show(); // show the deposit details

						urlParameters = 'update=true&deposit_id='+collectionDetails['deposit']['id']+'';

						//EDIT THE DATA, TITLE
						$('#--title-deposit').html('<h4 class="text-warning lighter">Edit Deposit Details</h4>');

						let bank = collectionDetails['deposit']['bank'],
							dateOfDeposit = collectionDetails['deposit']['date_of_deposit'],
							depositBy = collectionDetails['deposit']['employee']['full_name'],
							depositById = collectionDetails['deposit']['employee_id'];

						$('#--bank').html(bank);
						$('#--date-of-deposit').html(dateOfDeposit);
						$('#--deposit-by').html(depositBy);

						$('input[name="bank"].for-deposit').val(bank);
						$('input[name="date_of_deposit"].for-deposit').val(dateOfDeposit);
						$('select[name="employee_id"].for-deposit').val(depositById);

					}

					// cheque-no, date-of-cheque, cheque-bank

					if(collectionDetails['cheque'] != null)
					{
						$('.--is-cheque').show();

						let chequeNo = collectionDetails['cheque']['cheque_no'],
							dateOfCheque = collectionDetails['cheque']['date_of_cheque'],
							chequeBank = collectionDetails['cheque']['bank'];

						$('#--cheque-no').html(chequeNo);
						$('#--date-of-cheque').html(dateOfCheque);
						$('#--cheque-bank').html(chequeBank);
					}

				// load paid bills in datatables
				load_paidBillsDatatables(id);

			});
	}


	// form
	$('#--date-filter').on('submit', function( event ){

		// prevent the default on submit
		event.preventDefault();
		// reload filter tables
		collectionTable.ajax.data;
		// reload datatables
		collectionTable.ajax.reload();

	});