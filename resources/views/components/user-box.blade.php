<div class="user-box text-center">
    <img src="{{ auth()->user()['profile_img'] }}" alt="user-img" title="Mat Helme" class="rounded-circle img-thumbnail avatar-lg image-fit">
    <div class="dropdown">
        <a href="#" class="text-primary dropdown-toggle h5 mt-2 mb-1 d-block" data-toggle="dropdown">{{ auth()->user()['full_name'] }}</a>
        <div class="dropdown-menu user-pro-dropdown">

            <!-- item-->
            {{-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i class="fe-user mr-1"></i>
                <span>My Account</span>
            </a> --}}

            {{-- <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i class="fe-settings mr-1"></i>
                <span>Settings</span>
            </a>

            <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i class="fe-lock mr-1"></i>
                <span>Lock Screen</span>
            </a> --}}

            <!-- item-->
            <a href="javascript:void(0);" class="dropdown-item notify-item">
                <i class="fe-log-out mr-1"></i>
                <span>Logout</span>
            </a>

        </div>
    </div>
    <div class="my-2">
        {{-- <a href="#">{{ auth()->user()['full_name'] }}</a> --}}
        <p class="text-muted">{{ auth()->user()->getRoleNames()->first() }}</p>   
    </div>

    <ul class="list-inline">
        <li class="list-inline-item">
            <a href="#" class="text-muted">
                <i class="mdi mdi-settings"></i>
            </a>
        </li>

        <li class="list-inline-item">
            <a href="#" class="text-custom">
                <i class="mdi mdi-power"></i>
            </a>
        </li>
    </ul>
</div>