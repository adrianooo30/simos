var qoutasTable;

function load_qoutasDatatables()
{
	qoutasTable = $('#qoutas-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,

		ajax : {
			url : `/ajax/users/employees/qoutas`,
			data : function( data ){
				data['from_date'] = $('[name="from_date"].for-filtering-dates').val();
				data['to_date'] = $('[name="to_date"].for-filtering-dates').val();
			},
		},

		// order : [ [1, 'desc'] ],

		destroy : true,
		responsive : true,
		paging : false,
		searching : false,
		details : false,

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			// { width : '25%', 'targets' : [ 2 ] },
			// { width : '3%', 'targets' : [ 0 ] }
		],

		columns : [
			{
				data : 'profile_img',
				name : 'profile_img',
				orderable : false,
				searchable : false,
			},
			{
				data : 'full_name',
				name : 'full_name',
			},
			{
				data : 'total_sales_format',
				name : 'total_sales_format',
			},
		]
	});
}

// load qoutas of employees
load_qoutasDatatables();