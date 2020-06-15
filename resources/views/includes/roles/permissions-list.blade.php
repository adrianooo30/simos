<form action="{{ route('roles.update', ['role' => $role['id']]) }}" id="permissions-form">

	<h4 class="text-primary"> {{ $role['name'] }}'s Permissions</h4>
	<hr>
	@foreach($modules_and_permissions as $module)
		{{-- if current loop module is role mgt. and position is  admin --}}
		{{-- @if($module['name'] != 'role management' && $position['position_name'] != 'Admin') --}}
			{{-- MODULE --}}
			<div class="card card-shadow">
				<div class="card-header">
					<strong>{{ $module['name'] }}</strong>
				</div>

				<div class="card-body">
					@foreach($module->permissions as $permission)

						@php
							$checked = '';
							foreach($role->permissions  as $roles_permissions) {
								if($roles_permissions['name'] == $permission['name']) {
									$checked = 'checked';
								}
							}

							// if first index
							$loop->first ? $alertColor = 'alert-success' : $alertColor = 'alert-secondary';
							$loop->first ? $access = 'permission-parent' : $access = 'permission-child';
						@endphp

						<label for="{{ $loop->index }}-{{ $permission['name'] }}" class="alert {{ $alertColor }} mx-1  clickable-alert">
							<strong>{{ $permission['name'] }}</strong>
							<div class="custom-control custom-checkbox d-inline mx-1">

							    <input type="checkbox"
							    		name="{{ $permission['name'] }}"
							    		class="custom-control-input --{{ $access }}"
							    		id="{{ $loop->index }}-{{ $permission['name'] }}"
									    value="{{ $permission['name'] }}" {{ $checked }}>

							    <label class="custom-control-label" for="{{ $loop->index }}-{{ $permission['name'] }}"></label>
							</div>
						</label>
					@endforeach
				</div>
			</div>
		{{-- @endif --}}
		{{-- END OF MODULE --}}
	@endforeach

	<div class="d-flex justify-content-center my-2">
		<button type="submit" class="btn btn-primary waves-effect wave-light  font-weight-bold">
			Save Permissions
		</button>
	</div>

</form>