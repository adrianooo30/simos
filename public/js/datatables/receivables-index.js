
productTable = $('#receivables-table').DataTable({
	processing : true,
	serverSide : true,
	deferRender : true,
	ajax : '/ajax/transactions/receivables',

	// order : [ [1, 'desc'] ],

	destroy : true,
	responsive : true,
	paging : false,

	// exercise
	autoWidth : false, // fast initiliazation
	columnDefs: [
		{ width : '25%', 'targets' : [ 2 ] },
		{ width : '3%', 'targets' : [ 0 ] }
	],

	columns : [
		{
			data : null,
			orderable : false,
			render : function(data){
				return `&nbsp;`;
			}
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
			data : 'total_bill_format',
			name : 'total_bill',
		},
		{
			data : 'action',
			name : 'action',
			orderable : false,
			searchable : false,
		},
	]
});