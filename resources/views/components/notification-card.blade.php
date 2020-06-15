<li class="dropdown notification-list">
    <a class="nav-link dropdown-toggle  waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
        <i class="fe-bell noti-icon"></i>
        <span class="badge badge-primary --all-notif-count"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

        <!-- item-->
        <div class="dropdown-item noti-title">
            <h5 class="m-0">
                <span class="float-right">
                    <a href="#" class="text-dark">
                        {{-- <small>Clear All</small> --}}
                    </a>
                </span>Notification
            </h5>
        </div>

        <div class="slimscroll noti-scroll">

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item notify-item active">
                <div class="notify-icon">
                    <img src="{{ asset('/assets/images/users/user-1.jpg') }}" class="img-fluid rounded-circle" alt="" />
                </div>
                <p class="notify-details">Cristina Pride
                    <span class="float-right text-muted font-10">
                        <i class="ti-time"></i> 10 mins ago
                    </span>
                </p>
                <p class="text-muted mb-0 user-msg">
                    <small>Hi, How are you? What about our next meeting</small>
                </p>
                <div class="float-right">
                    <div class="btn-group font-12">
                        <button class="btn btn-sm btn-primary waves-effect text-hover-dark wave-light font-weight-bold">
                            <i class="mdi mdi-information-outline "></i>
                        </button>
                        <button class="btn btn-sm btn-success waves-effect wave-light font-weight-bold">
                            <i class="mdi mdi-email-mark-as-unread"></i>
                        </button>
                    </div>
                    <div class="mx-1 my-1 d-inline">
                        <button class="btn btn-sm btn-danger waves-effect wave-light font-weight-bold">
                            <i class="ti-trash"></i>
                        </button>
                    </div>
                </div>
            </a>

            <a href="javascript:void(0);" class="dropdown-item notify-item active">
                <div class="notify-icon">
                    <img src="{{ asset('/assets/images/users/user-1.jpg') }}" class="img-fluid rounded-circle" alt="" /> </div>
                <p class="notify-details">Cristina Pride</p>
                <p class="text-muted mb-0 user-msg">
                    <small>Hi, How are you? What about our next meeting</small>
                </p>
            </a>

            <a href="javascript:void(0);" class="dropdown-item notify-item active">
                <div class="notify-icon">
                    <img src="{{ asset('/assets/images/users/user-1.jpg') }}" class="img-fluid rounded-circle" alt="" /> </div>
                <p class="notify-details">Cristina Pride</p>
                <p class="text-muted mb-0 user-msg">
                    <small>Hi, How are you? What about our next meeting</small>
                </p>
            </a>

            <a href="javascript:void(0);" class="dropdown-item notify-item active">
                <div class="notify-icon">
                    <img src="{{ asset('/assets/images/users/user-1.jpg') }}" class="img-fluid rounded-circle" alt="" /> </div>
                <p class="notify-details">Cristina Pride</p>
                <p class="text-muted mb-0 user-msg">
                    <small>Hi, How are you? What about our next meeting</small>
                </p>
            </a>

        </div>

        <!-- All-->
        <a href="{{ route('notifications') }}" class="dropdown-item text-center text-primary notify-item notify-all">
            View all
            <i class="fi-arrow-right"></i>
        </a>

    </div>
</li>