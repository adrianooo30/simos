
function forReturnProducts(id)
{
	// SALES RETURNEE
	axios.get(`/ajax/transactions/sales/${ id }/toReturn/`)
		.then((success) => {

			// $('#--order-details').show();
			// $('#--order-loading').hide();

			console.log(success.data);

			selectedReturnees = new Array();
			$('#accordion-returnee').empty();

			let salesReturnees = success.data;

			let content = '';
			salesReturnees['order_medicine'].forEach(function(orderMedicine, index, array){

				if(orderMedicine['total_cost'] > 0)
				{
					let returneeDisplay_batchNo = ''; // list of returned batch number

					// DISPLAYING THE BATCH NUMBERS OF PER PRDOUCT
					orderMedicine['order_batch_no'].forEach(function(orderBatchNo, index, array){

						if(orderBatchNo['quantity'] > 0)
						{
							let options = `<option value="replace">Replace</option>`;
							if(salesReturnees['status'] === 'Paid' || salesReturnees['status'] === 'Balanced')
								options += `<option value="return payment">Return Payment</option>`;
							else
								options += `<option value="dont replace">Dont Replace</option>`;


						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************
						//
						//							ORDER BATCH NUMBER
						//
						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************

							returneeDisplay_batchNo += `
								<!-- BATCH NUMBER -->
					            <div class="card-body py-0">

							        <div class="card-header mb-4 pr-4 pl-4">

							        	<div class="d-flex flex-row justify-content-between">
							        		<div class="d-flex flex-row mx-1">
							        			<div class="custom-control custom-checkbox m-0">
												    <input type="checkbox" class="custom-control-input checkbox-o" id="checkbox-o`+orderBatchNo['id']+`" autocomplete="off" data-order-batch-no-id="`+orderBatchNo['id']+`">
												    <label class="custom-control-label" for="checkbox-o`+orderBatchNo['id']+`"></label>
												</div>

								                <label for="checkbox-o`+orderBatchNo['id']+`" class="mx-1 text-primary imp">
													`+orderBatchNo['batch_no']['batch_no']+` - `+orderBatchNo['quantity']+` pcs.
												</label>
							        		</div>

											<div class="">
						                        <a data-toggle="collapse" href="#sub-collapse`+orderBatchNo['id']+`" class="btn btn-sm btn-outline-primary">
						                            <i class="ti-angle-down"></i>
						                        </a>
						                    </div>	
							        	</div>

							        </div>

							        <div id="sub-collapse`+orderBatchNo['id']+`" class="collapse hide" data-parent="#collapse`+orderMedicine['id']+`">
							            <div class="card-body px-0">

							            	<input type="hidden" name="returnee_batch_no_type" value="original" class="for-returnee">

						                    <div class="form-group col-lg-5 col-md-10">
											    <div class="input-box d-flex flex-column mb-2">
											        <sup class="text-info mb-3">Returned Quantity</sup>
											        <div class="input-field col">
											            <input type="number" name="returned-qty`+orderBatchNo['id']+`" class="--quantity for-returnee" min="1" max="`+orderBatchNo['quantity']+`" placeholder="Max quantity of `+orderBatchNo['quantity']+`" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`">
											        </div>
											    </div>
											    <div class="input-box d-flex flex-column mt-4 mb-2">
											        <sup class="text-info mb-3">Status</sup>
											        <div class="input-field col">
											            <select name="status`+orderBatchNo['id']+`" class="--status form-control for-returnee" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`">
											                <option value="" disabled="" selected>--Select Status--</option>
											                ${ options }
											            </select>
											        </div>
											    </div>
											</div>

											<div class="form-group col-lg-5 col-md-10">
												<div class="input-box d-flex flex-column mt-4 mb-2">
											        <sup class="text-info mb-3">Static Reason</sup>
											        <div class="input-field col">
											            <select name="reason`+orderBatchNo['id']+`" class="--static-reason form-control for-returnee" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`">
															<option value="" disabled selected>--Select Static Reason--</option>
															<option value="damage">Damage</option>
															<option value="expired">Expired</option>
											            </select>
											        </div>
											    </div>

												<div class="input-box d-flex flex-column mt-4 mb-2">
											        <sup class="text-info mb-3">Reason of Return</sup>
											        <div class="input-field col">
											            <textarea name="reason_text`+orderBatchNo['id']+`" placeholder="reason of return" class="--reason for-returnee" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`"></textarea>
											        </div>
											    </div>
											</div>

						                </div>
							        </div>
							    </div> <!-- end of card - batch number card -->
							`;
						}


						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************
						//
						//							CHANGED BATCH NUMBER
						//
						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************
						// ************************************************************************************

						// display also the changed batch number
						if(orderBatchNo['changed_batch_no'].length > 0)
						{
							orderBatchNo['changed_batch_no'].forEach(function(changedBatchNo, index) {

								let options = `<option value="replace">Replace</option>`;
								if(salesReturnees['status'] === 'Paid' || salesReturnees['status'] === 'Balanced')
									options += `<option value="return payment">Return Payment</option>`;
								else
									options += `<option value="dont replace">Dont Replace</option>`;

								returneeDisplay_batchNo += `
								<!-- BATCH NUMBER -->
					            <div class="card-body py-0">

							        <div class="card-header mb-4 pr-4 pl-4">

							        	<div class="d-flex flex-row justify-content-between">
							        		<div class="d-flex flex-row mx-1">
							        			<div class="custom-control custom-checkbox m-0">
												    <input type="checkbox" class="custom-control-input checkbox-o" id="changed-checkbox-o`+changedBatchNo['id']+`" autocomplete="off" data-order-changed-batch-no="`+changedBatchNo['id']+`">
												    <label class="custom-control-label" for="changed-checkbox-o`+changedBatchNo['id']+`"></label>
												</div>

								                <label for="changed-checkbox-o`+changedBatchNo['id']+`" class="mx-1 text-warning imp">
													`+changedBatchNo['batch_no']['batch_no']+` - `+changedBatchNo['quantity']+` pcs.
												</label>
							        		</div>

											<div class="">
						                        <a data-toggle="collapse" href="#sub-changed-collapse`+changedBatchNo['id']+`" class="btn btn-sm btn-outline-primary">
						                            <i class="ti-angle-down"></i>
						                        </a>
						                    </div>	
							        	</div>

							        </div>

							        <div id="sub-changed-collapse`+changedBatchNo['id']+`" class="collapse hide" data-parent="#collapse`+orderMedicine['id']+`">
							            <div class="card-body px-0">

							            	<input type="hidden" name="returnee_batch_no_type" value="replaced" class="for-returnee">

						                    <div class="form-group col-lg-5 col-md-10">
											    <div class="input-box d-flex flex-column mb-2">
											        <sup class="text-info mb-3">Returned Quantity</sup>
											        <div class="input-field col">
											            <input type="number" name="returned-qty`+changedBatchNo['id']+`" class="--quantity for-returnee" min="1" max="`+changedBatchNo['quantity']+`" placeholder="Max quantity of `+changedBatchNo['quantity']+`" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`" data-order-changed-batch-no-id="`+changedBatchNo['id']+`">
											        </div>
											    </div>
											    <div class="input-box d-flex flex-column mt-4 mb-2">
											        <sup class="text-info mb-3">Status</sup>
											        <div class="input-field col">
											            <select name="status`+changedBatchNo['id']+`" class="--status form-control for-returnee" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`" data-order-changed-batch-no-id="`+changedBatchNo['id']+`">
											                <option value="" disabled="" selected>--Select Status--</option>
											                ${ options }
											            </select>
											        </div>
											    </div>
											</div>

											<div class="form-group col-lg-5 col-md-10">
												<div class="input-box d-flex flex-column mt-4 mb-2">
											        <sup class="text-info mb-3">Static Reason</sup>
											        <div class="input-field col">
											            <select name="reason`+changedBatchNo['id']+`" class="--static-reason form-control for-returnee" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`" data-order-changed-batch-no-id="`+changedBatchNo['id']+`">
															<option value="" disabled selected>--Select Static Reason--</option>
															<option value="damage">Damage</option>
															<option value="expired">Expired</option>
											            </select>
											        </div>
											    </div>

												<div class="input-box d-flex flex-column mt-4 mb-2">
											        <sup class="text-info mb-3">Reason of Return</sup>
											        <div class="input-field col">
											            <textarea name="reason_text`+changedBatchNo['id']+`" placeholder="reason of return" class="--reason for-returnee" disabled="" data-order-batch-no-id="`+orderBatchNo['id']+`" data-order-changed-batch-no-id="`+changedBatchNo['id']+`"></textarea>
											        </div>
											    </div>
											</div>

						                </div>
							        </div>
							    </div> <!-- end of card - batch number card -->
							`;
							});
						}

					});

					// DISPLAYING THE CONTENT
					content += `
						<!-- content of accordion -->
						    <div class="card returnee-card">

						    	<!-- card header -->
						        <div class="card-header mb-4 pr-4 pl-4">

						            <div class="d-flex flex-lg-row flex-md-row flex-sm-row flex-column align-items-center">
						                <div class="d-flex flex-lg-row flex-md-row flex-sm-row flex-column p-2 flex-grow-1 align-items-center">

						                    <img src="${ orderMedicine['product']['product_img'] }" width="120" height="120">

						                    <div class="d-flex align-items-center flex-column p-2">
						                        <label class="text-primary text-center">`+orderMedicine['product']['generic_name']+` `+orderMedicine['product']['weight_volume']+`</label>
						                        <label class="text-muted text-center">`+orderMedicine['product']['brand_name']+`</label>
						                        <sub class="text-muted text-center">`+orderMedicine['product']['unit_price_format']+`</sub>
						                    </div>
						                </div>

						                <div class="d-flex flex-column p-2 flex-grow-1">
						                    <label class="text-primary">`+orderMedicine['latest_quantity_format']+` pcs.</label>
						                    <sup class="text-muted mb-2">Total Quantity</sup>

						                    <label class="text-primary">`+orderMedicine['total_cost_format']+`</label>
						                    <sup class="text-muted">Total Cost</sup>
						                </div>

						                <div class="">
						                    <a data-toggle="collapse" href="#collapse`+orderMedicine['id']+`" class="btn btn-primary" accesskey="`+index+`">
						                        <i class="ti-angle-down"></i>
						                    </a>
						                </div>
						            </div>
						            
						        </div>

						        <div id="collapse`+orderMedicine['id']+`" class="collapse hide" data-parent="#accordion-returnee">
									${ returneeDisplay_batchNo }
						        </div>

						</div> <!-- end of returnee card - PRODUCT -->
					`;
				}

			});
			
			// DISPLAY THE LIST OF MEDICINE THAT ARE POSSIBLE TO RETURN
			$('#accordion-returnee').append(content);

			$('.form-control.for-returnee').keyup(function(){
				$('#--is-has-error').hide('fast');
			});

			//****************************************************
			//			Checkboxes add event listener
			//****************************************************
			document.querySelectorAll('.checkbox-o').forEach(function(object, index, array){

				object.addEventListener('click', function(){

					let thisObject = $('#'+object.id);

					if(object.checked) // if checkbox is checked
					{
						$.each(thisObject.closest('.card-body').find('.form-group .input-box .for-returnee'), (key, objectInput) => {
							objectInput.removeAttribute('disabled');
						});
					}
					else // is checkbox is not checked
					{
						$.each(thisObject.closest('.card-body').find('.form-group .input-box .for-returnee'), (key, objectInput) => {
							objectInput.setAttribute('disabled', '');
							objectInput.value = '';
						});

						// setting to true the is_drop property.
						selectedReturnees.forEach( function(product, index, array){
							if(product['order_batch_no_id'] == object.dataset.orderBatchNoId){
								product['is_drop'] = true;
							}
						});

						// setting valid data.
						setValidData();

						console.log(selectedReturnees);
					}

				});

			});

			//****************************************************
			//			QUANTITY add event listener
			//****************************************************
			document.querySelectorAll('.--quantity.for-returnee').forEach(function(object, index, array){

				object.addEventListener('keyup', function(){

					if(parseInt(object.value) > parseInt(object.max))
						object.value = object.max;
					else if(parseInt(object.value) < 1)
						object.value = 1;
					else if(isNaN(object.value))
						object.value = '';

					// add or update data
					addOrUpdateReturnee(object, 'quantity', parseInt(object.value));
					// setting valid data.
					setValidData();
				});

			});

			//****************************************************
			//			STATIC REASON add event listener
			//****************************************************
			document.querySelectorAll('.--static-reason.for-returnee').forEach(function(object, index, array){

				object.addEventListener('change', function(){

					// add or update data
					addOrUpdateReturnee(object, 'static_reason', object.value);
					// setting valid data.
					setValidData();
				});

			});

			//****************************************************
			//			REASON add event listener
			//****************************************************
			document.querySelectorAll('.--reason.for-returnee').forEach(function(object, index, array){

				object.addEventListener('keyup', function(){

					// add or update data
					addOrUpdateReturnee(object, 'reason', object.value);
					// setting valid data.
					setValidData();
				});

			});

			//****************************************************
			//			STATUS add event listener
			//****************************************************
			document.querySelectorAll('.--status.for-returnee').forEach(function(object, index, array){

				object.addEventListener('change', function(){

					// add or update data
					addOrUpdateReturnee(object, 'status', object.value);
					// setting valid data.
					setValidData();
				});

			});

		})
		.catch(error => {

			console.log(error);

		});
	}




	// setting the default error message
	var errorMessage = {
		'title' : 'Error',
		'message' : 'Wait you haven\'t selected any of the product to returned.',
	};

	function setErrorMessage()
	{
		// setting of error message
		$('#--error-title').text(errorMessage.title);
		$('#--error-message').text(errorMessage.message);

		// displaying the error message
		$('#--is-has-error').show('fast', 'swing', function(){
			$('#--is-has-error').addClass('active');
			
			setTimeout(function(){
				$('#--is-has-error').removeClass('active');
			}, 1000);
		});
	}

	// getting the valid data for each returnee products
	function setValidData()
	{
		// evacuate the not droping data into tempReturnedProducts
		let tempReturnedProducts = new Array();
		selectedReturnees.forEach(function(product, index, array){
			if(!product['is_drop'])
				tempReturnedProducts.push(product);
		});

		selectedReturnees = tempReturnedProducts; // return back the evacues

		console.log(selectedReturnees);
	}

	// function for adding and updating selected returnee
	function addOrUpdateReturnee(object, row, value)
	{
		// get the type of returnee - original or replaced
		returneeBatchNoType = $(object).closest('.card-body').find('[name="returnee_batch_no_type"].for-returnee').val();

		let hasSameBatchNo = selectedReturnees.some(function(returnee){
			return returnee['order_batch_no_id'] == object.dataset['orderBatchNoId'] && returnee['returnee_batch_no_type'] === returneeBatchNoType;
		});

		// has same product
		if(hasSameBatchNo) {
			selectedReturnees.forEach(function(returnee, index, array) {
				if(returnee['order_batch_no_id'] == object.dataset['orderBatchNoId']) {
					returnee[row] = value;
				}
			});
		}
		// else is no same product
		else
		{
			let jsonValue = { quantity : '', static_reason: '', reason : '', status : ''};

			if(row === 'quantity')
				jsonValue.quantity = value;
			else if(row === 'static_reason')
				jsonValue.reason = value;
			else if(row === 'reason')
				jsonValue.reason = value;
			else if(row === 'status')
				jsonValue.status = value;

			// add new selected returnees
			selectedReturnees.push({
				'order_batch_no_id' : object.dataset['orderBatchNoId'],
				'quantity' : jsonValue.quantity,
				'static_reason' : jsonValue.static_reason,
				'reason' : jsonValue.reason,
				'status' : jsonValue.status,
				'returnee_batch_no_type' : returneeBatchNoType, // original or replaced
				'is_drop' : false,
			});

			// if the returneeBatchNoType is replaced
			if(returneeBatchNoType === 'replaced') {
				selectedReturnees.forEach(function(returnee, index, array) {
					if(returnee['order_batch_no_id'] == object.dataset['orderBatchNoId'])
						returnee['order_changed_batch_no_id'] = object.dataset['orderChangedBatchNoId'];
				});
			}
			// end of if - replaced
		}
	}

	$(document).ready(function(){

		//************************************************
		//		sending values via ajax
		//************************************************

		$('#form-returnee').submit(function(e){

			e.preventDefault();

			// preparing to delete data, that is vulnerable to delete
			let isInputFieldError = false;
			selectedReturnees.forEach( function(product, index, array){

				if (product['quantity'].length <= 0 || product['reason'].length <= 0 || product['status'].length <= 0)
				{
					isInputFieldError = true;

					errorMessage.title = 'Error';
					errorMessage.message = 'Wait, please fill out all the inputs.';

					setErrorMessage();
				}

			});
			// setting the validated data
			setValidData();

			if(selectedReturnees.length > 0 && !isInputFieldError)
			{
				$('#--is-has-error').hide('fast');

				console.log(selectedReturnees);

				axios.post(`/ajax/inventory/returnee`, {
					'_token' : $('[name="_token"]').val(),
					'returnee_date' : $('[name="returnee_date"].for-returnee').val(),
					'reason' : $('[name="reason"].for-returnee').val(),
					'status' : $('[name="status"].for-returnee').val(),
					'returnee_batch_nos' : selectedReturnees,
					'employee_id' : $('[name="employee_id"].for-returnee').val(),
				})
				.then((response) => {
					console.log(response.data);

					// sweet alert
					swal({
						title : response.data['title'],
						text : response.data['text'],
						icon : 'success',
						button : {
							text : 'Okay',
							value : true,
							visible : true,
							className : '',
							closeModal : true,
						},
					});

					getSalesViaAjax();

					orderDetails(salesId);

					$('.for-returnee:not(#--returnee-date):not([name="employee_id"])').val(''); // remove the inputs
				})
				.catch((error) => {

					errorAlert(error);

					console.log(error.response);
				});
			}
			else if(selectedReturnees.length === 0 && !isInputFieldError)
			{
				errorMessage.title = 'Error';
				errorMessage.message = 'Wait you haven\'t selected any of the product to returned.';

				setErrorMessage();

			}

		}); // end of form returnee

	});