var expiredTable;

function load_expiredDatatables()
{
	expiredTable = $('#expired-products-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,

		ajax : {
			url : `/ajax/inventory/expired`,
			data : function( data ){
				data['from_date'] = $('[name="from_date"].for-filtering-dates').val();
				data['to_date'] = $('[name="to_date"].for-filtering-dates').val();
			},
		},

		// retrieve : true,

		responsive : true,

		order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			// { width : '30%', 'targets' : [ 4 ] },
		],

		columns : [
			{
				data : 'product_img',
				name : 'product_img',
				orderable : false,
				searchable : false,
			},
			{
				data : 'product_name',
				name : 'product_name',
			},
			{
				data : 'batch_no',
				name : 'batch_no',
			},
			{
				data : 'exp_date',
				name : 'exp_date',
			},
			{
				data : 'after_expiring',
				name : 'after_expiring',
			},
		]
	});
}

// load products datatables
load_expiredDatatables();


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