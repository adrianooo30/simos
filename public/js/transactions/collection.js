	
// // for all of the tabs their
// var tabs = ['details', 'bills', 'deposit'];
// // end of for all of the tabs their

$(document).ready(function(){

	$('#deposit-form').submit(function(e){

		e.preventDefault();

		let collection_transaction_id = $('[name="collection_transaction_id"]').val();

		axios.post('/ajax/transactions/collections/'+collection_transaction_id+'/deposit?'+urlParameters, {
			'_token' : $('[name="_token"]').val(),
			'collection_transaction_id' : collection_transaction_id,
			'bank' : $('[name="bank"].for-deposit').val(),
			'date_of_deposit' : $('[name="date_of_deposit"].for-deposit').val(),
			'employee_id' : $('[name="employee_id"].for-deposit').val(),
		})
		.then((response) => {

			console.log(response.data);

			orderDetails(collection_transaction_id);
			// openTab('details');

			// removeInputValues(); // removes all input values
			// removeErrorMessages(); // removes all error message

			// sweet alert
			successAlert(response);

		})
		.catch((error) => {

			errorAlert(error);

			console.log(error.response);

			let errors = error.response.data.errors;

			$.each(errors, (key, value) => {
				$(`[name="${ key }"].for-deposit`).addClass('is-invalid');
				$(`[name="${ key }"].for-deposit`).closest('.form-group').append(`
                    <h6 class="error-message for-deposit text-danger font-12 lighter">
                        ${ value }
                    </h6>`);
			})

		});

	});

});


//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//					SHOWING OF DEEP DETAILS
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

var urlParameters;

function orderDetails(id){

	$('[name="collection_transaction_id"]').val(id);

	let loading = new ModalLoading('#collections-modal');
	loading.setLoading();

	axios.get(`/ajax/transactions/collections/${ id }`)
		.then((response) => {

			loading.removeLoading();

			let collectionDetails = response.data['collections_transaction'];

			$('#tab-1').html( response.data['collections_html'] );

			//ADD DATAS, TITLE
			$('#--title-deposit').html('<h4 class="text-primary lighter">Add Deposit Details</h4>');

			urlParameters = 'add=true';

			// deposit input fields
			if(collectionDetails['deposit'] != null)
			{
				urlParameters = 'update=true&deposit_id='+collectionDetails['deposit']['id']+'';

				//EDIT THE DATA, TITLE
				$('#--title-deposit').html('<h4 class="text-warning lighter">Edit Deposit Details</h4>');

				let bank = collectionDetails['deposit']['bank'],
					dateOfDeposit = collectionDetails['deposit']['date_of_deposit'],
					depositBy = collectionDetails['deposit']['employee']['full_name'],
					depositById = collectionDetails['deposit']['employee_id'];

				$('[name="bank"].for-deposit').val(bank);
				$('[name="date_of_deposit"].for-deposit').val(dateOfDeposit);
				$('[name="employee_id"].for-deposit').val(depositById);

			}

			// load paid bills in datatables
			load_paidBillsDatatables(id);

		});
}

$('#paid-bills-table').on('click', '.--order-medicine-action-btn', function(){

	// alert( $(this).data('collection-transaction-id') );

	$('#paid-order-medicines-modal').modal('show');

	// load paid order medicines
	load_paidOrderMedicinesDatatables(
			$(this).data('order-transaction-id'),
			$(this).data('collection-transaction-id'),
		);
});

// section for alerts
$('#alert-sections').on('click', '.--reload-table-btn', function(){
	// remove the current alert
	$(this).closest('.--alert-box').hide('fast');
	// reload filter tables
	collectionTable.ajax.data;
	// reload datatables
	collectionTable.ajax.reload();
});

collectionTable.on('draw.dt', function(){
	// collections charts
	getCollectionsCharts();
});


// **********************************
// 			FILTERING DATES
// **********************************
var start = moment().startOf('month');
var end = moment().endOf('month');

function cb(start, end) {
	
    // setting the value
    $('[name="from_date"].for-filtering-dates').val( start.format('YYYY-MM-DD') );
    $('[name="to_date"].for-filtering-dates').val( end.format('YYYY-MM-DD') );

    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    // reload filter tables
	collectionTable.ajax.data;
	// reload datatables
	collectionTable.ajax.reload();

	getCollectionsCharts();
}

// set date range picker
$('#reportrange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb);

cb(start, end);