// $(document).ready(function() {

var collectionTable;

function load_collectionsDatatables()
{
	collectionTable = $('#collection-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,

		ajax : {
			url : '/ajax/transactions/collections',
			data : function( data ){
				data['from_date'] = $('[name="from_date"].for-filtering-dates').val();
				data['to_date'] = $('[name="to_date"].for-filtering-dates').val();
			},
		},

		retrieve : true,

		responsive : true,

		// destroy : true,

		order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '10%', 'targets' : [ 0, 1, 5 ] }
		],

		columns : [
			{
				data : 'receipt_no',
				name : 'receipt_no',
			},
			{
				data : 'profile_img',
				name : 'profile_img',
				orderable : false,
				searchable : false,
			},
			{
				data : 'account_name',
				name : 'account_name',
			},
			{
				data : 'collection_date',
				name : 'id',
			},
			{
				data : 'total_collected_amount_format',
				name : 'total_collected_amount',
			},
			{
				data : 'action',
				name : 'action',
				orderable : false,
				searchable : false,
			},
		]
	});
}

// load products datatables
load_collectionsDatatables();

function load_paidBillsDatatables(id)
{
	$('#paid-bills-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : `/ajax/transactions/collections/${ id }/paidBills`,

		destroy : true,

		sorting : false,
		searching : false,

		// order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '20%', 'targets' : [ ] }
		],

		columns : [
			{
				data : 'receipt_no',
				name : 'receipt_no',
			},
			{
				data : 'total_cost_format',
				name : 'total_cost',
			},
			{
				data : 'total_paid_amount_format',
				name : 'total_paid_amount_format',
				orderable : false,
			},
			{
				data : 'action',
				name : 'action',
				orderable : false,
				searchable : false,
			},
		]
	});
}


// load datatables for paid order medicines
function load_paidOrderMedicinesDatatables(order_transaction_id, collection_transaction_id)
{
	$('#paid-order-medicines-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,

		ajax : {
			url : `/ajax/transactions/collections/paidOrderMedicines`,
			data : function( data ){
				data['order_transaction_id'] = order_transaction_id;
				data['collection_transaction_id'] = collection_transaction_id;
			},
		},

		destroy : true,

		sorting : false,
		searching : false,
		paging : false,

		// order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '20%', 'targets' : [ ] }
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
				orderable : false,
			},
			{
				data : 'paid_quantity_format',
				name : 'paid_quantity',
				orderable : false,
			},
			{
				data : 'paid_amount_format',
				name : 'paid_amount',
				orderable : false,
			},
		]
	});
}

// });