<div class="card-box widget-user border-top border-bottom  {{ is_null($notification['read_at'])? 'bg-light-blue' : '' }} --notif-card" data-notification-id="{{ $notification['id'] }}">
    <div class="row">
        <div class="col-xl-2">
            <div class="avatar-lg float-left">
                <img src="{{ $data['account']['profile_img'] }}" class="img-fluid rounded-circle" width="50">
            </div>
        </div>
        <div class="col-xl-7">
            <div class="wid-u-info flex-grow-1">
                <h5 class="mt-0 text-primary font-16">{{ $data['account']['account_name'] }}</h5>
                <h6 class="text-muted mb-1">{{ $data['account']['type'] }}</h6>
                <p class="text-muted font-16">
                    {{ $data['deliver_transaction']['receipt_no'] }} has been
                        {{-- <strong>Canceled</strong> --}}
                        <strong>{{ $data['status'] }}</strong>
                        {{-- <strong>Delivered</strong> --}}
                     by Admin - Mr. Fname Lname
                </p>

                @btnNotificationAction
            </div>
        </div>
        <div class="col-xl-3">
            <p class="text-muted font-14 font-weight-lighter d-block">
                <i class="ti-time"></i> {{ $notification['created_at']->diffForHumans() }}
            </p>
        </div>
    </div>
</div>