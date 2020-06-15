<template>
    <div class="">
        <div class="btn-group">
            <button class="btn btn-sm font-12 btn-primary waves-effect text-hover-dark wave-light font-weight-bold">
                <i class="dripicons-information"></i> Details
            </button>
            <!-- <button class="btn btn-sm font-12 btn-success waves-effect wave-light font-weight-bold">
                <i class="ti-email"></i> Mark as Read
            </button> -->
        </div>
        <div class="mx-1 d-inline">
            <button class="btn btn-sm font-12 btn-danger waves-effect wave-light font-weight-bold">
                <i class="ti-trash"></i> Delete
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
