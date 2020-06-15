// $(document).ready(function() {
var pendingTable;

function load_pendingOrdersDatatables()
{
	pendingTable = $('#pending-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : '/ajax/order/pending',

		ajax : {
			url : `/ajax/order/pending`,
			data : function( data ){
				data['from_date'] = $('[name="from_date"].for-filtering-dates').val();
				data['to_date'] = $('[name="to_date"].for-filtering-dates').val();
			},
		},

		retrieve : true,

		order : [ [1, 'asc'] ],

		responsive : true,

		// exercise
		// autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '15%', 'targets' : [ 1 ] }
		],

		columns : [
			{
				data : null,
				orderable : false,
				render : function(data){
					return `&nbsp;`;
				}
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
				data : 'order_date',
				name : 'id',
			},
			{
				data : 'total_cost_format',
				name : 'total_cost',
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
load_pendingOrdersDatatables();


function load_orderMedicineDatatables(id)
{
	$('#order-medicine-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : `/ajax/order/pending/orderMedicine/${ id }`,

		destroy : true,

		sorting : false,
		searching : false,

		// order : [ [1, 'asc'] ],
		responsive : true,

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '20%', 'targets' : [ 2 ] }
		],

		columns : [
			{
				data : null,
				orderable : false,
				render : function(data){
					return `&nbsp;`;
				}
			},
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
				data : 'batch_nos',
				name : 'batch_nos',
				orderable : false,
			},
			{
				data : 'quantity_and_free',
				name : 'quantity_and_free',
				orderable : false,
			},
			{
				data : 'total_cost_format',
				name : 'total_cost',
				orderable : false,
			},
		]
	});
}

// });