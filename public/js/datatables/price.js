var pricesTable;

function load_pricesDatatables()
{
	pricesTable = $('#price-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : '/ajax/inventory/price',

		// retrieve : true,

		responsive : true,

		order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '15%', 'targets' : [ 3 ] },
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
				data : 'unit_price_format',
				name : 'unit_price',
				sorting : false,
			},
			{
				data : 'action',
				name : 'action',
			},
		]
	});
}

// load products datatables
load_pricesDatatables();