<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                        :page-title="$t('lead_groups')"
                        :directory="[$t('contact'), $t('contact_type')]"
                        :icon="'clipboard'"/>
            </div>
            <div class="col-sm-12 col-md-6" v-if="$can('create_types')">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button
                            type="button"
                            class="btn btn-primary btn-with-shadow"
                            @click.prevent="openModal">
                        {{ $t('add_leads_group') }}
                    </button>
                </div>
            </div>
        </div>

        <app-table :id="'contact-type-table'" :options="options" @action="getAction"/>

        <app-contact-type-modal v-if="isModalActive"
                                :table-id="tableId"
                                :selected-url="selectedUrlContact"
                                @close-modal="closeModal"/>

        <app-confirmation-modal v-if="confirmationModalActive"
                                modal-id="delete-modal"
                                @confirmed="confirmed"
                                @cancelled="cancelled"/>

    </div>
</template>

<script>

    import {FormMixin} from "@core/mixins/form/FormMixin";
    import {DeleteMixin} from "@app/Mixins/Global/DeleteMixin";

    export default {
        name: "ContactTypeList",
        mixins: [FormMixin, DeleteMixin],
        data() {
            return {
                confirmationModalActive: false,
                isModalActive: false,
                tableId: 'contact-type-table',
                selectedUrlContact: '',
                options: {
                    name: this.$t('pipeline_table'),
                    url: route('types.index'),
                    showHeader: true,
                    columns: [
                        {
                            title: this.$t('name'),
                            type: 'text',
                            key: 'name',
                        },
                        {
                            title: this.$t('class'),
                            type: 'custom-html',
                            key: 'class',
                            modifier: (value) => {
                                return `<span class="badge badge-pill badge-${value}">${value}</span>`;

                            }
                        },

                    ],
                    filters: [
                        {
                            "title": this.$t('created_date'),
                            "type": "range-picker",
                            "key": "date",
                            "option": ["today", "thisMonth", "last7Days", "thisYear"]
                        },

                    ],
                    showSearch: true,
                    showFilter: true,
                    paginationType: "pagination",
                    responsive: true,
                    rowLimit: 10,
                    showAction: true,
                    orderBy: 'desc',
                    actionType: "default",
                    enableCookie: false,
                    showCount: true,
                    showClearFilter: true,
                    actions: [
                        {
                            title: this.$t('edit'),
                            icon: 'edit',
                            type: 'modal',
                            component: 'app-contact-type-modal',
                            modalId: 'contact-type-modal',
                            modifier: () => this.$can("update_types"),

                        }, {
                            title: this.$t('delete'),
                            icon: 'trash',
                            type: 'modal',
                            component: 'app-confirmation-modal',
                            modalId: 'delete-modal',
                            url: '',
                            modifier: () => this.$can("delete_types"),
                        },
                    ],
                }
            }
        },
        methods: {
            getAction(row, action, active) {

                if (action.title === this.$t('edit')) {
                    this.selectedUrlContact = route('types.show', {id: row.id});
                    this.isModalActive = true;
                } else if (action.title == this.$t('delete')) {

                    this.deleteUrl = route('types.destroy', {id: row.id});
                    this.confirmationModalActive = true;
                }
            },
            openModal() {
                this.isModalActive = true;
                $('#contact-type-modal').modal('show');
            },
            closeModal() {
                this.isModalActive = false;
                this.selectedUrlContact = '';
                $('#contact-type-modal').modal('hide');
            }
        },
        created(){
            if (this.$can("update_types") || this.$can("delete_types")){
                this.options.columns = [...this.options.columns, {
                    title: 'Action',
                    type: 'action',
                    key: 'invoice',
                    isVisible: true
                },]
            }

        }
    }
</script>
<style>
    .badge-link {
        color: #0f37da;
    }
</style>
