var productIn_Table;

function load_productsIn_Datatables()
{
	productIn_Table = $('#product-in-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		// ajax : '/ajax/inventory/products',

		// order : [ [1, 'desc'] ],

		destroy : true,

		responsive : true,

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '25%', 'targets' : [ 2 ] },
		],

		columns : [
			{
				data : '',
				name : '',
				orderable : false,
				searchable : false,
			},
			{
				data : 'product_img',
				name : 'product_img',
				orderable : false,
				searchable : false,
			},
			{
				data : 'product_name',
				name : 'generic_name',
			},
			{
				data : 'date_added',
				name : 'date_added',
			},
			{
				data : 'batch_no',
				name : 'batch_no',
			},
		]
	});
}


var productOut_Table;

function load_productsOut_Datatables()
{
	productOut_Table = $('#product-out-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		// ajax : '/ajax/inventory/products',

		// order : [ [1, 'desc'] ],

		destroy : true,

		responsive : true,

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '25%', 'targets' : [ 2 ] },
		],

		columns : [
			{
				data : '',
				name : '',
				orderable : false,
				searchable : false,
			},
			{
				data : 'product_img',
				name : 'product_img',
				orderable : false,
				searchable : false,
			},
			{
				data : 'product_name',
				name : 'generic_name',
			},
			{
				data : 'date_added',
				name : 'date_added',
			},
			{
				data : 'batch_no',
				name : 'batch_no',
			},
		]
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
	// productTable.ajax.data;
	// reload datatables
	// productTable.ajax.reload();
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