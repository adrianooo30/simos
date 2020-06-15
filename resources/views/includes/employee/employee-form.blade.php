<div class="row">
	<div class="col-md-6">
		<div class="card-box">

            <h4 class="header-title mt-0 mb-3">Select Custom Image Here</h4>

            <input type="hidden" name="profile_img_hidden" value="/images/users/user.png" class="{{ $inputClassName }}">

            <input type="file" name="profile_img" class="dropify {{ $inputClassName }}" data-default-file="{{ asset('/images/users/user.png') }}" />
        </div>
	</div>

	<div class="col-md-6">
		<div class="card-box">
            @csrf
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="fname" parsley-trigger="change" required
                       placeholder="First Name" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="mname" parsley-trigger="change" required
                       placeholder="Middle Name" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="lname" parsley-trigger="change" required
                       placeholder="Last Name" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Postion</label>
                <select name="role_id" class="form-control {{ $inputClassName }}">
                	<option value="">-Select Position-</option>
					@foreach($roles as $role)
						<option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
					@endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_no" parsley-trigger="change" required
                       placeholder="Contact Number" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="text" name="email" parsley-trigger="change" required
                       placeholder="Email Address" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" parsley-trigger="change" required
                       placeholder="Address" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>User Name</label>
                <input type="text" name="username" parsley-trigger="change" required
                       placeholder="User Name" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" parsley-trigger="change" required
                       placeholder="Password" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group text-right mb-0">
                <button class="btn btn-primary waves-effect waves-light mr-1" type="submit">
                    {{ $saveBtnName }}
                </button>
                <button type="reset" class="btn btn-secondary waves-effect waves-light">
                    CANCEL
                </button>
            </div>

        </div>
	</div>
</div>