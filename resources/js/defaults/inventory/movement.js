window.onload = function () {

    		let page = document.getElementById('page').value;
    		

    		if(page="product-movement"){

    			let xmlrequest = new XMLHttpRequest();
				xmlrequest.onreadystatechange = function(){
					if(this.readyState == 4 && this.status == 200){

						// document.getElementById('chart').style.display= 'block';


						let json = JSON.parse(this.responseText);

						if(json.length == 0){
							document.getElementById('chartdiv').innerHTML = '<br><div style="text-align:center; color:dodgerblue"><h3>There is No Registered Product in Your Inventory!</h3></div>';
						}

						else{

							document.getElementById('chartdiv').innerHTML = '<canvas id="bar-chart" style="width: 100%; height: 350px"></canvas>';

							let labelsArray = [];
							let dataArray = [];
							let start_date = json[0]['start_date'];
							let end_date = json[0]['end_date'];

							

							document.getElementById('start_date').value = start_date;
							document.getElementById('end_date').value = end_date;



							for(var i=0; i<json.length; i++){
						    	
						         labelsArray[i] = json[i]['brand_name'] +' '+ json[i]['weight_volume'];
						         dataArray[i] = json[i]['order_qty'];

						    }


						    let backgroundColorArray1 = ["#3e95cd", "#e8c3b9","#3cba9f","#8e5ea2","#ffb399", "#ffd966", "#3e95cd", "#e8c3b9"];
							let backgroundColorArray2 = [];

							var countitems = 0;

							//loop for assigning background color
							do{
						    	
						    	for(var i=0; i<backgroundColorArray1.length; i++){

						    		if(countitems < json.length){
						    			
						    			backgroundColorArray2[countitems] = backgroundColorArray1[i];

						    			countitems++;
						    		}

						    		else{
						    			break;
						    		}

						    	}

						    }while(countitems < json.length);

						    

							new Chart(document.getElementById("bar-chart"), {
							    type: 'bar',
							    data: {
							      labels: labelsArray,
							      datasets: [
							        {
							          label: "Total Quantity",
							          backgroundColor: backgroundColorArray2,
							          data: dataArray
							        }
							      ]
							    },
							    options: {
							      legend: { display: true},
							      title: {
							        display: true,
							        text: 'Product Movement:  ' + start_date + '  To  ' + end_date

							      },

							      scales:{
							      	yAxes:[{
							      		ticks:{
							      			beginAtZero:true
							      		}
							      	}]
							      }

							    }
							});

						}
						

					}	

				}
			
				xmlrequest.open('GET', '/getProductMovement', true);
				xmlrequest.send();

			}


				
		}



		function productMovementDateRange(){

			let page = document.getElementById('page').value;

			
			
			if(page == "product-movement"){

				let startDate = document.getElementById('start_date').value;

				let endDate = document.getElementById('end_date').value;


				let xmlrequest = new XMLHttpRequest();
				xmlrequest.onreadystatechange = function(){
					if(this.readyState == 4 && this.status == 200){

						let json = JSON.parse(this.responseText);

						if(json.length == 0){
							document.getElementById('chartdiv').innerHTML = '<br><div style="text-align:center; color:dodgerblue"><h3>There is No Registered Product in Your Inventory!</h3></div>';
						}


						else{
							
							document.getElementById('chartdiv').innerHTML = '<canvas id="bar-chart" style="width: 100%; height: 350px"></canvas>';

							let labelsArray = [];
							let dataArray = [];

							for(var i=0; i<json.length; i++){
						    	
						         labelsArray[i] = json[i]['brand_name'] +' '+ json[i]['weight_volume'];
						         dataArray[i] = json[i]['order_qty'];

						    }


						    let backgroundColorArray1 = ["#3e95cd", "#e8c3b9","#3cba9f","#8e5ea2","#ffb399", "#ffd966", "#3e95cd", "#e8c3b9"];
							let backgroundColorArray2 = [];

							var countitems = 0;

							//loop for assigning background color
							do{
						    	
						    	for(var i=0; i<backgroundColorArray1.length; i++){

						    		if(countitems < json.length){
						    			
						    			backgroundColorArray2[countitems] = backgroundColorArray1[i];

						    			countitems++;
						    		}

						    		else{
						    			break;
						    		}

						    	}

						    }while(countitems < json.length);



							new Chart(document.getElementById("bar-chart"), {
							    type: 'bar',
							    data: {
							      labels: labelsArray,
							      datasets: [
							        {
							          label: "Total Quantity",
							          backgroundColor: backgroundColorArray2,
							          data: dataArray
							        }
							      ]
							    },
							    options: {
							      legend: { display: true},
							      title: {
							        display: true,
							        text: 'Product Movement:  ' + startDate + '  To  ' + endDate
							      },

							      scales:{
							      	yAxes:[{
							      		ticks:{
							      			beginAtZero:true
							      		}
							      	}]
							      }

							    }
							});

						}

						

					}	

				}
			
				xmlrequest.open('GET', '/productMovementDateRange/'+startDate+'/'+endDate, false);
				xmlrequest.send();

			}

			document.getElementById('filter-modal').style.display = 'none';

			return false;

		}