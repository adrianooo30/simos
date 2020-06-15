function toggleStatBtns(id){

	let statOpt = document.querySelectorAll('.stat-opt');

	$.each(statOpt, (key, object) => {
		if(object.id != id)
			object.classList.remove('active');
	});

	$('#'+id).toggleClass('active');
}

function statBadge(order_id, status) {

	if(status == "Delivered") {
		
		set_dateToday('[name="delivery_date"]');

		$('[name="order_transaction_id"].for-delivery').val(order_id); //put the value of order_transaction
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
	})
	.catch((error) => {
		errorAlert(error);
	})
}

$(document).ready(function(){

	// DELIVERY FORM
	$('#delivery-form').submit(function(e){

		e.preventDefault();

		let order_transaction_id = $('[name="order_transaction_id"].for-delivery').val();

		let receipt_no = '';
		if($('[name="receipt_no"].for-delivery').val() != '')
			receipt_no = $('[name="receipt_type"].for-delivery').val() + $('[name="receipt_no"].for-delivery').val();

		axios.post(`/ajax/order/track/delivery/${ order_transaction_id }`, {
			'_token' : $('[name="_token"]').val(),
			'receipt_no' : receipt_no,
			'delivery_date' : $('[name="delivery_date"].for-delivery').val(),
			'employee_id' : $('[name="employee_id"].for-delivery').val()
		})
		.then((response) => {
			console.log(response);

			// update status also
			updateStatus(order_transaction_id, 'Delivered');

			// $('#delivery-modal').modal('hide');

			trackTable.ajax.reload();

			// sweet alert
			successAlert(response);
		})
		.catch((error) => {

			console.log(error.response);

			errorAlert(error);

			// display errors
			let errors = error.response.data.errors;
			$.each(errors, (key, value) => {
				$(`[name="${ key }"].for-delivery`).addClass('is-invalid');
				$(`[name="${ key }"].for-delivery`).closest('.form-group').append(`
                    <h6 class="error-message for-delivery text-danger font-12 lighter">
                        ${ value }
                    </h6>`);
			})
		});

	});

});

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//					SHOWING OF DEEP DETAILS
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function orderDetails(id)
{
	let loading = new ModalLoading('#track-modal');
	loading.setLoading();

	axios.get(`/ajax/order/track/${ id }`)
		.then((response) => {

			loading.removeLoading();

			let orderDetails = response.data;

			$('#tab-1').html(orderDetails['order_details_html']);

			// load ordered medicine in datatables
			load_orderMedicineDatatables(id);

		})
		.catch((error) => {
			errorAlert(error);
		});
}

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
	trackTable.ajax.data;
	// reload datatables
	trackTable.ajax.reload();
}

// set date range picker
$(document).ready(function(){

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
    
});

cb(start, end);