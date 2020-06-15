var returnsTable;

function load_returnsDatatables()
{
	returnsTable = $('#returns-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : '/ajax/inventory/returns',

		// retrieve : true,

		responsive : true,

		order : [ [3, 'desc'] ],

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
				data : 'returned_date',
				name : 'id',
			},
			{
				data : 'reason',
				name : 'reason',
			},
		]
	});
}

// load products datatables
load_returnsDatatables();