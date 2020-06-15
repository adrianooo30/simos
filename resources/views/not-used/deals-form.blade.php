<div id="btnwizard">
    <ul class="nav nav-pills bg-light nav-justified form-wizard-header mb-2">
        <li class="nav-item">
            <a href="#tab-select-product" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                <i class="mdi mdi-account-circle mr-1"></i>
                <span class="d-none d-sm-inline">Select Product</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#tab-deal-name" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                <i class="mdi mdi-face-profile mr-1"></i>
                <span class="d-none d-sm-inline">Set Deal's Name</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#tab-select-account" data-toggle="tab" class="nav-link rounded-0 pt-2 pb-2">
                <i class="mdi mdi-checkbox-marked-circle-outline mr-1"></i>
                <span class="d-none d-sm-inline">Add Accounts to Deal</span>
            </a>
        </li>
    </ul>

    <div class="tab-content mb-0 border-0">

        <div class="tab-pane fade" id="tab-select-product">

			<div class="card my-1">
				<div class="card-body row p-1">
					<div class="col-xl-6">
						{{--  --}}
						<div class="form-group">
							<label class="text-primary">Search Here</label>
								<div class="input-group">
									<input type="search" class="form-control" placeholder="--search here--">
                                    <div class="input-group-append">
                                        <span class="input-group-addon">
                                        	<button class="btn btn-icon waves-effect btn-primary">
												<i class="fas fa-search"></i>
											</button>
                                        </span>
                                    </div>
                                </div><!-- input-group -->
							<small class="form-text text-muted">Type here your product you want to search.</small>
						</div>
					</div>
				</div>
			</div>

            <div class="row">

                <div class="col-xl-6  mx-auto">
                    <div class="card-box widget-user card-shadow">
                        <div>
                            <div class="avatar-lg float-left mr-3">
                                <img src="{{ asset('/images/products/syringe.png') }}" class="img-fluid rounded-circle" alt="user">
                            </div>
                            <div class="wid-u-info">
                                <h5 class="mt-0">Generic Name 10ml</h5>
                                <p class="text-muted mb-1 font-13 text-truncate">Brand Name</p>
                                <h5 class="text-primary lighter"><b>&#8369; 100,000.00</b></h5>

                                <button type="submit" class="button pulse float-right">
							        SELECT
							    </button>

							    <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->

            </div> <!-- end row -->
        </div>

        <div class="tab-pane fade" id="tab-deal-name">
            <div class="row">
                <div class="col-xl-8  mx-auto">

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label text-primary"> Buy</label>
                        <div class="col-md-8">

                            <div class="input-group">
                            	<input type="text" name="buy" class="form-control text-center" value="" placeholder="--quantity for buy--">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                    	pcs.
                                    </span>
                                </div>
                            </div><!-- input-group -->

                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-md-2 col-form-label text-primary"> Take</label>
                        <div class="col-md-8">

                            <div class="input-group">
                            	<input type="text" name="buy" class="form-control text-center" value="" placeholder="--quantity to take--">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                    	pcs.
                                    </span>
                                </div>
                            </div><!-- input-group -->

                        </div>
                    </div>

                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>

        <div class="tab-pane" id="tab-select-account">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <h2 class="mt-0"><i class="mdi mdi-check-all"></i></h2>
                        <h3 class="mt-0">Thank you !</h3>

                        <p class="w-75 mb-2 mx-auto">Quisque nec turpis at urna dictum luctus. Suspendisse convallis dignissim eros at volutpat. In egestas mattis dui. Aliquam
                            mattis dictum aliquet.</p>

                        <div class="mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customCheck2">
                                <label class="custom-control-label" for="customCheck2">I agree with the Terms and Conditions</label>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    
        <div class="float-right">
            <input type='button' class='btn btn-secondary button-next' name='next' value='Next' />
            <input type='button' class='btn btn-secondary button-last' name='last' value='Last' />
        </div>
        <div class="float-left">
            <input type='button' class='btn btn-secondary button-first' name='first' value='First' />
            <input type='button' class='btn btn-secondary button-previous' name='previous' value='Previous' />
        </div>

        <div class="clearfix"></div>

    </div> <!-- tab-content -->
</div> <!-- end #btnwizard-->