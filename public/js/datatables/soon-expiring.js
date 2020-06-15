// $(document).ready(function() {

	var soonExpiringTable;

	function load_soonExpiringDatatables()
	{
		soonExpiringTable = $('#soon-expiring-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : '/ajax/inventory/soon-expiring',

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
					data : 'exp_date',
					name : 'exp_date',
				},
				{
					data : 'before_expiring',
					name : 'before_expiring',
				},
			]
		});
	}

	// load products datatables
	load_soonExpiringDatatables();

// });