<div class="card-box widget-user border-top border-bottom  {{ is_null($notification['read_at'])? 'bg-light-blue' : '' }} --notif-card" data-notification-id="{{ $notification['id'] }}">
    <div class="row">
        <div class="col-xl-2">
            <div class="avatar-lg">
                <img src="{{ asset( $data['account']['profile_img'] ) }}" class="img-fluid rounded-circle" alt="user" width="50">
            </div>
        </div>
        <div class="col-xl-7">
            <div class="wid-u-info">
                <h5 class="mt-0 text-primary font-16">{{ $data['account']['account_name'] }}</h5>
                <h6 class="text-muted mb-1">{{ $data['account']['type'] }}</h6>
                <p class="text-muted font-16">
                    This account ordered product(s) with a total cost of <strong>&#8369;10,230.00</strong>
                </p>

                {{-- <btn-notifications-actions></btn-notifications-actions> --}}

                @btnNotificationAction
            </div>
        </div>
        <div class="col-xl-3">
            <p class="text-muted font-14 font-weight-lighter">
                <i class="ti-time"></i> {{ $notification['created_at']->diffForHumans() }}
            </p>
        </div>
    </div>
</div>