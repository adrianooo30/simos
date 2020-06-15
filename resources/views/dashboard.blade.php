@extends('layouts.app')
@section('title', 'Dashboard Analytics')
@section('title-of-page')
	<i class="ti-bar-chart"></i> <span>Dashboard Analytics</span>
@endsection

@section('styles')
    
    <!--Morris Chart-->
    {{-- <link rel="stylesheet" href="{{ asset('/assets/libs/morris-js/morris.css') }}" /> --}}

    <link rel="stylesheet" type="text/css" href="{{ asset('/plugins/chartjs/Chart.min.css') }}">

@endsection

@section('content')

  <div class="row">

    <div class="col-xl-4 col-md-6">

        <div class="card card-shadow p-3">
            <div class="d-flex justify-content-between py-2">
                <div class="text-center">
                    {{-- <img src="{{ asset('/images/products/syringe.png') }}" alt="" class="img-fluid" width="50" height="50"> --}}
                    <i class="fas fa-syringe font-42"></i>
                </div>
                <div class="text-right">
                    <h5 class="text-muted">
                        Total Products Out
                    </h5>
                    <h2 class="text-primary font-weight-lighter">
                        123 pcs.
                    </h2>
                    <sup class="text-muted font-weight-bold">For the month of {{ now()->format('F') }}</sup>
                </div>
            </div>
            
            <div class="border-top pt-2">
                <a href="#" class="font-weight-bold d-block" data-toggle="modal" data-target="#products-out-summarized-modal">
                    See summarized data
                </a>
                <a href="{{ route('inventory.products') }}" class="font-weight-bold d-block">
                    Go to page
                </a>
            </div>
        </div>

    </div><!-- end col -->

    <div class="col-xl-4 col-md-6">
        
        <div class="card card-shadow p-3">
            <div class="d-flex justify-content-between py-2">
                <div class="">
                    <i class="ti-receipt text- font-42"></i>
                </div>
                <div class="text-right">
                    <h5 class="text-muted">
                        Total Sales
                    </h5>
                    <h2 class="text-danger font-weight-lighter">
                        &#8369; 10,230.00
                    </h2>
                    <sup class="text-muted font-weight-bold">For the month of {{ now()->format('F') }}</sup>
                </div>
            </div>
            
            <div class="border-top pt-2">
                <a href="{{ route('transactions.sales') }}" class="font-weight-bold">
                    Go to page
                </a>
            </div>
        </div>

    </div><!-- end col -->

    <div class="col-xl-4 col-md-6">

        <div class="card card-shadow p-3">
            <div class="d-flex justify-content-between py-2">
                <div class="">
                    <i class="ti-receipt text- font-42"></i>
                </div>
                <div class="text-right">
                    <h5 class="text-muted">
                        Total Collections
                    </h5>
                    <h2 class="text-success font-weight-lighter">
                        &#8369; 10,230.00
                    </h2>
                    <sup class="text-muted font-weight-bold">For the month of {{ now()->format('F') }}</sup>
                </div>
            </div>
            
            <div class="border-top pt-2">
                <a href="{{ route('transactions.collections') }}" class="font-weight-bold">
                    Go to page
                </a>
            </div>
        </div>

    </div><!-- end col -->

