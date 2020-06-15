// $(document).ready(function() {

var trackTable;

function load_trackingOrdersDatatables()
{
	trackTable = $('#track-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,

		ajax : {
			url : `/ajax/order/track`,
			data : function( data ){
				data['from_date'] = $('[name="from_date"].for-filtering-dates').val();
				data['to_date'] = $('[name="to_date"].for-filtering-dates').val();
			},
		},

		retrieve : true,

		responsive : true,

		// destroy : true,

		order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '10%', 'targets' : [ 6 ] }
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
				data : 'order_date',
				name : 'id',
			},
			{
				data : 'total_cost_format',
				name : 'total_cost',
			},
			{
				data : 'status_html',
				name : 'status',
				orderable : false,
				searchable : false,
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
load_trackingOrdersDatatables();

function load_orderMedicineDatatables(id)
{
	$('#order-medicine-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : `/ajax/order/track/orderMedicine/${ id }`,

		destroy : true,

		sorting : false,
		searching : false,

		responsive: {
		    breakpoints: [
		      {name: 'bigdesktop', width: Infinity},
		      {name: 'meddesktop', width: 1280},
		      {name: 'smalldesktop', width: 1188},
		      {name: 'medium', width: Infinity},
		      {name: 'tabletl', width: 848},
		      {name: 'btwtabllandp', width: 768},
		      {name: 'tabletp', width: 480},
		      {name: 'mobilel', width: 320},
		      // {name: 'mobilep', width: 320}
		    ]
		  },

		// order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			// { width : '7%', 'targets' : [ 5 ] }
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
				orderable : false,
			},
			{
				data : 'batch_nos',
				name : 'batch_nos',
				orderable : false,
			},
			{
				data : 'quantity_and_free',
				name : 'quantity_and_free',
				orderable : false,
			},
			{
				data : 'total_cost_format',
				name : 'total_cost',
				orderable : false,
			},
			// {
			// 	data : 'action',
			// 	name : 'action',
			// 	orderable : false,
			// 	searchable : false,
			// },
		]
	});
}

// });