<template>
    <div>
        <form class="mb-0" ref="form" :data-url="props.fileSyncUrl" enctype="multipart/form-data">
            <app-input type="dropzone" :required="true" v-model="files"/>

          <small v-if="Object.values(errors).length > 0" class="text-danger">
            {{ $t('you_can_not_upload_a_file_larger_than_5_MB') }}
          </small>

            <div class="pt-primary px-primary border-top mx-minus-primary">
                <button type="button" class="btn btn-primary" @click.prevent="submitData">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                    <template v-if="!loading">{{ $t('save') }}</template>
                </button>
                <button type="button" class="btn btn-secondary" @click="cancel">{{ $t('cancel') }}</button>
            </div>
        </form>
    </div>
</template>

<script>

    import {FormMixin} from "@core/mixins/form/FormMixin";

    export default {
        name: "FileTab",
        mixins: [FormMixin],
        props:['props'],
        data() {
            return {
                loading: false,
                files: [],
                errors: {},
            }
        },
        methods: {
            beforeSubmit() {
                this.loading = true;
            },
            submitData() {
                let formData = new FormData();
                if (this.files.length)
                    this.files.forEach(el => {
                        formData.append('path[]', el);
                    })
                this.save(formData)
            },
            afterError(response) {
                this.errors = response.data.errors;
                this.loading = false;
                this.$toastr.e(this.$t('you_can_not_upload_a_file_larger_than_5_MB'));
            },

            afterSuccess(response) {
                this.$toastr.s(response.data.message);
                this.$hub.$emit('activity-list', 'file');
                this.files = [];
                this.errors = [];
            },
            afterFinalResponse() {
                this.loading = false;
            },
            cancel() {
                location.reload();
            }
        }
    }
</script>
