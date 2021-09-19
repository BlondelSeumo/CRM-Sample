<template>
    <div class="content-wrapper">
        <button type="button" class="btn btn-success" @click.prevent="showToast()">
            Show Toast
        </button>
    </div>
</template>

<script>
    export default {
        name: "ToasterExample",
        methods: {
            showToast() {
                this.$toastr.s("SUCCESS MESSAGE", "Success Toast Title");
                this.$toastr.e("ERRROR MESSAGE");
                this.$toastr.w("WARNING MESSAGE");
                this.$toastr.i("INFORMATION MESSAGE");
            }
        }
    }
</script>
