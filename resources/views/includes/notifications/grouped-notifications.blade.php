@forelse($notifications as $notification)

	{!! $notification['notification_html'] !!}

	@empty
	<div class="card-box widget-user border-top border-bottom">
	    <h4 class="text-center text-muted">
	    	There are no notifications for this type of notification.
	    </h4>
	</div>
@endforelse