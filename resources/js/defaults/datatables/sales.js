// $(document).ready(function() {

	var salesTable;

	function load_salesDatatables()
	{
		salesTable = $('#sales-table').DataTable({
			
			processing : true,
			serverSide : true,
			deferRender : true,

			ajax : {
				url : `/ajax/transactions/sales`,
				data : function( data ){
					data.start_date = $('[name="start_date"]').val();
					data.end_date = $('[name="end_date"]').val();
				},
			},

			retrieve : true,

			destroy : true,

			responsive: {
			    breakpoints: [
			      {name: 'bigdesktop', width: Infinity},
			      {name: 'meddesktop', width: 1280},
			      {name: 'smalldesktop', width: 1188},
			      {name: 'medium', width: 1024},
			      {name: 'tabletl', width: 848},
			      {name: 'btwtabllandp', width: 768},
			      {name: 'tabletp', width: 480},
			      {name: 'mobilel', width: 320},
			      // {name: 'mobilep', width: 320}
			    ]
			  },

			order : [ [1, 'asc'] ],

			// exercise
			autoWidth : false, // fast initiliazation
			columnDefs: [
				{ width : '10%', 'targets' : [ 6 ] }
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

	// loading of ordered medicine
	function load_orderMedicineDatatables(id)
	{
		$('#order-medicine-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : `/ajax/transactions/sales/orderMedicine/${ id }`,

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

	// load collections table
	function load_collectionsDatatables(id)
	{
		$('#collections-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : `/ajax/transactions/sales/${ id }/collections`,

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
					orderable : false,
					searchable : false,
				},
				{
					data : 'collection_date',
					name : 'id',
					orderable : false,
				},
				{
					data : 'paid_amount_format',
					name : 'paid_amount',
					orderable : false,
				},
				{
					data : 'type_of_payment',
					name : 'type_of_payment',
					orderable : false,
				},
			]
		});
	}