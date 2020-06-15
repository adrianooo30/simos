// **********************************
// 			FILTERING DATES
// **********************************
var start = moment().startOf('month');
var end = moment().endOf('month');

function cb(start, end) {

    // setting the value
    $('[name="from_date"].for-filtering-dates').val( start.format('YYYY-MM-DD') );
    $('[name="to_date"].for-filtering-dates').val( end.format('YYYY-MM-DD') );

    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    // reload filter tables
    expiredTable.ajax.data;
    // reload datatables
    expiredTable.ajax.reload();

    removeThis();
}

// set date range picker
$(document).ready(function(){

	$('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        separator : ' to ',
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    
});

cb(start, end);

function removeThis()
{
  // remove custom range
  $('li[data-range-key="Custom Range"]').remove();
}