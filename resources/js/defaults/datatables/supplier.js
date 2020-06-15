// $(document).ready(function() {

	var suppliersTable;

	function load_employeesDatatables()
	{
		suppliersTable = $('#supplier-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : '/ajax/users/suppliers',

			// retrieve : true,

			responsive : true,

			order : [ [1, 'asc'] ],

			// exercise
			autoWidth : false, // fast initiliazation
			columnDefs: [
				{ width : '20%', 'targets' : [ 2 ] },
				{ width : '15%', 'targets' : [ 4 ] },
			],

			columns : [
				{
					data : 'profile_img',
					name : 'profile_img',
					orderable : false,
					searchable : false,
				},
				{
					data : 'supplier_name',
					name : 'supplier_name',
				},
				{
					data : 'address',
					name : 'address',
				},
				{
					data : 'contact',
					name : 'contact',
					orderable : false,
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
	load_employeesDatatables();

// });