// $(document).ready(function() {

	var trackTable;

	function load_trackingOrdersDatatables()
	{
		trackTable = $('#track-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : '/ajax/order/track',

			retrieve : true,

			// destroy : true,

			order : [ [1, 'asc'] ],

			// exercise
			autoWidth : false, // fast initiliazation
			columnDefs: [
				{ width : '10%', 'targets' : [ 4, 5 ] }
			],

			columns : [
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
					data : 'status',
					name : 'status',
					orderable : false,
					searchable : false,
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
	load_trackingOrdersDatatables();

	function load_orderMedicineDatatables(id)
	{
		$('#order-medicine-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : `/ajax/order/track/orderMedicine/${ id }`,

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
					data : 'quantity_format',
					name : 'quantity',
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