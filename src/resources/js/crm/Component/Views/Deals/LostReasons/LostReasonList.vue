<template>
    <div class="content-wrapper calendar-position-modified">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :directory="[$t('deals'), $t('lost_reason')]"
                    :icon="'clipboard'"
                    :page-title="$t('lost_reasons')"/>
            </div>
            <div v-if="$can('create_lost_reasons')" class="col-sm-12 col-md-6">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button
                        class="btn btn-primary btn-with-shadow"
                        type="button"
                        @click.prevent="openModal">
                        {{ $t('add_lost_reason') }}
                    </button>
                </div>
            </div>
        </div>

        <app-table :id="'lost-reason-table'" :options="options" @action="getAction"/>

        <app-lost-reason-modal v-if="isModalActive"
                               :selected-url="selectedUrlReason"
                               :table-id="tableId"
                               @close-modal="closeModal"/>

        <app-confirmation-modal v-if="confirmationModalActive"
                                modal-id="lost-reason-delete-modal"
                                @cancelled="cancelled"
                                @confirmed="confirmed"/>

    </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin";
import {owner} from "@app/Mixins/Global/FilterMixins";
import {DeleteMixin} from "../../../../Mixins/Global/DeleteMixin";

export default {
    name: "LostReasonList",
    mixins: [FormMixin, owner, DeleteMixin],
    data() {
        return {
            isModalActive: false,
            tableId: 'lost-reason-table',
            selectedUrlReason: '',
            options: {
                name: this.$t('pipeline_table'),
                url: route('lost-reasons.index'),
                showHeader: true,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'lost_reason',
                    },
                    {
                        title: this.$t("created_by"),
                        type: "custom-html",
                        key: "created_by",
                        modifier: (value, row) => {
                            return value ? value.full_name
                                : `<p class="m-0 font-size-90 text-secondary">` +
                                this.$t("user_deleted") +
                                `</p>`;
                        },
                    },
                ],
                filters: [
                    {
                        "title": this.$t('created_by'),
                        "type": "checkbox",
                        "key": "owner_is",
                        "option": [],
                    },
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
                        component: 'app-lost-reason-modal',
                        modalId: 'lost-reason-modal',
                        modifier: () => this.$can('update_lost_reasons')

                    }, {
                        title: this.$t('delete'),
                        icon: 'trash',
                        type: 'modal',
                        component: 'app-confirmation-modal',
                        modalId: 'lost-reason-delete-modal',
                        url: '',
                        modifier: () => this.$can('delete_lost_reasons')
                    },
                ],
            }
        }
    },
    methods: {
        getAction(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrlReason = route('lost-reasons.show', {id: row.id});
                this.isModalActive = true;
            } else if (action.title == this.$t('delete')) {
                this.deleteUrl = route('lost-reasons.destroy', {id: row.id});
                this.confirmationModalActive = true;
            }
        },
        openModal() {
            this.isModalActive = true;
            $('#lost-reason-modal').modal('show');
        },
        closeModal() {
            this.isModalActive = false;
            this.selectedUrlReason = '';
            $('#lost-reason-modal').modal('hide');
        }
    },
    created() {
        if (this.$can('update_lost_reasons') || this.$can('delete_lost_reasons')) {
            this.options.columns = [...this.options.columns, {
                title: 'Action',
                type: 'action',
                key: 'invoice',
                isVisible: true
            }];
        }

    }
}
</script>
