<div class="card-box widget-user border-top border-bottom  {{ is_null($notification['read_at'])? 'bg-light-blue' : '' }} --notif-card" data-notification-id="{{ $notification['id'] }}">
    <div class="row">
        <div class="col-xl-2">
            <div class="avatar-lg float-left">
                <img src="{{ asset( $data['profile_img'] ) }}" class="img-fluid rounded-circle" alt="user" width="50">
            </div>
        </div>
        <div class="col-xl-7">
            <div class="wid-u-info flex-grow-1">
                <h5 class="mt-0 text-primary font-16">{{ $data['account']['account_name'] }}</h5>
                <h6 class="text-muted mb-1">{{ $data['account']['type'] }}</h6>
                <p class="text-muted font-16">
                    Has paid the following bills;
                        <strong>DR1023</strong>,
                        <strong>DR1024</strong>,
                        <strong>DR1025</strong>
                </p>
                
                @btnNotificationAction
            </div>
        </div>
        {{-- time ago --}}
        <div class="col-xl-3">
            <p class="text-muted font-14 font-weight-lighter d-block">
                <i class="ti-time"></i> {{ $notification['created_at']->diffForHumans() }}
            </p>
        </div>
    </div>
</div>