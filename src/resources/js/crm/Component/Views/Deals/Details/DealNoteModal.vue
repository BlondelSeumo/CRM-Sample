<template>
    <app-modal modal-id="note-modal" modal-size="large" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{this.note.id ? $t("edit") : $t("add")}} </h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <form class="mb-0" ref="form" :data-url="route(`activities.update-note`, {id: note.note.id})">
            <div class="col-12">
                <app-input v-if="textEditorReboot" type="text-editor" :required="true" v-model="formData.note"/>
            </div>
            </form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="submitData">
                {{ $t('save') }}
            </button>
        </template>
    </app-modal>
</template>

<script>
    import {FormMixin} from "@core/mixins/form/FormMixin";
    export default {
        name: "DealNoteModal",
        props: ['note'],
        mixins: [FormMixin],
        data:()=>({
           formData:{note:''},
            textEditorReboot: false,
        }),

        methods: {
            submitData() {
                this.save(this.formData)
            },
            afterError(response) {
                this.$toastr.e(response.data.message);
            },

            afterSuccess(response) {
                this.$toastr.s(response.data.message);
                this.$hub.$emit('activity-list');
            },
            afterFinalResponse(){
                this.closeModal();
            },
            closeModal(value) {
                this.$emit("close-modal", value);
            }
        },
        mounted(){
            this.formData.note = this.note.note;
            if (this.note){
                this.textEditorReboot = false;
                setTimeout(() => this.textEditorReboot = true)
            }
        }
    }
</script>

<style scoped>

</style>
