@extends('layouts.app')
@section('title', 'Accounts Receivable')
@section('title-of-page')
	<i class="ti-receipt"></i> <span>Accounts Receivable</span>
@endsection

@section('styles')
	{{-- datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-bs4/css/dataTables.bootstrap4.css') }}"/>
	{{-- responsive datatables --}}
	<link rel="stylesheet" type="text/css" href="{{ asset('/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.css') }}">
@endsection

@section('content')

	{{-- <div class="card my-3">
		<div class="card-body row p-2">
			<div class="col-xl-4">
				<form id="search-form">
					<div class="form-group">
						<label for="" class="text-primary">Search Accounts</label>
						<div class="input-group">
							<input type="search" name="search" class="form-control" placeholder="--search here--">
							<div class="input-group-append input-group-addon">
								<button type="button" class="btn btn-primary btn-icon waves-effect wave-light">
									<i class="ti-search"></i>
								</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div> --}}
	
	{{-- <div class="card my-3">

		<div class="card-body">
	
			<h4 class="text-primary font-weight-lighter my-2">
				<i class="ti-user"></i> Account Receivables List
			</h4>

			<div class="row">
				<div class="col">
					<h3 id="receivables-loading" class="text-center text-muted lighter my-3">
						Loading account receivables... Please wait.
					</h3>
			        <div id="receivables-content">
			        	<div id="receivables-list">
			        		@receivablesList
			        	</div>
			        </div>
				</div>
			</div>
		</div>
	</div> --}}

	<div class="row my-2">
    	<div class="col-xl-12">
            <div class="card-box">

                {{-- title --}}
                <h4 class="header-title text-primary mt-0 mb-3">List of Accounts with Bills</h4>

                {{-- sections content --}}
				<table id="receivables-table" class="table table-striped bg-white text-center" style="width: 100%;">
					<thead class="text-primary">
						<td></td>
						<td></td>
						<td>Account Name</td>
						<td></td>
						<td></td>
					</thead>
				</table>

            </div>
        </div><!-- end col -->
    </div>

@endsection

@section('scripts')

	{{-- plugin datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
	
	{{-- responsive datatables --}}
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.js') }}"></script>

	<script src="{{asset('js/receivable/index.js')}}" type="text/javascript"></script>
	<script src="{{asset('js/datatables/receivables-index.js')}}" type="text/javascript"></script>

@endsection