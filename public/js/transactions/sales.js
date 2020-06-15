var salesId;
function orderDetails(id)
{
	salesId = id;

	let loading = new ModalLoading('#sales-modal');
	loading.setLoading();

	axios.get(`/ajax/transactions/sales/${ id }`)
		.then((response) => {

			console.log(response.data);

			loading.removeLoading();

			let salesTransaction = response.data['sales_transaction'];

			// sales details
			$('#tab-1').html(response.data['sales_transaction_html']);

			// load ordered medicine table
			load_orderMedicineDatatables(id);

			// hmmmmm.....
			forReturningOfProducts(response);
	        
	        // COLLECTION TRANSACTION
			// if(salesTransaction['collection_transaction'].length > 0) {
			// 	$('[href="#tab-3"]').show();
			// 	// load collections table
			// 	// load_collectionsDatatables(id);
			// }
			// else{
			// 	$('[href="#tab-3"]').hide();
			// }
			
		})
		.catch((error) => {
			console.log(error.response);
		})
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
	salesTable.ajax.data;
	// reload datatables
	salesTable.ajax.reload();
}

// reload button for datatable...
$('#alert-sections').on('click', '.--reload-table-btn', function(){

	$(this).closest('.--alert-box').hide('fast');

	// reload filter tables
	salesTable.ajax.data;
	// reload datatables
	salesTable.ajax.reload();

});

salesTable.on('draw.dt', function(){

	// reloading filter dates
	qoutasTable.ajax.data;
	// reload datatables
	qoutasTable.ajax.reload();

	// 
	getSalesCharts();

});

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