// $(document).ready(function() {

	var employeesTable;

	function load_employeesDatatables()
	{
		employeesTable = $('#employee-table').DataTable({
			processing : true,
			serverSide : true,
			deferRender : true,
			ajax : '/ajax/users/employees',

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
					data : 'full_name',
					name : 'full_name',
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