import {FormMixin} from "@core/mixins/form/FormMixin";

export const FormSubmitMixin = {
    props: {
        tableId: String
    },
    mixins: [FormMixin],
    data() {
        return {
            loading: false,
            errors: {},
        }
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.closeModal();
        },
        afterFinalResponse() {
            this.loading = false;
        },
        afterError(response) {
            this.loading = false;
            this.errors = response.data.errors
        },
        closeModal(value) {
            this.$emit('close-modal', value);
        },
    }
}