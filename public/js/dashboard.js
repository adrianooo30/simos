/**
 * Section for Sales per Product.
 *
 */
function getCharts()
{
    axios.get(`/ajax/dashboard/charts`,{
        params : {
            from_date : moment().startOf('month').format('YYYY-MM-DD'),
            to_date : moment().endOf('month').format('YYYY-MM-DD')
        }
    })
        .then( response => {

            update_salesPerProduct(response.data['sales_per_product']);
            update_collectionsPerProduct(response.data['collections_per_product']);

            console.log( moment().startOf('month'), moment().endOf('month') );

        } )
        .catch( error => {
            console.log( error.response );
        });
}

var salesPerProduct_chart;
function salesPerProduct()
{
    var ctx = document.getElementById('sales-per-product').getContext('2d');
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


/**
 * Section for Collections per Product.
 *
 */
var collectionsPerProduct_chart;
function collectionsPerProduct()
{
    var ctx = document.getElementById('collections-per-product').getContext('2d');
    collectionsPerProduct_chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Collections per Product',
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

collectionsPerProduct();

function update_collectionsPerProduct(collections)
{
    collectionsPerProduct_chart.data.labels = collections['product_names'];
    collectionsPerProduct_chart.data.datasets[0].data = collections['collection_totals'];

    let randomRGBA = generateRandomColor( collections['product_names'].length );

    collectionsPerProduct_chart.data.datasets[0].backgroundColor = randomRGBA['background'];
    collectionsPerProduct_chart.data.datasets[0].borderColor = randomRGBA['border'];

    collectionsPerProduct_chart.update();
}


// after loading front end
// $(document).ready( function(){
    // after reloading...
    getCharts();
// });