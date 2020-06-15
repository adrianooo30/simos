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
                <label>Account Name</label>
                <input type="text" name="account_name" parsley-trigger="change" required
                       placeholder="Account Name" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Type of Account</label>
                <select name="type" class="form-control {{ $inputClassName }}">
                	<option value="" disabled="display" selected>--Select Type--</option>
					<option>Pharmacy</option>
					<option>Hospital</option>
					<option>Clinic</option>
                </select>
            </div>

            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" parsley-trigger="change" required
                       placeholder="--address--" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="number" name="contact_no" parsley-trigger="change" required
                       placeholder="--contact no--" class="form-control {{ $inputClassName }}">
            </div>

            <div class="form-group">
                <label>Contact Person</label>
                <input type="text" name="contact_person" parsley-trigger="change" required
                       placeholder="--contact person--" class="form-control {{ $inputClassName }}">
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