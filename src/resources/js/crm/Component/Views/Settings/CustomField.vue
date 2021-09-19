<template>
    <div>
        <app-table :id="customFieldTableId" :options="customField" @action="getAction"/>

        <app-custom-field-modal v-if="isModalActive"
                                :tableId="customFieldTableId"
                                :selected-url="customFiledSelectedUrl"
                                @close-modal="closeModal"/>

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="custom-field-delete-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"/>

    </div>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin.js";
import {urlGenerator} from "@app/Helpers/helpers";

export default {
    name: "CustomField",
    mixins: [FormMixin],
    props: ['id'],
    data() {
        return {
            urlGenerator,
            isModalActive: false,
            confirmationModalActive: false,
            customFieldTableId: 'custom-filed-table',
            customFiledSelectedUrl: '',
                customField: {
                    tableShadow: false,
                    tablePaddingClass: 'px-0 py-primary',
                    name: 'CustomFiledTable',
                    url: route('core.custom-fields.index'),
                    showHeader: true,
                    columns: [
                        {
                            title: this.$t('name'),
                            type: 'text',
                            key: 'name'
                        },
                        {
                            title: this.$t('context'),
                            type: 'text',
                            key: 'context',

                        },

                        {
                            title: 'Action',
                            type: 'action',
                            key: 'invoice',
                        },
                    ],
                    filters: [],
                    showSearch: true,
                    showFilter: true,
                    showManageColumn: false,
                    paginationType: "pagination",
                    responsive: true,
                    rowLimit: 10,
                    showAction: true,
                    orderBy: 'desc',
                    actionType: "default",
                    actions: [
                        {
                            title: 'Edit',
                            icon: 'edit',
                            type: 'modal',
                            component: 'app-custom-field-modal',
                            modalId: 'custom-field-modal',
                            url: '',
                        }, {
                            title: this.$t('delete'),
                            icon: 'trash',
                            type: 'modal',
                            component: 'app-confirmation-modal',
                            modalId: 'custom-field-delete-modal',
                            url: '',
                        },
                    ],
                }
            }
        },

        methods: {
            getAction(row, action, active) {
                this.customFiledSelectedUrl = route('core.custom-fields.show', {'id': row.id})
                if (action.title === this.$t('edit')) {
                    this.isModalActive = true;
                }else if (action.title === this.$t('delete')) {
                    this.confirmationModalActive = true;
                }
            },
            confirmed() {
                this.axiosDelete(this.customFiledSelectedUrl).then(response => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit('reload-' + this.customFieldTableId);
                }).catch(({error}) => {
                    this.$toastr.e(response.data.message);
                });
            },
            cancelled() {
                this.confirmationModalActive = false;
            },
            openModal() {
                this.isModalActive = true;
                $('#custom-field-modal').modal('show');
            },
            closeModal() {
                this.isModalActive = false;
                this.customFiledSelectedUrl = '';
                $("#custom-field-modal").modal('hide');
            },
        },

        mounted(){
            this.$hub.$on('headerButtonClicked-'+this.id, () => {
                this.isModalActive = true
                this.customFiledSelectedUrl = '';
            })
        }
    }
</script>

