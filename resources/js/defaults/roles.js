
function getModules_and_theirMethods()
{
	axios.get('/ajax/roles/modulesAndTheirMethods')
		.then((response) => {

			console.log(response.data);

			let modules = response.data;

			$('#modal-collapse').empty();

			modules.forEach(function(module, index){

				let moduleMethods = '';

				module['methods'].forEach(function(method, index){

					moduleMethods += `
					    <li class="list-group-item text-primary d-flex flex-row">
					    	<div class="custom-control custom-checkbox m-0">
							    <input type="checkbox" class="custom-control-input checkbox-o --method-checkbox-o for-add" id="method-${ module['access_name'] }-checkbox-o${ index }" value="${ method['method'] }">
							    <label class="custom-control-label" for="method-${ module['access_name'] }-checkbox-o${ index }"></label>
							</div>
							<label for="method-${ module['access_name'] }-checkbox-o${ index }" class="text-primary imp">${ method['method'] }</label>
					    </li>
					`;

				});

				$('#modal-collapse').append(`
					<div class="card-body py-0">
						<!--  -->
						<div class="--module-parent">
							<div class="card-header d-flex flex-row">
								<div class="custom-control custom-checkbox m-0">
								    <input type="checkbox" class="custom-control-input checkbox-o --module-checkbox-o for-add" id="module-${ module['access_name'] }-checkbox-o${ index }" data-module="${ module['module'] }" data-access-name="${ module['access_name'] }">
								    <label class="custom-control-label" for="module-${ module['access_name'] }-checkbox-o${ index }"></label>
								</div>
								<a class="card-link" data-toggle="collapse" href="#sub-collapse-${ index }">
									${ module['module'] }
								</a>
							</div>
							<div id="sub-collapse-${ index }" class="collapse --sub-collapse ${ module['access_name'] }${ index }" data-parent="#modal-collapse">

								<div class="card-body">
									<ul class="list-group">
									    ${ moduleMethods }
									</ul>
								</div>

							</div>
						</div>
						<!--  -->
					</div>
				`);

			});

		})
		.catch((error) => {

			console.log(error);

		});
}

// immediately invokable function
(function(){ getModules_and_theirMethods(); })();

// get all positions

var Positions;

function getPositions_and_theirRoles()
{
	axios.get('/ajax/roles')
		.then((response) => {

			// refresh the content
			$('#--positions').empty();

			console.log(response.data);

			// get the response
			Positions = response.data;

			Positions.forEach(function(position, index){

				let modulesAndTheirMethods = '';

				position['modules'].forEach(function(module, index){

					let methods = '';

					let noOfChecked = 0;
					module['methods'].forEach(function(method, index){

						let checkedIfAllowed = '';

						if(method['allowed']){
							checkedIfAllowed = 'checked';
							noOfChecked++;
						}

						methods += `
						<li class="list-group-item text-primary d-flex flex-row">
					    	<div class="custom-control custom-checkbox m-0">
							    <input type="checkbox" class="custom-control-input checkbox-o --method-checkbox-o for-update" id="method-checkbox-o${ method['id'] }" autocomplete="off" ${ checkedIfAllowed } data-access-name="${ module['access_name'] }" data-module-id="${ method['id'] }">
							    <label class="custom-control-label" for="method-checkbox-o${ method['id'] }"></label>
							</div>
							<label for="method-checkbox-o${ method['id'] }" class="text-primary imp">${ method['method_name'] }</label>
					    </li>
						`;

					});

					let checkedIfAllChecked = '';
					if(noOfChecked === module['methods'].length)
						checkedIfAllChecked = 'checked';


					modulesAndTheirMethods += `
					<div class="--module-parent">
						<div class="card-header d-flex flex-row">
							<div class="custom-control custom-checkbox m-0">
							    <input type="checkbox" class="custom-control-input checkbox-o --module-checkbox-o" id="module-checkbox-o${ module['id'] }" autocomplete="off" ${ checkedIfAllChecked } data-access-name="${ module['access_name'] }${ module['id'] }">
							    <label class="custom-control-label" for="module-checkbox-o${ module['id'] }"></label>
							</div>
							<a class="card-link" data-toggle="collapse" href="#sub-collapse-${ module['id'] }">
								${ module['module_name'] }
							</a>
						</div>
						<div id="sub-collapse-${ module['id'] }" class="collapse --sub-collapse ${ module['access_name'] }${ module['id'] }" data-parent="#collapse-${ position['id'] }">

							<div class="card-body">
								<ul class="list-group">
								    ${ methods }
								</ul>
							</div>

						</div>
					</div>
					`;

				});

				$('#--positions').append(`
				<div class="card not-custom-card my-4 --parent-card">

					<div class="card-header">
						<input type="hidden" name="position" class="for-update" value="${ position['id'] }">
						<a class="card-link" data-toggle="collapse" href="#collapse-${ position['id'] }">
							<i class="ti-user"></i> ${ position['position_name'] }
						</a>
					</div>

					<div id="collapse-${ position['id'] }" class="collapse" data-parent="#--positions">
						<form class="--update-role-form">
							<div class="card-body">
								<!--  -->
								${ modulesAndTheirMethods }
								<!--  -->
							</div>

							<div class="card-footer">
								<button type="submit" class="btn btn-outline-primary">SAVE CHANGES</button>
							</div>
						</form>
					</div>

				 </div>
			`);

			});
			

		})
		.catch((error) => {

			console.log(error);

		});
}

getPositions_and_theirRoles();


// ********************************************************
//						DYNAMIC CHECKBOX
// ********************************************************
// clicks the checkbox
$('#--positions').on('change', '.--module-checkbox-o', function(){

	$(this).closest('.--module-parent').find(`.--method-checkbox-o`).prop('checked', $(this).prop('checked'));

});

