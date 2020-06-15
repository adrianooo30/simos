		<!-- this is for editing of accounts -->
	<div id="update-current-employee-modal" class="parent-modal">
		<div onclick="closeModal('update-current-employee-modal'), removeInputValues(), removeErrorMessages()" class="bg-for-modal"></div>
		<div class="modal-box edit-emp-modal user-modal">
			<i class="fas fa-times" onclick="closeModal('update-current-employee-modal'), removeInputValues(), removeErrorMessages()"></i>
			<h2><i class="ti-pencil-alt"></i> Edit Employee</h2>

		<h1 id="--loading" class="--loading" style="display: none;">Loading... Please Wait</h1>
	
	<div id="--update-current-employee">
		<form method="POST" id="form-update-current-employee">

			@csrf
			@method('PATCH')

			<div class="tab active">
				<div class="image">
					<input type="hidden" name="profile_img" id="--e-current-profile-img-hidden" class="for-update">

					<img src="/images/users/user.png" id="--e-current-profile-img" class="profile"><br>
					
					<label for="file-e" class="btn btn-outline-primary choose-file">
						<i class="ti-image"></i> CHOOOSE FILE
					</label>
					<input type="file" id="file-e" onchange="displayImgFile(event, '--e-profile-img')"><br>

					<span>Click me to view more default images.</span>
					<ul id="default-imgs2" class="default-imgs">
						<li onclick="toggleImgUser('--e-profile-img', '/images/users/user.png')">
							<img class="choose-img" src="/images/users/user.png">
						</li>
						<li onclick="toggleImgUser('--e-profile-img', '/images/users/user2.png')">
							<img class="choose-img" src="/images/users/user2.png">
						</li>
						<li onclick="toggleImgUser('--e-profile-img', '/images/users/user3.png')">
							<img class="choose-img" src="/images/users/user3.png">
						</li>
						<li onclick="toggleImgUser('--e-profile-img', '/images/users/user4.png')">
							<img class="choose-img" src="/images/users/user4.png">
						</li>
					</ul><br>
				</div>
				<div class="edit add-account">
					<div class="input-box">
						<label>First Name</label>
						<div class="input-field">
							<input type="text" name="fname" id="id_e_fname" placeholder="First Name" class="for-update">
						</div>
					</div>
					<div class="input-box">
						<label>Middle Name</label>
						<div class="input-field">
							<input type="text" name="mname" id="id_e_mname" placeholder="Middle Name" class="for-update">
						</div>
					</div>
					<div class="input-box">
						<label>Last Name</label>
						<div class="input-field">
							<input type="text" name="lname" id="id_e_lname" placeholder="Last Name" class="for-update">
						</div>
					</div><br>
					<div class="input-box">
						<label>Position</label>
						<div class="input-field">
							<select name="position" id="id_e_position" class="for-update">
								<option value="" disabled="display" selected>-Select Position-</option>
							</select>
						</div>
					</div><br>
					<div class="input-box">
						<label><i class="ti-mobile"></i> Contact Number</label>
						<div class="input-field">
							<input type="text" name="contact_no" id="id_e_contact_no" placeholder="Contact Number" class="for-update">
						</div>
					</div>
					<div class="input-box">
						<label><i class="ti-home"></i> Address</label>
						<div class="input-field">
							<input type="text" name="address" id="id_e_address" placeholder="Address" class="for-update">
						</div>
					</div><br>
					<div class="input-box">
						<label><i class="ti-user"></i> Username</label>
						<div class="input-field">
							<input type="text" name="username" id="id_e_username" placeholder="Username" class="for-update">
						</div>
					</div>
					<div class="input-box">
						<label><i class="ti-lock"></i> Password</label>
						<div class="input-field">
							<input type="text" name="password" id="id_e_password" placeholder="Password" class="for-update">
						</div>
					</div><br>

					<div class="d-flex justify-content-center">
						<button type="submit" class="btn btn-outline-primary">
							<i class="ti-write"></i>
							&nbsp;&nbsp;SAVE CHANGES
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>

		</div>
	</div>
	<!-- end of editting of accounts -->