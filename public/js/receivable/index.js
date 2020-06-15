function getReceivablesList(page = 1)
{
	let content = $('#receivables-content'),
		loading = $('#receivables-loading');

	content.hide();
	loading.show();

	axios.get(`/ajax/transactions/receivables?page=${ page }`,{
		params : {
			'search' : $('[name="search"]').val(),
		}
	})
	.then((response) => {

		content.show();
		loading.hide();

		console.log( response.data );

		$('#receivables-list').html( response.data['receivables_html'] );
	})
	.catch((error) => {
		console.log(error.data);
	});
}

getReceivablesList();

// search account
$('[name="search"]').on('input', function(){
	if( $(this).length > 0 )
		getReceivablesList();
});

// pagination links for accounts
$(document).on('click', '#receivables-list-pagination .page-item .page-link', function(e){
	e.preventDefault();
	// get account list with the pagition
	getReceivablesList( $(this).attr('href').split('page=')[1] );
});