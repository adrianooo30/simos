function getCollectionsCharts()
{
    axios.get(`/ajax/transactions/collections/charts`,{
        params : {
            from_date : $('[name="from_date"].for-filtering-dates').val(),
            to_date : $('[name="to_date"].for-filtering-dates').val()
        }
    })
        .then( response => {

            update_collectionsPerProduct(response.data['collections_per_product']);
            update_collectionsPerAccount(response.data['collections_per_account']);

            console.log(response.data);

        } )
        .catch(error => {
            console.log( error );
        });
}

var collectionsPerProduct_chart;
function collectionsPerProduct(length = 0)
{
    let generatedRandomColor = generateRandomColor(  );

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
// load chart
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


var collectionsPerAccount_chart;
function collectionsPerAccount(collections)
{
    var chart_collectionsPerAccount = document.getElementById('collections-per-account').getContext('2d');
    collectionsPerAccount_chart = new Chart(chart_collectionsPerAccount, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Collections per Account',
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
// load collections per account
collectionsPerAccount();

function update_collectionsPerAccount(collections)
{
    collectionsPerAccount_chart.data.labels = collections['account_names'];
    collectionsPerAccount_chart.data.datasets[0].data = collections['collection_totals'];

    let randomRGBA = generateRandomColor( collections['account_names'].length );

    collectionsPerAccount_chart.data.datasets[0].backgroundColor = randomRGBA['background'];
    collectionsPerAccount_chart.data.datasets[0].borderColor = randomRGBA['border'];

    collectionsPerAccount_chart.update();
}