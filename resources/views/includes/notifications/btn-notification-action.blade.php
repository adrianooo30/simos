<div class="--btn-notification-parent" data-read="{{ !is_null($notification['read_at']) ? 'true' : 'false' }}">
    <div class="btn-group font-12">
        <a href="#" 
            class="btn btn-sm btn-primary waves-effect text-hover-dark wave-light font-weight-bold text-white"
            title="See more details" 
        >
            <i class="mdi mdi-information-outline "></i>
        </a>
        @if( is_null($notification['read_at']) )

            <button class="btn btn-sm btn-success waves-effect wave-light font-weight-bold --mark-as-read-btn"
                title="Mark as read"
            >
                <i class="mdi mdi-email-mark-as-unread"></i>
            </button>
        @endif
    </div>
    <div class="mx-1 my-1 d-inline">
        <button class="btn btn-sm btn-danger waves-effect wave-light font-weight-bold --delete-notif-btn"
            title="Delete this notification" 
        >
            <i class="ti-trash"></i>
        </button>
    </div>
</div>

{{-- <div class="">
    <div class="btn-group font-10">
        <button class="btn btn-sm btn-primary waves-effect text-hover-dark wave-light font-weight-bold">
            <i class="dripicons-information"></i> Details
        </button>
        if not null
        @if( is_null($notification['read_at']) )
            <button class="btn btn-sm btn-success waves-effect wave-light font-weight-bold" disabled>
                <i class="ti-email"></i> Mark as Read
                <div class="spinner-grow text-custom font-10" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        @endif
    </div>
    <div class="mx-1 my-1 d-inline">
        <button class="btn btn-sm btn-danger waves-effect wave-light font-weight-bold">
            <i class="ti-trash"></i> Delete
        </button>
    </div>
</div> --}}