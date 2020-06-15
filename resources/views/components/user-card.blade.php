<li class="dropdown notification-list">
    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
        <img src="{{ auth()->user()['profile_img'] }}" alt="user-image" class="rounded-circle image-fit">
        <span class="pro-user-name ml-1">
            {{ auth()->user()['full_name'] }} <i class="mdi mdi-chevron-down"></i> 
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
        <!-- item-->
        <div class="dropdown-header noti-title">
            <h6 class="text-overflow m-0">Welcome !</h6>
        </div>

        <!-- item-->
{{--         <a href="javascript:void(0);" class="dropdown-item notify-item">
            <i class="fe-user"></i>
            <span>My Account</span>
        </a> --}}

        {{-- <!-- item-->
        <a href="javascript:void(0);" class="dropdown-item notify-item">
            <i class="fe-settings"></i>
            <span>Settings</span>
        </a>

        <!-- item-->
        <a href="javascript:void(0);" class="dropdown-item notify-item">
            <i class="fe-lock"></i>
            <span>Lock Screen</span>
        </a> --}}

        <div class="dropdown-divider"></div>

        <form action="{{ route('logout') }}" method="POST"  class="--logout-btn">
            @csrf
            <button type="submit" class="dropdown-item notify-item">
                <i class="fe-log-out"></i> {{ __('Logout') }}
            </button>
        </form>

    </div>
</li>