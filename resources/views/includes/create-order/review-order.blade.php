<div class="col">
	<div class="card">
		<div class="card-body p-0">
			<div class="row">

				<div class="col-xl-3 col-lg-3 col-md-3">
					<div class="card">
						<div class="card-body p-0 d-flex justify-content-center">
							<img src="{{ asset('/images/users/user2.png') }}" class="img-thumbnail image-100">
						</div>
					</div>
				</div>

				<div class="col-xl-9 col-lg-9 col-md-9">
					<div class="card">
						<div class="card-body p-0">
							<div class="row text-center">
								<div class="col-xl-6 col-lg-6 col-md-6">
									<h3 class="text-primary font-weight-light" id="--selected-account">No Selected Account</h3>
									<h6 class="text-muted font-weight-light" id="--selected-account-type">No Selected Account</h6>

									<h3 class="text-primary font-weight-light" id="--total-cost-of-order">&#8369; 0.00</h3>
									<h6 class="text-muted">Total Cost of Order</h6>
								</div>
								<div class="col-xl-6 col-lg-6 col-md-6">
									<h3 class="text-primary font-weight-light">{{ now()->toDateString() }}</h3>
									<h6 class="text-muted">Date of Order</h6>

									<input type="hidden" name="employee_id" class="for-send-order" value="{{ Auth::id() }}">

									<h3 class="text-primary font-weight-light">{{ Auth::user()['full_name'] }}</h3>
									<h6 class="text-muted">Employee Assigned</h6>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

{{-- <table id="cart-table" class="table table-striped bg-white text-center" style="width: 100%;">
		<thead class="text-primary">
			<td></td>
			<td>Product Name</td>
			<td>Total Quantity</td>
			<td>Total Cost</td>
			<td></td>
		</thead>
		<tbody>
			<tr>
				<td>some data here...</td>
				<td>some data here...</td>
				<td>some data here...</td>
				<td>some data here...</td>
				<td>some data here...</td>
			</tr>
		</tbody>
	</table> --}}

	<h4 class="text-primary text-center my-3">
		<i class="ti-shopping-cart"></i> List of Ordered Products
	</h4>

	<div class="row" id="--products-in-cart">

		<div class="col-xl-6  mx-auto">
			<div class="card card-shadow">
				<div class="card-body d-flex justify-content-center">
					<h3 class="text-danger text-center">Empty Cart.</h3>
				</div>
			</div>
		</div>


{{-- 		<div class="col-xl-6  mx-auto">
			<div class="card card-shadow">
				<div class="card-header d-flex justify-content-between">
					<div class="d-flex">
						<img src="{{ asset('/images/products/syringe.png') }}" alt="" class="img-thumbnail image-50 mx-2">

						<div class="mx-2">
							<h5 class="text-primary">Generic Name 10mg</h5>
							<sup>Brand Name</sup>
						</div>
					</div>

					<button type="button" class="btn btn-icon waves-effect btn-danger float-right" onclick="removeFromCart(1)">
                           <i class="ti-trash"></i>
                    </button>

				</div>
				<div class="card-body d-flex justify-content-center">
					<div class="mx-2 text-center">
						<div class="alert alert-danger">
							<strong>100 + 10 pcs.</strong>
						</div>
						<sup class="font-weight-bolder">Total Quantity</sup>
					</div>
					<div class="mx-2 text-center">
						<div class="alert alert-primary">
							<strong>&#8369; 1010.00</strong>
						</div>
						<sup class="font-weight-bolder">Total Cost</sup>
					</div>
				</div>
			</div>
		</div> --}}

	</div>

	<hr>

	<div class="d-flex justify-content-center">
            <form id="--send-order-form">
            	<button type="submit" class="btn btn-primary waves-effect width-lg waves-light mx-1">
	                <i class="far fa-paper-plane"></i> SEND ORDER
	            </button>
            </form>
        </div>

</div>