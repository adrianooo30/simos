var dealsTable;

function load_dealsDatatables()
{
	dealsTable = $('#deals-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : '/ajax/inventory/deals',

		// order : [ [1, 'desc'] ],

		destroy : true,

		responsive : true,

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '25%', 'targets' : [ 1 ] },
			{ width : '20%', 'targets' : [ 2 ] }
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
				data : 'deal_name',
				name : 'deal_name',
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

$(document).ready( function(){
	
	// load products datatables
	load_dealsDatatables();

} );