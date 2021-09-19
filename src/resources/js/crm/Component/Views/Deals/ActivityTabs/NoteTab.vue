<template>
    <div>
        <form class="mb-0" ref="form" :data-url="route('deal.sync-note', {id: props.id})">
         <app-input type="text-editor" :required="true" v-model="formData.note"/>

            <div class="pt-primary px-primary border-top mx-minus-primary">
                <button type="button" :disabled="props.status ? false : true" class="btn btn-primary" @click.prevent="submitData">
                        <span v-if="loading" class="w-100">
                            <app-submit-button-loader></app-submit-button-loader>
                        </span>
                    <template v-else>{{ $t('save') }}</template>
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
                route,
                loading: false,
                formData:{}
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
                this.$toastr.e(response.data.message);
                this.loading = false;
            },

            afterSuccess(response) {
                this.$toastr.s(response.data.message);
                this.loading = false;
                this.$hub.$emit('activity-list', 'note');
                this.formData.note = ''
            },
            cancel(){
                location.reload();
            }
        }
    }
</script>
