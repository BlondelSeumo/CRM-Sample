<template>
    <div>
        <form class="mb-0" ref="form" :data-url="this.props.noteSyncUrl">
         <app-input type="text-editor" :required="true" v-model="formData.note"/>

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
        name: "NoteTab",
        mixins: [FormMixin],
        props:['props'],
        data() {
            return {
                formData:{},
                loading: false,
            }
        },
        methods:{
            beforeSubmit() {
                this.loading = true;
            },
            submitData(){
                this.save(this.formData);
            },
            afterError(response) {
                this.loading = false;
                this.$toastr.e(response.data.message);
            },

            afterSuccess(response) {
                this.$toastr.s(response.data.message);
                this.$hub.$emit('activity-list', 'note');
                this.formData.note = ''
            },
            afterFinalResponse() {
                this.loading = false;
            },
            cancel(){
                location.reload();
            }
        }
    }
</script>
