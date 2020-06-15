	
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//					SHOWING OF DEEP DETAILS
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	
	var orderTransactionId;

	function showDetails(id){

		// to access this via global
		orderTransactionId = id;

		// remove all old datas
		$('#--returnee-details').empty();

		$('#--details').hide();
		$('#--order-loading').show();

		axios.get('/ajax/inventory/returnee/'+id)
			.then((response) => {

				$('#--order-loading').hide();
				$('#--details').show();

				console.log(response.data);

				let returnee = response.data;

				let replacementDetails = '',
					replacementBatchNo = '',

					returnPaymentDetails = '',

					doneButton = '',
					successIcon = '';

				switch(returnee['status'])
				{
					case 'replace':

						returnee['changed_batch_no'].forEach(function(changedBatchNo, index){
							replacementBatchNo += `
								<tr>
								    <td></td>
								    <td class="text-primary">${ changedBatchNo['batch_no']['batch_no'] }</td>
								    <td>${ changedBatchNo['quantity'] } pcs.</td>
								    <td></td>
								</tr>
							`;
						});

						replacementDetails = `
							<h6 class="text-primary text-center">Batch Number for Replacement</h6>
							
							<table class="table table-striped replacement-table">
				                <thead>
				                    <tr>
				                        <td></td>
				                        <td>BATCH NUMBER</td>
				                        <td>QUANTITY</td>
				                        <td></td>
				                    </tr>
				                </thead>
				                <tbody>
									${ replacementBatchNo }
				                </tbody>
				            </table>
						`;

						if( !returnee['replace']['enough_supply'] )
						{
							replacementDetails = `
								<h6 class="text-secondary">Not enough supply for replacing the returned batch number.</h6>
							`;
						}

						doneButton = `
							<button type="button" class="btn btn-outline-primary mx-1" onclick="success(${ returnee['id'] }, '${ returnee['status'] }')">
							    <i class="dripicons-thumbs-up"></i> Done Task
							</button>
						`;

						if(returnee['replace']['success'])
						{
							doneButton = '';
							successIcon = `
								<div class="alert alert-success text-center">
									<strong>Success</strong> The replaced product is now at the customers hand.
								</div>
							`;
						}
					break;

					case 'return payment':
						returnPaymentDetails = `
							<h6 class="text-primary">${ returnee['order_transaction']['total_bill_format'] }</h6>
						    <sup class="text-muted">Updated Total Bill for 
						        <a href="/transactions/sales?orderTransaction=${ returnee['order_transaction']['id'] }" class="text-primary">${ returnee['order_transaction']['deliver_transaction']['receipt_no'] }</a>
						    </sup>
						    
						    <h6 class="text-warning">${ returnee['order_transaction']['excess_payment_format'] }</h6>
						    <sup class="text-muted">Excess Payment for 
						        <a href="/transactions/sales?orderTransaction=${ returnee['order_transaction']['id'] }" class="text-primary">${ returnee['order_transaction']['deliver_transaction']['receipt_no'] }</a>
						    </sup>
						`;
						doneButton = `
							<button type="button" class="btn btn-outline-primary mx-1" onclick="success(${ returnee['id'] }, '${ returnee['status'] }')">
							    <i class="dripicons-thumbs-up"></i> Done Task
							</button>
						`;
						// only show if their are excess payment...
						if(returnee['return_payment']['success'])
						{
							doneButton = '';
							successIcon = `
								<div class="alert alert-success text-center">
									<strong>Success</strong> The excess payment for <a href="/transactions/sales?orderTransaction=${ returnee['order_transaction']['id'] }" class="text-primary">${ returnee['order_transaction']['deliver_transaction']['receipt_no'] }</a> is now at the customers hand.
								</div>
							`;
						}
					break;
				}

				$('#--returnee-details').append(`
					<div class="d-flex">
						<div class="image">
							<img src="${ returnee['product']['product_img'] }" class="profile image-100" id="product-img-display"><br>
						</div>
						<div class="first-section">
							<h3 id="generic-strength-display my-2">${ returnee['product']['generic_name&strength'] }</h3>
							<h4 class="lighter" id="brand-display my-2">${ returnee['product']['brand_name'] }</h4>
						</div>
					</div>

					<div class="card not-clickable-card p-0">
						<div class="container-fluid p-0">
							<div class="card-header d-flex justify-content-between">
								<h5 class="text-muted">${ returnee['batch_no']['batch_no'] } - ${ returnee['quantity'] } pcs.</h5>
								<div>
									<h5 class="text-danger">${ returnee['total_cost_format'] }</h5>
									<sup class="text-muted">Total Cost of Returned</sup>
								</div>
							</div>
							<div class="card-body">
								<h6 class="text-danger">Reason - ${ returnee['static_reason'] }</h6>
								<p class="text-muted">${ returnee['status_sentence'] }</p>
								
								${ replacementDetails }

								${ returnPaymentDetails }

								${ successIcon }

							</div>
							<div class="card-footer d-flex justify-content-end">
								${ doneButton }
							</div>
						</div>
					</div>
				`);

			})
			.catch((error) => {

				console.log(error);

			});
	}


	function success(returnee, status)
	{
		let title;
		switch(status)
		{
			case 'replace' :
				title = 'Are you sure the replaced products is delivered to the customer?';
			break;
			case 'return payment' :
				title = 'Are you sure the excess payment is delivered to the customer?';
			break;
		}
		// REPLACE
		swal({
			icon : 'warning',
			title : title,
			text : 'If yes, then click the confirm button. :)',
			buttons : {
				cancel : 'Cancel',
				confirm : {
					text : 'Confirm',
					value : 'confirm',
				},
			},
		})
		.then((value) => {
			// confirm
			if(value === 'confirm')
			{
				axios.patch('/ajax/inventory/returnee/success/'+returnee,{
					'status' : status,
				})
				.then((response) => {
					swal({
						icon : 'success',
						title : response.data.title,
						text : response.data.text,
						button : {
							text : 'Okay',
						}
					});
					console.log(response.data);
					// refreshing the modal, by calling one method, called getting of data via ajax
					showDetails(orderTransactionId);
				});
			}
		});
	}
