function getCurrentPositionAndItsRoles()
{
	axios.get('/ajax/privilege')
		.then((response) => {

			console.log(response.data);

			response.data.modules.forEach(function(module){
				if( module.methods[0].allowed )
					$('#--'+module['access_name']).show();
				else
					$('#--'+module['access_name']).remove();
			});

			// logic for parent navs
			let parentNavs = $('.--parent-nav');
			$.each(parentNavs, (index, parentNav) => {

				let subNavs = $('#'+parentNav.id+' ~ .--sub-nav li');
				if( subNavs.length === 0)
					$('#'+parentNav.id).remove();

			});

			// show now the main-nav. this is the parent of the sidebar
			$('#main-nav').show();
		})
		.catch((error) => {

			console.log(error);

		});

}

( function(){ getCurrentPositionAndItsRoles() } )();