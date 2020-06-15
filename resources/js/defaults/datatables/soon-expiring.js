// $(document).ready(function() {

	var lossProductsTable;

	function load_lossProductsDatatables()
	{
		lossProductsTable = $('#loss-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : '/ajax/inventory/loss',

			// retrieve : true,

			responsive : true,

			order : [ [1, 'asc'] ],

			// exercise
			autoWidth : false, // fast initiliazation
			columnDefs: [
				{ width : '30%', 'targets' : [ 4 ] },
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
					data : 'loss_date',
					name : 'loss_date',
				},
			]
		});
	}

	// load products datatables
	load_lossProductsDatatables();

// });