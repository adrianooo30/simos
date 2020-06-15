// SET DATE TODAY
$('.--date-today').val( moment().format('YYYY-MM-DD') );

function set_dateToday(object) {
	$(object).val( moment().format('YYYY-MM-DD') );
}

// ONKEYUP | ALL WITH CLASS .FORM-CONTROL
$('.form-control').on('input', function(){

	$(this).removeClass('is-invalid');
	$(this).closest('.form-group').find('.error-message').remove();

});

$('.form-control').attr('autocomplete', 'off');

// NOT EXCEED IN MAX
$('body').on('input', '.--dont-exceed-max', function(){
	dontExceedMax(this);
});

function dontExceedMax(object)
{
	if(parseInt($(object).attr('max')) < parseInt( $(object).val() ))
		$(object).val( $(object).attr('max')  );

	else if(parseInt( $(object).val() ) < 1)
		$(object).val(0);
}

// SUCCESS MESSAGE IN FORMS
function successAlert(response)
{
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
}

// ERROR MESSAGE IN FORMS
function errorAlert(error)
{
	swal({
		icon : 'error',
		title : error.response.statusText,
		text : error.response.data.message,
		button : {
			text : 'Okay',
		}
	});
}

function refreshInputFields()
{
	$('input:not(.--date-today))').val('');
	$('select:not(.--date-today))').val('');
}

// just in case  - must currency
$(document).on('input', '.--must-currency', function(event){

	console.log( $(this).val().split('').find(function(value){ return value == '.' }) );

	var iKeyCode = (event.which) ? event.which : event.keyCode;
	if(iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
		return false;

	return true;

	console.log('hmmmm');

});

// general functions
function setPesoFormatted(number)
{
	return '&#8369; '+__numberWithComma( parseInt(number).toFixed(2) );
}

function setQuantityFormatted(number)
{
	return __numberWithComma( parseInt(number) )+' pcs.';
}

function __numberWithComma(x)
{
	x = x.toString();
	var pattern = /(-?\d+)(\d{3})/;

	while(pattern.test(x))
		x = x.replace(pattern, "$1,$2");
	return x;
}


// custom serializeObject
$.fn.serializeObject = function(){

	var o = {};
	var a = this.serializeArray();

	$.each(a, function(){
		if(o[this.name]){
			if(!o[this.name].push){
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});

	return o;

}


function generateRandomColor(times = 1)
{
	let rgba = [], rgb = [];

	for(let i = 0; i < times; i++)
	{
		const r = Math.floor(Math.random()*256);          // Random between 0-255
		const g = Math.floor(Math.random()*256);          // Random between 0-255
		const b = Math.floor(Math.random()*256);          // Random between 0-255

		rgba.push(`rgb(${ r }, ${ g }, ${ b }, 0.5)`);
		rgb.push(`rgb(${ r }, ${ g }, ${ b }, 1)`);
	}

	return { 'background' : rgba, 'border' : rgb };
}

// $('.phpdebugbar-minimize-btn').attr('accesskey', '1');
// $('.phpdebugbar-maximize-btn').attr('accesskey', '2');


$('.--logout-btn').on('submit', function(e){

	e.preventDefault();

	swal({
			title: 'Are you sure, you want to logout?',
			text : 'If yes, then click the confirm button to proceed.',
			icon : 'warning',
			buttons : true,
			dangerMode: true,
		})
		.then((confirm) => {
			if(confirm) {
				e.currentTarget.submit();
			}
		});

});

// 
function savingUI()
{
	Swal.fire({
	  title: '<h2>Saving your work...</h2>',
	  text: 'It should take a few seconds.',
	  // imageUrl: '/images/randoms/loading.gif',
	  showSpinner : true,
	  imageWidth: 150,
	  imageHeight: 120,
	  imageAlt: 'Loading image...',
	})
}

// saving data ux
// $(document).on('click', '.--btn-save-effect', function(){

// 	 $(this)

// });

// function getAllUnreadNotifications()
// {
// 	axios.get(`/ajax/notifications`,{
// 		params : {
// 			state : 'all',
// 			state : 'all',
// 		}
// 	})
//     .then((response) => {

//     	if(response.data['all_notif_count'] > 0)
//       		$('.--all-notif-count').show().text(response.data['all_notif_count']);
//       	else
//       		$('.--all-notif-count').hide().empty();

//     })
// }

function getAllNotificationsCount()
{
	axios.get(`/ajax/notifications?count_all_unread`,)
    .then((response) => {

    	if(response.data['all_notif_count'] > 0)
      		$('.--all-notif-count').show().text(response.data['all_notif_count']);
      	else
      		$('.--all-notif-count').hide().empty();

    })
}

getAllNotificationsCount();