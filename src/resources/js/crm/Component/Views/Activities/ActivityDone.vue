<template>
    <div class="pt-1">
        <app-input
            type="single-checkbox"
            @input="setActivityStatusDone"
            :id="'checkbox-value-'+rowData.id"
            :disabled="checkboxValue"
            v-model="checkboxValue"
            :list-value-field="$t(' ')"
        />
    </div>
</template>

<script>
    import { FormMixin } from "@core/mixins/form/FormMixin";

    export default {
        name: "ActivityDone",
        mixins: [FormMixin],
        props: {
            tableId: String,
            value: {},
            rowData: {}
        },
        data() {
            return {
                checkboxValue: false,
                formData: {}
            };
        },
        computed: {
            doneStatusId() {
                return this.$store.getters.getActivityStatus.find(status => status.name === 'status_done').id
            }
        },
        watch: {
            value: {
                handler: function () {
                    this.checkboxValue = Number(this.value) === Number(this.doneStatusId)
                },
                immediate: true
            }
        },
        methods: {
            setActivityStatusDone(value){
                this.formData.title = this.rowData.title;
                this.formData.status_id = this.doneStatusId;
                this.formData.contextable_type = this.rowData.contextable_type;
                this.formData.contextable_id = this.rowData.contextable_id;

                this.axiosPut({
                    url: route('activities.update', {'id': this.rowData.id}),
                    data: this.formData,
                }).then((response) => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit("reload-" + "activity-modal");
                });
            }
        },
    };
</script>
