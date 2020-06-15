<template>
    <div class="card-box widget-user border-top border-bottom">
        <div class="d-flex">
            <div class="avatar-lg float-left mr-3">
                <img src="/images/users/user.png" class="img-fluid rounded-circle" alt="user" width="50">
            </div>
            <div class="wid-u-info flex-grow-1">
                <h5 class="mt-0 text-primary font-16">Generic Name 10ml</h5>
                <h6 class="text-muted mb-1">Brand Name</h6>
                <p class="text-muted font-16 font-weight-lighter">
                    <b>This product is now
                        <span class="text-danger font-16">out of stock.</span>
                    </b>
                </p>

                <btn-notifications-actions></btn-notifications-actions>
            </div>
            <p class="text-muted font-14 font-weight-lighter">
                <i class="ti-time"></i> 10 mins ago
            </p>
        </div>
    </div>
</template>

<script>

    import BtnNotificationActions from './BtnNotificationActions.vue';
    
    export default {
        mounted() {
            console.log('Component mounted.')
        },

        components : {
            'btn-notifications-actions' : BtnNotificationActions,
        }
    }
</script>