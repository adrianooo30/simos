// $(document).ready(function() {

	var qoutaTable;

	function load_qoutasDatatables()
	{
		qoutaTable = $('#qouta-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : {
				url : '/ajax/users/employees/qoutas',
				data : function( data ){
					data.start_date = $('[name="start_date"]').val();
					data.end_date = $('[name="end_date"]').val();
				},
			},

			retrieve : true,

			// destroy : true,

			lengthChange: false,
			searching : false,
			details : false,
			paging : false,


			order : [ [1, 'asc'] ],

			// exercise
			autoWidth : false, // fast initiliazation
			columnDefs: [
				{ width : '10%', 'targets' : [ ] }
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
					orderable : false,
					searchable : false,
				},
				{
					data : 'target_amount_format',
					name : 'target_amount',
					orderable : false,
					searchable : false,
				},
				{
					data : 'achievement_format',
					name : 'achievement',
					orderable : false,
					searchable : false,
				},
				{
					data : 'percent',
					name : 'percent',
					orderable : false,
					searchable : false,
				},
			]
		});
	}

	// load products datatables
	load_qoutasDatatables();


// });