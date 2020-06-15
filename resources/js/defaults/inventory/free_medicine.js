	
	function freemedicineDetails(id){
	
		let editFreeMedicine = document.querySelector('#edit_free_medicine');
		let loading = document.querySelector('#free_medicine_loading');

		loading.style.display = "block";
		editFreeMedicine.style.display = "none";


		let account = document.querySelector('#account').value;



		let xmlrequest = new XMLHttpRequest();
		xmlrequest.onreadystatechange = function(){
			if(this.readyState == 4 && this.status == 200){
				editFreeMedicine.style.display = "block";
				loading.style.display = "none";
				
				let product_info = JSON.parse(this.responseText);

				let d_product_id = document.querySelector('#update_product_id'),
					d_product_img = document.querySelector('#update_product_img'),
					d_generic = document.querySelector('#update_generic_name'),
					d_brand = document.querySelector('#update_brand_name'),
					d_strength = document.querySelector('#update_strength'),
					d_weight_volume = document.querySelector('#update_weight_volume'),
					d_product_unit = document.querySelector('#update_product_unit'),
					d_quantity_to_get_free = document.querySelector('#update_quantity_to_get_free'),
				    d_free = document.querySelector('#update_free'),
				    acc = document.querySelector('#update_account');


				    d_product_img.setAttribute('src', 'medicine-images/'+product_info['product_img']+'');


				    d_generic.innerHTML = product_info['generic']+" "+product_info['strength'];
					d_brand.innerHTML = product_info['brand'];
					d_weight_volume.innerHTML = product_info['weight_volume'];
					d_product_unit.innerHTML = product_info['product_unit'];
					d_product_id.value = product_info['id'];
					d_quantity_to_get_free.value = product_info['quantity_to_get_free'];
				    d_free.value = product_info['free'];
				    acc.value = account;

					
					
			}
		}
		xmlrequest.open('GET', 'get-free-medicine-details/'+id+'/'+account, true);
		xmlrequest.send();

	}



	function check_account(account){

		alert(account);

		if(account == "General"){
			document.getElementById('general_table').style.display = 'block';
			document.getElementById('accounts_table').style.display = 'none';
		}

		else{
			document.getElementById('general_table').style.display = 'none';
			document.getElementById('accounts_table').style.display = 'block';



			let xmlrequest = new XMLHttpRequest();
			xmlrequest.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					
					let product = JSON.parse(this.responseText);


					let table = '<thead><tr><td></td><td>Product Name <i class="ti-arrows-vertical"></td><td>Product Unit <i class="ti-arrows-vertical"></td><td>Weight / Volume <i class="ti-arrows-vertical"></td><td>Quantity To Get Free <i class="ti-arrows-vertical"></td><td>Free <i class="ti-arrows-vertical"></td><td></td></tr></thead>';

					for(let i = 0; i < product.length; i++){
						
						table += '<tr>';
						table += '<td><input type="hidden" value="'+product[i]['id']+'"></td>';
						table += '<td class="td-name"><img src="medicine-images/'+product[i]['product_img']+'" alt="">';
						table += '<div class="name">';
						table += '<h2>'+product[i]['generic']+' '+product[i]['strength']+'</h2>';
						table += '<h3>'+product[i]['brand']+'</h3>';
						table += '</div>';
						table += '</td>';
						table += '<td>'+product[i]['product_unit']+'</td>';
						table += '<td>'+product[i]['weight_volume']+'</td>';
						table += '<td>'+product[i]['quantity_to_get_free']+'</td>';
						table += '<td>'+product[i]['free']+'</td>';
						table += '<td class="more-alt td-btn" title="Show more details">';
						table += '<i class="ti-settings" onclick="openModal(\'med-modal\'), freemedicineDetails('+product[i]['id']+')"></i>';
						table += '</td>';
						table += '</tr>';
					}

					document.querySelector('#free_medicine_table1').innerHTML = table;
		
				}
			}
			xmlrequest.open('GET', 'get-free-medicine-details-per-account/'+account, true);
			xmlrequest.send();

		}

		

	}