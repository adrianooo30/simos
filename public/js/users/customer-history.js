function getSalesCharts()
{
    axios.get(`/ajax/users/accounts/history/${ accountId }/charts`,{
        params : {
            from_date : $('[name="from_date"].for-filtering-dates').val(),
            to_date : $('[name="to_date"].for-filtering-dates').val()
        }
    })
        .then( response => {

            update_sales(response.data['sales']);
            update_collections(response.data['collections']);

        } )
        .catch(error => {
            console.log( error );
        });
}

// set sales charts
var sales_chart;
function sales()
{
    var ctx = document.getElementById('sales').getContext('2d');
    sales_chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
            }]
        },
        options: {
            // scales: {
            //     yAxes: [{
            //         ticks: {
            //             beginAtZero: true
            //         }
            //     }]
            // }
        }
    });
}
// load chart
sales();

function update_sales(sales)
{
    sales_chart.data.labels = sales['product_name'];
    sales_chart.data.datasets[0].data = sales['total_sales'];

    let randomRGBA = generateRandomColor( sales['product_name'].length );

    sales_chart.data.datasets[0].backgroundColor = randomRGBA['background'];
    sales_chart.data.datasets[0].borderColor = randomRGBA['border'];

    sales_chart.update();
}

// set collections charts
var collections_chart;
function collections()
{
    var chart_collections = document.getElementById('collections').getContext('2d');
    collections_chart = new Chart(chart_collections, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
            }]
        },
        options: {
            // scales: {
            //     yAxes: [{
            //         ticks: {
            //             beginAtZero: true
            //         }
            //     }]
            // }
        }
    });
}
// load sales per account
collections();

function update_collections(collections)
{
    collections_chart.data.labels = collections['product_name'];
    collections_chart.data.datasets[0].data = collections['total_collections'];

    let randomRGBA = generateRandomColor( collections['product_name'].length );

    collections_chart.data.datasets[0].backgroundColor = randomRGBA['background'];
    collections_chart.data.datasets[0].borderColor = randomRGBA['border'];

    collections_chart.update();
}

var bills_canPaidUsing_excessTable;
// datatables
function getBillsCanBePaidUsingExcessTable()
{
    bills_canPaidUsing_excessTable = $('#bills-can-paid-using-excess-table').DataTable({
        
        processing : true,
        serverSide : true,
        deferRender : true,

        ajax : `/ajax/users/accounts/history/${ accountId }/get_bills_can_paid_using_excess_payment`,

        retrieve : true,
        destroy : true,
        paging : false,
        searching : false,

        responsive: {
            breakpoints: [
              {name: 'bigdesktop', width: Infinity},
              {name: 'meddesktop', width: 1280},
              {name: 'smalldesktop', width: 1188},
              {name: 'medium', width: 1024},
              {name: 'tabletl', width: 848},
              {name: 'btwtabllandp', width: Infinity},
              {name: 'tabletp', width: 480},
              {name: 'mobilel', width: 320},
              // {name: 'mobilep', width: 320}
            ]
          },

        order : [ [3, 'desc'] ],

        // exercise
        autoWidth : false, // fast initiliazation
        columnDefs: [
            // { width : '10%', 'targets' : [ 5 ] }
        ],

        columns : [
            {
                data : 'receipt_no',
                name : 'receipt_no',
            },
            {
                data : 'delivery_date',
                name : 'delivery_date',
            },
            {
                data : 'total_bill_format',
                name : 'total_bill',
            },
            {
                data : 'status_html',
                name : 'status',
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

// load bills that can be paid using excess payment
getBillsCanBePaidUsingExcessTable();

// **********************************
// 			FILTERING DATES
// **********************************
var start = moment().startOf('month');
var end = moment().endOf('month');

function cb(start, end) {
    // setting the value
    $('[name="from_date"].for-filtering-dates').val( start.format('YYYY-MM-DD') );
    $('[name="to_date"].for-filtering-dates').val( end.format('YYYY-MM-DD') );

    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    getSalesCharts();
}

// set date range picker
$(document).ready(function(){

    // report-range
	$('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    
});

cb(start, end);


// events
$('#bills-can-paid-using-excess-table').on('click', '.--pay-btn', function(){

    let order_transaction_id = $(this).data('order-transaction-id');

    swal({
        title: 'Are you sure, you want to paid this bill using the excess payment?',
        text : 'If yes, then just click the confirm button.',
        icon : 'warning',
        buttons : true,
        dangerMode: true,
    })
    .then((confirm) => {
        if(confirm) {
            // alert order transaction id
            alert( order_transaction_id );
        }
    });

});