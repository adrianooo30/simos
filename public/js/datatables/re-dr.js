var reDRTable;

function load_reDRDatatables()
{
	reDRTable = $('#re-dr-table').DataTable({
		
		processing : true,
		serverSide : true,
		deferRender : true,

		ajax : {
			url : `/ajax/order/re-dr`,
		},

		retrieve : true,
		destroy : true,

		responsive: {
		    breakpoints: [
		      {name: 'bigdesktop', width: Infinity},
		      {name: 'meddesktop', width: 1280},
		      {name: 'smalldesktop', width: 1188},
		      {name: 'medium', width: 1024},
		      {name: 'tabletl', width: 848},
		      {name: 'btwtabllandp', width: 768},
		      {name: 'tabletp', width: 480},
		      {name: 'mobilel', width: 320},
		      // {name: 'mobilep', width: 320}
		    ]
		  },

		order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			// { width : '10%', 'targets' : [ 0, 1, 4 ] }
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
				data : 'total_cost_format',
				name : 'total_cost',
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

// load sales datatables
load_reDRDatatables();