$('#--positions').on('change', '.--method-checkbox-o', function(){

	if( $(this).prop('checked') === false )
		$(this).closest(`.--module-parent`).find(`.--module-checkbox-o`).prop('checked', false);

	// all list of methods from one single module
	let module_CheckBox = $(this).closest('.list-group').find('li .--method-checkbox-o');

	// counting the number of checked box
	let numberOf_checkedBox = 0;
	$.each(module_CheckBox, (index, object) => {
		if( $('#'+object.id).prop('checked') )
			numberOf_checkedBox++;
	});

	if(numberOf_checkedBox === module_CheckBox.length){
		$(this).closest(`.--module-parent`).find(`.--module-checkbox-o`).prop('checked', true);
	}

	console.log(numberOf_checkedBox);


});

// ********************************************************
//				DYNAMIC FORM
// ********************************************************

$('#--positions').on('submit', '.--update-role-form', function(e){

	let method_ofModules = [];

	e.preventDefault();

	let methodCheckbox = $(this).find('.--method-checkbox-o.for-update');

	let countChecked = 0;
	// set the methods selected
	$.each(methodCheckbox, (index, object, array) => {
		// module, methods, access_name
		if( $('#'+object.id).prop('checked') ){
			method_ofModules.push({ 'id': object.dataset.moduleId, 'allowed' : true });
			countChecked++;
		}
		else
			method_ofModules.push({ 'id': object.dataset.moduleId, 'allowed' : false });
	});

	console.log(method_ofModules);

	// sending of request
	if(countChecked > 0)
	{
		// passing through ajax
		axios.patch(`/ajax/roles/update`, {
			'methods' : method_ofModules,
		})
		.then((response) => {

			console.log(response.data);

			getPositions_and_theirRoles();

			// sweet alert
			swal({
				title : response.data['title'],
				text : response.data['text'],
				icon : 'success',
				button : {
					text : 'Okay',
					value : true,
					visible : true,
					className : '',
					closeModal : true,
				},
			});

			// refresh the page
			window.location.assign('/roles');

		})
		.catch((error) => {

			console.log(error.response);

			errorAlert(error.response);

		});
	}
	else
	{
		swal({
			title : 'Error! Wait.',
			text : 'Please select atleast one module or its method/s.',
			icon : 'error',
			button : {
				text : 'Okay',
				value : true,
				visible : true,
				className : '',
				closeModal : true,
			},
		});
	}


});

// ********************************************************
//						STATIC CHECKBOX
// ********************************************************
$('#--add-position-form').on('change', '.--module-checkbox-o', function(){

	$(this).closest('.--module-parent').find(`.--method-checkbox-o`).prop('checked', $(this).prop('checked'));

});

$('#--add-position-form').on('change', '.--method-checkbox-o', function(){

	if( $(this).prop('checked') === false )
		$(this).closest(`.--module-parent`).find(`.--module-checkbox-o`).prop('checked', false);

	// all list of methods from one single module
	let module_CheckBox = $(this).closest('.list-group').find('li .--method-checkbox-o');

	// counting the number of checked box
	let numberOf_checkedBox = 0;
	$.each(module_CheckBox, (index, object) => {
		if( $('#'+object.id).prop('checked') )
			numberOf_checkedBox++;
	});

	if(numberOf_checkedBox === module_CheckBox.length){
		$(this).closest(`.--module-parent`).find(`.--module-checkbox-o`).prop('checked', true);
	}

	console.log(numberOf_checkedBox);

});

//**************************************************
//			STATIC FORM
//**************************************************
// add new position
$('#--add-position-form').submit(function(e){

	var assignedRoles = [];

	e.preventDefault();

	let moduleCheckbox = $('.--module-checkbox-o.for-add');

	let countChecked = 0;
	// set the methods selected
	$.each(moduleCheckbox, (index, object, array) => {
		let assignedRole = { module: '', access_name: '', methods: '' };
		// module, methods, access_name
		assignedRole['module'] = object.dataset.module;
		assignedRole['access_name'] = object.dataset.accessName;
		assignedRole['methods'] = [];

		let methodsOfModule = $('#'+object.id).closest('.--module-parent').find('.--method-checkbox-o.for-add');

		$.each(methodsOfModule, (subIndex, subObject) => {

			if( $('#'+subObject.id).prop('checked') ){
				assignedRole['methods'].push({ 'method' : subObject.value, 'allowed':  true});
				countChecked++;
			}
			else
				assignedRole['methods'].push({ 'method' : subObject.value, 'allowed': false });

		});

		assignedRoles.push(assignedRole);
	});

	console.log(assignedRoles);

	if(countChecked > 0)
	{
		// passing through ajax
		axios.post(`/ajax/roles`, {
			'position' : $('[name="position"].for-position-add').val(),
			'assigned_roles' : assignedRoles,
		})
		.then((response) => {
			
			// will refresh the table
			getPositions_and_theirRoles();

			// will refresh the adding of position
			$('[name="position"].for-position-add').val('');
			getModules_and_theirMethods();

			// sweet alert
			swal({
				title : response.data['title'],
				text : response.data['text'],
				icon : 'success',
				button : {
					text : 'Okay',
					value : true,
					visible : true,
					className : '',
					closeModal : true,
				},
			});

			closeModal('add-position-modal');

		})
		.catch((error) => {

			console.log(error.response);

			errorAlert(error.response);

		});
	}
	else
	{
		swal({
			title : 'Error! Wait.',
			text : 'Please select atleast one module or its method/s.',
			icon : 'error',
			button : {
				text : 'Okay',
				value : true,
				visible : true,
				className : '',
				closeModal : true,
			},
		});
	}

});