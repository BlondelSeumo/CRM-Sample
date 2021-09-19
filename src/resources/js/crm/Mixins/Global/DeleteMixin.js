export const DeleteMixin = {
    data() {
        return {
            confirmationModalActive: false,
            deleteUrl: '',
        }
    },

    methods: {
        confirmed() {
            this.axiosDelete(this.deleteUrl).then((response) => {
                this.$toastr.s(response.data.message);
                this.$hub.$emit("reload-" + this.tableId);
                this.cancelled();
            }).catch(({error}) => {
            });
        },

        cancelled() {
            this.confirmationModalActive = false;
            this.deleteUrl = "";
        },

    }
}