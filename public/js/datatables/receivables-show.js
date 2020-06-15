
var billsTable;

function load_showBillsDatatables(id)
{
	billsTable = $('#bills-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : `/ajax/transactions/receivables/${ id }/bills`,

		retrieve : true,

		destroy : true,

		responsive : true,
		// scrollX : true,

		searching : false,
		sorting : false,
		paging : false,
		// info : false,

		order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '40%', 'targets' : [ 3 ] }
		],

		columns : [
			{
				data : 'checkbox',
				name : 'checkbox',
				orderable : false,
				searchable : false,
			},
			{
				data : 'receipt_no',
				name : 'receipt_no',
				orderable : false,
				searchable : false,
			},
			{
				data : 'totals',
				name : 'totals',
				orderable : false,
				searchable : false,
			},
			{
				data : 'inputs',
				name : 'inputs',
				orderable : false,
				searchable : false,
			},
		]
	});
}

// load products datatables
load_showBillsDatatables( accountId );