var currentPositionId = 1;
var currentPositionName = 'Admin';

function getPermissionList(position = 1)
{
	currentPositionId = position;

	let loading = $('#permissions-loading');
	let content = $('#permissions-list');

	loading.show();
	content.hide();

	axios.get(`/ajax/roles/${ position }`)
		.then((response) =>{

			loading.hide();
			content.show();

			console.log(response.data);

			$('#permissions-list').html( response.data['modules_and_permissions_html'] );

		})
		.catch((error) => {

			errorAlert(error);

		})
}

getPermissionList();

$('.--position-link').on('click', function(e){

	e.preventDefault();

	$.each( $('.--position-link strong'), (key, object) => $(object).removeClass('text-primary') )

	$(this).find('strong').addClass('text-primary');

	// current position
	currentPositionName = $(this).find('strong span').text();

	if( currentPositionId != $(this).attr('data-position') )
		getPermissionList( $(this).attr('data-position') );

});


// sync permissions to position
$('body').on('submit', '#permissions-form',function(e){

	e.preventDefault();

	console.log( $(this).serializeArray() );

	swal({
			title: `Are you sure, you recorded properly the permissions for the position of ${ currentPositionName }?`,
			text : 'If yes, then just click the confirm button.',
			icon : 'warning',
			buttons : true,
			dangerMode: true,
		})
		.then((confirmation) => {
			if(confirmation) {
				axios.patch( $(this).attr('action'),
				{
					'permissions' : $(this).serializeArray(),
				})
				.then((response) => {

					console.log(response.data);

					successAlert(response);

					getPermissionList(currentPositionId);

				})
				.catch((error) => {

					console.log(error.response);

					errorAlert(error);

				});
			}
		});
});