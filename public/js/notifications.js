// TYPEOF NOTIFICATION
$('.--notif-type-btn').on('click', function(){
  // check if the current character
  if(currentNotifType == $(this).data('notif-type'))
    return;

  $.each( $('.--notif-link strong'), (key, object) => $(object).removeClass('text-primary') )
  $(this).find('strong').addClass('text-primary');

	getNotifications( $(this).data('notif-type') );

});

var currentNotifType = '';

var naku;
function getNotifications(notifType = 'out-of-stock')
{
  // set the current notif type
  currentNotifType = notifType;

  let loading = $('.--notif-loading');
  let content = $('.--notif-content');

  loading.show();
  content.empty();

  axios.get(`/ajax/notifications`,{
    params : {
      'notification_type' : notifType,
      'from_date' : $('[name="from_date"].for-filtering-dates').val(),
      'to_date' : $('[name="to_date"].for-filtering-dates').val(),
      'state' : $('[name="filter_state"]').val(),
    }
  })
    .then((response) => {

      loading.hide();
      content.html( response.data['notifications_html'] );

      setNotificationsCount( response.data['notifications_count'] );

      getAllNotificationsCount();

    })
    .catch((error) => {
      console.log( error );

      errorAlert(error);

      loading.hide();

      content.html(`
          <h4 class="text-center text-muted font-weight-lighter">
            Error. Something went wrong.
          </h4>
        `);
    });
}

// auto call this
getNotifications();
  
// FILTER STATE
$('[name="filter_state"]').on('change', function(){
  getNotifications(currentNotifType);
});

// MARK AS READ
$('.--notifications-parent').on('click', '.--mark-as-read-btn', function(){

  setLoadingUI( $(this) );

  let notifID = $(this).closest('.--notif-card').data('notification-id');

  axios.patch(`/ajax/notifications/${ notifID }`,{ })
    .then((response) => {

      let notifCard = $(this).closest(`[data-notification-id="${ notifID }"]`);
      // insert updated notif card
      notifCard.after(response.data['notifications_html']);
      notifCard.hide();
      // then, remove
      setTimeout(function(){
        notifCard.remove();
      }, 1000);

      setNotificationsCount( response.data['notifications_count'] );

      getAllNotificationsCount();

    })
    .catch((error) => {

       errorAlert(error);

       console.log(error.response);

    });
});

// DELETE NOTIFICATION
$('.--notifications-parent').on('click', '.--delete-notif-btn', function(){

  setLoadingUI( $(this) );

  let notifID = $(this).closest('.--notif-card').data('notification-id');

  // deleting notification
  axios.delete(`/ajax/notifications/${ notifID }`,{ })
    .then((response) => {

      console.log(response.data);

      let notifCard = $(this).closest(`[data-notification-id="${ notifID }"]`);
      notifCard.remove();

      setNotificationsCount( response.data['notifications_count'] );

      getAllNotificationsCount();

      // track if no notification
      trackIfNoNotification();

    })
    .catch((error) => {

       errorAlert(error);

       console.log(error.response);

    });
});

// set loading style
function setLoadingUI(object)
{
  $(object).find('i')
      .removeAttr('class')
      .addClass('fas fa-spinner fa-spin');
}

function trackIfNoNotification()
{
  if( $('.--notif-card').length == 0 )
    $('.--notif-content').html(`
        <div class="card-box widget-user border-top border-bottom">
          <h4 class="text-center text-muted">
            There are no notifications for this type of notification.
          </h4>
      </div>
    `);
}

function setNotificationsCount(notifications_count)
{
  $.each(notifications_count, (key, value) => {
    if(value['count'] > 0)
      $(value['selector']).text( value['count'] );
    else
      $(value['selector']).empty();
  });
}

// date filter
// **********************************
//       FILTERING DATES
// **********************************
var start = moment().startOf('month');
var end = moment().endOf('month');

function cb(start, end) {

    // setting the value
    $('[name="from_date"].for-filtering-dates').val( start.format('YYYY-MM-DD') );
    $('[name="to_date"].for-filtering-dates').val( end.format('YYYY-MM-DD') );

    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    // hmffftt
    getNotifications(currentNotifType);
}
// set date range picker
$('#reportrange').daterangepicker({
    startDate: start,
    endDate: end,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
}, cb);

cb(start, end);