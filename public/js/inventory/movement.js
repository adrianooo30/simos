function getSalesCharts()
{
    axios.get(`/ajax/inventory/movement`,{
        params : {
            from_date : $('[name="from_date"].for-filtering-dates').val(),
            to_date : $('[name="to_date"].for-filtering-dates').val()
        }
    })
        .then( response => {

            update_salesPerProduct(response.data['sales_per_product']);

        } )
        .catch(error => {
            console.log( error );
        });
}


var salesPerProduct_chart;
function salesPerProduct()
{
    let generatedRandomColor = generateRandomColor(  );

    var ctx = document.getElementById('movement').getContext('2d');
    salesPerProduct_chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales per Product',
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}
// load chart
salesPerProduct();

function update_salesPerProduct(sales)
{
    salesPerProduct_chart.data.labels = sales['product_names'];
    salesPerProduct_chart.data.datasets[0].data = sales['sales_totals'];

    let randomRGBA = generateRandomColor( sales['product_names'].length );

    salesPerProduct_chart.data.datasets[0].backgroundColor = randomRGBA['background'];
    salesPerProduct_chart.data.datasets[0].borderColor = randomRGBA['border'];

    salesPerProduct_chart.update();
}


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