
// for all of the tabs their
var tabs = ['details', 'orders'];
// end of for all of the tabs their

function orderDetails(id)
{
	let orderDetailsDisplay = document.querySelector('#--order-details');
	let orderLoading = document.querySelector('#--order-loading');

	orderDetailsDisplay.style.display = "none";
	orderLoading.style.display = "block";

	axios.get(`/ajax/order/view/${ id }`)
		.then((response) => {

			orderLoading.style.display = "none";
			orderDetailsDisplay.style.display = "block";

			let orderDetails = response.data;

			let profileImg = document.getElementById('--profile-img'),
				accountName = document.getElementById('--account-name'),
				type = document.getElementById('--type'),
				total = document.getElementById('--total'),
				orderDate = document.getElementById('--order-date'),
				psr = document.getElementById('--psr');

				profileImg.setAttribute('src', orderDetails['account']['profile_img']);
				accountName.innerHTML = orderDetails['account']['account_name'];
				type.innerHTML = '<i class="ti-home"></i> '+orderDetails['account']['type'];
				total.innerHTML = orderDetails['total_cost_format'];
				orderDate.innerHTML = orderDetails['order_date'];
				psr.innerHTML = orderDetails['employee']['full_name'];

			// load ordered medicine in datatables
			load_orderMedicineDatatables(id);

		});

}