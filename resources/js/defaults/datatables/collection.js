// $(document).ready(function() {

	var collectionTable;

	function load_collectionsDatatables()
	{
		collectionTable = $('#collection-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,

			ajax : {
				url : '/ajax/transactions/collections',
				data : function( data ){
					data.start_date = $('[name="start_date"]').val();
					data.end_date = $('[name="end_date"]').val();
				},
			},

			retrieve : true,

			// destroy : true,

			order : [ [1, 'asc'] ],

			// exercise
			autoWidth : false, // fast initiliazation
			columnDefs: [
				{ width : '10%', 'targets' : [ 0, 1, 5 ] }
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
					data : 'collection_date',
					name : 'id',
				},
				{
					data : 'collected_amount_format',
					name : 'collected_amount',
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
	load_collectionsDatatables();

	function load_paidBillsDatatables(id)
	{
		$('#paid-bills-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : `/ajax/transactions/collections/${ id }/paidBills`,

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
				},
				{
					data : 'total_cost_format',
					name : 'total_cost',
				},
				{
					data : 'paid_amount_format',
					name : 'paid_amount',
					orderable : false,
				},
				{
					data : 'others',
					name : 'others',
					orderable : false,
					searchable : false,
				}
			]
		});
	}

// });