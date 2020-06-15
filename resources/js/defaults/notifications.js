function getNotifications()
{
	axios.get('/ajax/notifications')
		.then((response) => {

			console.log(response.data);
			displayNotifications(response.data);

		})
		.catch((error) => {

			console.log(error);

		});
}

(function(){ getNotifications() })();

var Notifications;

// display notifications via alert
function displayNotifications(notifications)
{
	// Notification global variable
	Notifications = notifications;

	$('#--notifications').empty();
	Notifications.forEach(function(notification, index){

		let notificationDatas = notification.data[0];

		let titleMessage = '', bodyMessage = '';
		switch(notification.type)
		{
			case 'App\\Notifications\\Product\\OutOfStock':
				titleMessage = 'Out of Stock';
				bodyMessage = `<sub class="text-muted"><strong>${ notificationDatas['generic_name'] } ${ notificationDatas['strength'] }</strong> with the brand name of <strong>${ notificationDatas['brand_name'] }</strong> is now out of stock.</sub><br>`;
			break;

			case 'App\\Notifications\\Product\\Critical':
				titleMessage = 'Critical Level';
				bodyMessage = `<sub class="text-muted"><strong>${ notificationDatas['generic_name'] } ${ notificationDatas['strength'] }</strong> with the brand name of <strong>${ notificationDatas['brand_name'] }</strong> is now in its critical level.</sub><br>`;
			break;
		}

		let markAsType, readType, read;
		// for alert class design
		if(notification['read_at'] !== null){
			readType = 'read';
			read = false;
			markAsType = 'Mark as Unread';
		}
		else{
			readType = 'unread';
			read = true;
			markAsType = 'Mark as Read';
		}

		$('#--notifications').append(`
					<div class="alert alert-danger d-flex ${ readType } border-3">
						<img src="${ notificationDatas['product_img'] }" alt="" class="image-50 b-r-half px-2">
						<div class="flex-grow-1 px-2">
							<div class="d-flex justify-content-between">
								<strong>${ titleMessage }</strong>
								<sub>${ notification['time_ago'] }</sub>
							</div>
							${ bodyMessage }
							<div class="mt-3">
								<button type="submit" class="btn btn-primary font-10 mx-1" onclick="updateReadAt('${ notification['id'] }', ${ read })">
									<i class="fas fa-envelope-open-text"></i>
									${ markAsType }
								</button>
								<a href="/redirect/inventory/product?productId=${ notificationDatas['id'] }" class="btn btn-primary font-10 mx-1">
									<i class="fas fa-syringe"></i>
									Go to Product
								</a>
							</div>
						</div>
					</div>
				`);

	});
}


function updateReadAt(notificationId, read)
{
	axios.patch(`/ajax/notifications/updateReadAt`,{
		'notificationId' : notificationId,
		'read': read,
	})
		.then((response) => {

			// console.log(response.data);

			// get all notifications, because its on the notification module
			getNotifications();

			// notifications in bell icon
			get_unreadNotifications();

		})
		.catch((error) => {

			console.log(error.response);

		});
}