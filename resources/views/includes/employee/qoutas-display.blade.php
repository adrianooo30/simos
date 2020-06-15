<div class="card-box">	
    {{-- title --}}
    <h4 class="header-title text-primary mt-0 mb-3">PSR Qoutas</h4>
	
	{{-- psr qouta cards --}}
	<div class="row">

		{{-- START OF SINGLE CARD FOR PSR --}}
		<div class="col-xl-6  mx-auto --product-in-cart">
			<div class="card card-shadow">
				<div class="card-header d-flex justify-content-between">
					<div class="d-flex">
						<img src="{{ $employee['profile_img'] ?? '/images/users/user.png' }}" alt="" class="img-thumbnail image-50 mx-2">
						<div class="mx-2">
							<h5 class="text-primary">{{ $employee['full_name'] ?? 'Adrianooo, Valero Z.' }}</h5>
							<h6 class="text-muted">{{ $employee['position']['position_name'] ?? 'PSR' }}</h6>
						</div>
					</div>
				</div>
				<div class="card-body py-0">
					
					{{-- start of a single targets --}}
					<div class="my-3">
						<div class="row">
							<div class="col-xl-6 col-xs-6">
								<div class="row">
									<div class="col-xl-12 col-l-12 col-md-6 col-sm-6">
										<h5 class="text-primary">11-12-2020</h5>
										<sup class="text-muted">Start Date</sup>
									</div>
									<div class="col-xl-12 col-l-12 col-md-6 col-sm-6 col-xs-6">
										<div class="single-chart">
											<svg viewbox="0 0 36 36" class="circular-chart blue">
											  <path class="circle-bg"
											    d="M18 2.0845
											      a 15.9155 15.9155 0 0 1 0 31.831
											      a 15.9155 15.9155 0 0 1 0 -31.831"
											  />
											  <path class="circle"
											    stroke-dasharray="80, 100"
											    d="M18 2.0845
											      a 15.9155 15.9155 0 0 1 0 31.831
											      a 15.9155 15.9155 0 0 1 0 -31.831"
											  />
											  <text x="18" y="20.35" class="percentage font-weight-bolder text-muted">80%</text>
											</svg>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xl-6 col-xs-6">
								<div class="alert alert-danger">
									<strong>&#8369; 100,001.00</strong><br>
									<sub class="text-muted">Target Amount</sub>
								</div>
								<div class="alert alert-success">
									<strong>&#8369; 100,001.00</strong><br>
									<sub class="text-muted">Achievement</sub>
								</div>
							</div>
						</div>
					</div>
					<hr>
					{{-- end  of a single targets --}}

					{{-- start of a single targets --}}
					<div class="my-3">
						<div class="row">
							<div class="col-xl-6 col-xs-6">
								<div class="row">
									<div class="col-xl-12 col-l-12 col-md-6 col-sm-6">
										<h5 class="text-primary">10-12-2021</h5>
										<sup class="text-muted">Start Date</sup>
									</div>
									<div class="col-xl-12 col-l-12 col-md-6 col-sm-6 col-xs-6">
										<div class="single-chart">
											<svg viewbox="0 0 36 36" class="circular-chart blue">
											  <path class="circle-bg"
											    d="M18 2.0845
											      a 15.9155 15.9155 0 0 1 0 31.831
											      a 15.9155 15.9155 0 0 1 0 -31.831"
											  />
											  <path class="circle"
											    stroke-dasharray="58, 100"
											    d="M18 2.0845
											      a 15.9155 15.9155 0 0 1 0 31.831
											      a 15.9155 15.9155 0 0 1 0 -31.831"
											  />
											  <text x="18" y="20.35" class="percentage font-weight-bolder text-muted">58%</text>
											</svg>
										</div>
									</div>
								</div>
							</div>

							<div class="col-xl-6 col-xs-6">
								<div class="alert alert-danger">
									<strong>&#8369; 100,001.00</strong><br>
									<sub class="text-muted">Target Amount</sub>
								</div>
								<div class="alert alert-success">
									<strong>&#8369; 100,001.00</strong><br>
									<sub class="text-muted">Achievement</sub>
								</div>
							</div>
						</div>
					</div>
					<hr>
					{{-- end  of a single targets --}}

				</div>

			</div>
		</div>
		{{-- END OF SINGLE CARD FOR PSR --}}

	</div>
	{{-- end of row --}}

</div>