</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-12">
        <div class="card-box">
            <div class="row">
                <div class="col-xl-6 col-l-6">
                    <h4 class="text-center text-muted">
                        <i class="ti-shopping-cart"></i> Sales for the Month of {{ now()->format('F') }}
                    </h4>
                    <div class="p-2">
                        <canvas id="sales-per-product" style="width: 100%; height: 350px"></canvas>
                    </div>
                </div>
                <div class="col-xl-6 col-l-6">
                    <h4 class="text-center text-muted">
                        <i class="ti-user"></i> Collections for the Month of {{ now()->format('F') }}
                    </h4>
                    <div class="p-2">
                        <canvas id="collections-per-product" style="width: 100%; height: 350px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-xl-4">
        <div class="card-box">

            <div class="dropdown float-right">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">View more details in sales.</a>
                    <a href="javascript:void(0);" class="dropdown-item">View more details in collections.</a>
                </div>
            </div>

            <h4 class="header-title mb-3">
                <i class="ti-user"></i> PSR Qoutas
            </h4>

            <div class="inbox-widget">
                
                <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img">
                            <img src="assets/images/users/user-1.jpg" class="rounded-circle" alt="">
                        </div>
                        <h5 class="inbox-item-author mt-0 mb-1">Mr. Adrian Valera</h5>
                        <p class="inbox-item-text">&#8369; 100,100.00 - Achievement</p>
                        <p class="inbox-item-date"></p>
                    </a>
                </div>

                <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img">
                            <img src="assets/images/users/user-1.jpg" class="rounded-circle" alt="">
                        </div>
                        <h5 class="inbox-item-author mt-0 mb-1">Mr. Adrian Valera</h5>
                        <p class="inbox-item-text">&#8369; 100,100.00 - Achievement</p>
                        <p class="inbox-item-date"></p>
                    </a>
                </div>

                <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img">
                            <img src="assets/images/users/user-1.jpg" class="rounded-circle" alt="">
                        </div>
                        <h5 class="inbox-item-author mt-0 mb-1">Mr. Adrian Valera</h5>
                        <p class="inbox-item-text">&#8369; 100,100.00 - Achievement</p>
                        <p class="inbox-item-date"></p>
                    </a>
                </div>

            </div>
        </div>
    </div><!-- end col -->
    
    <div class="col-xl-8">
        
        <div class="card-box">
            <div class="dropdown float-right">
                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-dots-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">Proceed to pending orders.</a>
                </div>
            </div>

            <h4 class="header-title mb-3">
                <i class="mdi mdi-notification-clear-all mr-1"></i> Newly created orders.
            </h4>

            <div class="inbox-widget">
                
                <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img"><img src="assets/images/users/user-1.jpg" class="rounded-circle" alt=""></div>
                        <h5 class="inbox-item-author mt-0 mb-1">Account Names</h5>
                        <p class="inbox-item-text">Hospital</p>
                        <p class="inbox-item-date">10-10-2020</p>
                    </a>
                </div>
                
                <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img"><img src="assets/images/users/user-2.jpg" class="rounded-circle" alt=""></div>
                        <h5 class="inbox-item-author mt-0 mb-1">Tomaslau</h5>
                        <p class="inbox-item-text">Clinic</p>
                        <p class="inbox-item-date">10-10-2020</p>
                    </a>
                </div>

                <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img"><img src="assets/images/users/user-2.jpg" class="rounded-circle" alt=""></div>
                        <h5 class="inbox-item-author mt-0 mb-1">Tomaslau</h5>
                        <p class="inbox-item-text">Clinic</p>
                        <p class="inbox-item-date">10-10-2020</p>
                    </a>
                </div>

                <div class="inbox-item">
                    <a href="#">
                        <div class="inbox-item-img"><img src="assets/images/users/user-2.jpg" class="rounded-circle" alt=""></div>
                        <h5 class="inbox-item-author mt-0 mb-1">Tomaslau</h5>
                        <p class="inbox-item-text">Clinic</p>
                        <p class="inbox-item-date">10-10-2020</p>
                    </a>
                </div>


            </div>
        </div>

    </div>
</div> --}}

@endsection

@section('modals')
    <!--  Modal content for the above example -->
    <div id="products-out-summarized-modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-primary">Summarized Products Out</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                   
                   {{-- sections content --}}
                    <table id="products-out-summarized-table" class="table table-striped bg-white text-center" style="width: 100%;">
                        <thead class="text-primary">
                            <td></td>
                            <td></td>
                            <td>Product Name</td>
                            <td>Unit Price</td>
                            <td>Sales Quantity</td>
                            {{-- <td>Price of Sales</td> --}}
                            <td></td>
                        </thead>
                    </table>
                   
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('dashboard-scripts')
	
	{{-- <script src="{{ asset('js/dashboard.js') }}" type="text/javascript"></script> --}}

    <!-- knob plugin -->
    <script src="{{ asset('/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>

    <!--Morris Chart-->
    <script src="{{ asset('/assets/libs/morris-js/morris.min.js') }}"></script>
    <script src="{{ asset('/assets/libs/raphael/raphael.min.js') }}"></script>

    <!-- Dashboard init js-->
    <script src="{{ asset('/assets/js/pages/dashboard.init.js') }}"></script>

@endsection

@section('scripts')
    
    <script src='{{asset('/plugins/chartjs/Chart.min.js')}}' type="text/javascript"></script>

    <script src="{{ asset('/js/dashboard.js') }}"></script>

@endsection