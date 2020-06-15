function getSalesCharts()
{
    axios.get(`/ajax/transactions/sales/charts`,{
        params : {
            from_date : $('[name="from_date"].for-filtering-dates').val(),
            to_date : $('[name="to_date"].for-filtering-dates').val()
        }
    })
        .then( response => {

            update_salesPerProduct(response.data['sales_per_product']);
            update_salesPerAccount(response.data['sales_per_account']);

        } )
        .catch(error => {
            console.log( error.response );
        });
}

// getSalesCharts();

var salesPerProduct_chart;
function salesPerProduct()
{
    let generatedRandomColor = generateRandomColor(  );

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


var salesPerAccount_chart;
function salesPerAccount()
{
    var chart_salesPerAccount = document.getElementById('sales-per-account').getContext('2d');
    salesPerAccount_chart = new Chart(chart_salesPerAccount, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Sales per Account',
                data: [],
                backgroundColor: [],
                borderColor: [],
                borderWidth: 1
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
// load sales per account
salesPerAccount();

function update_salesPerAccount(sales)
{
    salesPerAccount_chart.data.labels = sales['account_names'];
    salesPerAccount_chart.data.datasets[0].data = sales['sales_totals'];

    let randomRGBA = generateRandomColor( sales['account_names'].length );

    salesPerAccount_chart.data.datasets[0].backgroundColor = randomRGBA['background'];
    salesPerAccount_chart.data.datasets[0].borderColor = randomRGBA['border'];

    salesPerAccount_chart.update();
}