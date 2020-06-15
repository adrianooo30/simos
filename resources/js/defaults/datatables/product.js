var productTable;

function load_productsDatatables()
{
	productTable = $('#product-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : '/ajax/inventory/products',

		// order : [ [1, 'desc'] ],

		destroy : true,

		responsive : true,

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '25%', 'targets' : [ 1 ] }
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
				name : 'generic_name',
			},
			{
				data : 'stock_format',
				name : 'stock',
			},
			{
				data : 'unit_price_format',
				name : 'unit_price',
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

var batchNoTable;

function load_batchNosDatatables(id)
{
	batchNoTable = $('#batch-no-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : `/ajax/inventory/products/${ id }/batchNo`,

		destroy : true,

		// paging : false,
		ordering : false,
		// info : false,
		searching : false,

		// order : [ [1, 'desc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '15%', 'targets' : [ 4 ] }
		],

		columns : [
			{
				data : 'batch_no',
				name : 'batch_no',
			},
			{
				data : 'exp_date',
				name : 'exp_date',
			},
			{
				data : 'quantity_format',
				name : 'quantity',
			},
			{
				data : 'unit_cost_format',
				name : 'unit_cost',
			},
			{
				data : 'action',
				name : 'action',
				orderable : false,
				searchable : false,
			}
		]
	});
}

$(document).ready( function(){
	
	// load products datatables
	load_productsDatatables();

} );