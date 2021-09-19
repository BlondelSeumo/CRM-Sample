<template>
    <div>
        <app-table :id="tableId" :options="options" @action="getAction"/>

        <app-check-email-deliver
            v-if="isCheckMailModalActive"
            :header-title="$t('send_proposal')"
            @close-modal="closeModalCheckMail"
        />

        <app-send-proposal-modal
            v-if="isSendProposalModalActive"
            :table-id="tableId"
            :selected-url="selectUrl"
            :selected-deal="proposalDeal"
            @close-modal="closeModal"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="proposal-delete-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />

        <app-proposal-preview-modal
            :content="selectedProposalContent"
            :title="selectProposalTitle"
            modalId="proposal-preview"
            @close="isPreviewModalActive = false"
            v-if="isPreviewModalActive"
        />
    </div>
</template>

<script>
import CoreLibrary from "@core/helpers/CoreLibrary.js";
import {formatDateToLocal, urlGenerator} from "@app/Helpers/helpers";
import {owner, proposalStatus, tag} from "@app/Mixins/Global/FilterMixins";
import {collect} from "@app/Helpers/Collection";
import {mapGetters} from "vuex";

export default {
    name: "ListView",
    extends: CoreLibrary,
    mixins: [proposalStatus, owner, tag],
    data() {
        return {
            urlGenerator,
            tableId: "send-proposal-modal",
            confirmationModalActive: false,
            isSendProposalModalActive: false,
            isPreviewModalActive: false,
            isCheckMailModalActive: false,
            selectedProposalContent: "",
            selectProposalTitle: "",
            proposalData: {},
            proposalDeal: {},
            proposal: "",
            selectedUrl: "",
            selectUrl: "",
            copyUrl: "",
            sentStatusId: null,
            options: {
                name: this.$t("proposal_table"),
                url: route('proposals.index'),
                showHeader: true,
                columns: [
                    {
                        title: this.$t("subject"),
                        type: "button",
                        key: "subject",
                        className: "btn text-left text-primary pl-0",
                        // icon: 'check',
                        label: "",
                        modifier: (value, row) => {
                            return row.subject;
                        },
                    },
                    {
                        title: this.$t("status"),
                        type: "component",
                        componentName: "app-proposal-status",
                    },
                    {
                        title: this.$t("created_date"),
                        type: "custom-html",
                        key: "created_at",
                        isVisible: true,
                        modifier: (date) => formatDateToLocal(date),
                    },
                    {
                        title: this.$t("deals"),
                        type: "object",
                        key: "deal",
                        modifier: (value, row) => {
                            return value ? value.title : "";
                        },
                    },
                    {
                        title: this.$t("owner"),
                        type: "custom-html",
                        key: "owner",
                        modifier: (value, row) => {
                            return value ? value.full_name
                                : `<p class="m-0 font-size-90 text-secondary">` +
                                this.$t("user_deleted") +
                                `</p>`;
                        },
                    },
                    {
                        title: this.$t("tags"),
                        type: "component",
                        key: "tags",
                        isVisible: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
                        componentName: "tags-type-column",
                    },
                    {
                        title: "Action",
                        type: "action",
                        key: "invoice",
                        isVisible: (this.$can('create_proposals') ||
                            this.$can('update_proposals') ||
                            this.$can('delete_proposals')) ? true : false,
                    },
                ],
                filters: [
                    {
                        title: this.$t("owner"),
                        type: "checkbox",
                        key: "owner_is",
                        option: [],
                        permission: this.$can("manage_public_access") ? true : false,
                    },
                    {
                        title: this.$t("status"),
                        type: "checkbox",
                        key: "status",
                        option: [],
                    },
                    {
                        title: "Created date",
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"],
                    },
                    {
                        title: "Proposal have deal",
                        type: "radio",
                        key: "proposal_with_deal",
                        header: {
                            title: "Want to filter your list?",
                            description:
                                "You can filter your data table which are created based on deal",
                        },
                        option: [
                            {
                                id: 1,
                                name: "Have deal",
                            },
                            {
                                id: 2,
                                name: "Don't have deal",
                            },
                        ],
                        listValueField: "name",
                    },
                    {
                        title: this.$t("tag"),
                        type: "multi-select-filter",
                        key: "tags",
                        option: [],
                        permission: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
                    },
                ],
                showSearch: true,
                showFilter: true,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                showManageColumn: !this.$can('manage_public_access') && this.$can('client_access') ? false : true,
                orderBy: "desc",
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t("send"),
                        icon: "send",
                        type: "modal",
                        component: "app-send-proposal-modal",
                        modalId: "send-proposal-modal",
                        modifier: (row) => {
                            return row.status.name === "status_draft" ? true : false;
                        },
                    },
                    {
                        title: this.$t("duplicate"),
                        icon: "copy",
                        type: "page",
                        modifier: () => this.$can("copy_proposals"),
                    },
                    {
                        title: this.$t("edit"),
                        icon: "edit",
                        type: "page",
                        component: "app-send-proposals",
                        modalId: "",
                        url: "",
                        modifier: () => this.$can("update_proposals"),
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        component: "app-confirmation-modal",
                        modalId: "proposal-delete-modal",
                        url: "",
                        modifier: () => this.$can("delete_proposals"),
                    },
                ],
                showCount: true,
                showClearFilter: true,
            },
        };
    },
    computed: {
        ...mapGetters(["checkEmailDelivery"]),
    },
    watch: {
        'tags.length': {
            handler: function (length) {
                this.options.filters.find(({key}, index) => {
                    if (key === 'tags') {
                        this.options.filters[index].option = [...this.tags]
                    }
                })
            },
            immediate: true
        },
    },
    created() {
        this.getDealValue();
        this.$store.dispatch("checkEmailDelivery");
    },
    mounted() {
        this.getStatus();
    },
    methods: {
        getAction(rowData, actionObj, active) {
            if (actionObj.title == this.$t("edit")) {
                this.selectedUrl = route('proposals.edit', {'id': rowData.id});
                window.open(this.selectedUrl, "_self");
            } else if (actionObj.title == this.$t("delete")) {
                this.proposal = rowData.id;
                this.confirmationModalActive = true;
            } else if (actionObj.title == this.$t("send")) {
                if (this.checkEmailDelivery != 1) {
                    this.isCheckMailModalActive = true;
                } else {
                    if (rowData.deal && rowData.deal.contact_person.length && collect(rowData.deal.contact_person).first().email.length == 1) {
                        this.directSendProposal(rowData);
                    } else {
                        this.proposalDeal = rowData.deal ? rowData.deal : {};
                        this.selectUrl = route('proposals.show', {id: rowData.id});
                        this.openModal();
                    }
                }
            } else if (actionObj.title == this.$t("duplicate")) {
                this.copyUrl = route('proposals.copy', {id: rowData.id});
                window.open(this.copyUrl, "_self");
            } else if (actionObj.title == this.$t("subject")) {
                this.selectedProposalContent = rowData.content;
                this.selectProposalTitle = rowData.subject;
                this.isPreviewModalActive = true;
            }
        },

        directSendProposal(proposal) {
            this.proposalData.url = route('proposals.send');
            this.proposalData.data = {
                custom_content: proposal.content,
                status_id: this.sentStatusId,
                deal_id: proposal.deal_id,
                id: proposal.id,
                subject: proposal.subject,
                email: collect(proposal.deal.contact_person).first().email[0].value
            };
            this.axiosPost(this.proposalData)
                .then((response) => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit("reload-" + this.tableId);
                })
                .catch((error) => {
                    this.$toastr.e(error.response.data.message);
                })
                .finally(() => {
                });
        },
        getStatus() {
            this.axiosGet(
                route('statuses.index', {
                    _query: {
                        name: "status_sent",
                        type: "proposal"
                    }
                })
            ).then((res) => {
                this.sentStatusId = collect(res.data).first().id;
            });
        },
        confirmed() {
            let url = route('proposals.destroy', {'id': this.proposal});
            this.axiosDelete(url)
                .then((response) => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit("reload-" + this.tableId);
                })
                .catch(({error}) => {
                    //trigger after error
                })
                .finally(() => {
                    this.cancelled();
                });
        },
        cancelled() {
            this.confirmationModalActive = false;
        },

        openModal() {
            this.isSendProposalModalActive = true;
        },

        closeModal() {
            this.isSendProposalModalActive = false;
            $("#send-proposal-modal").modal("hide");
        },

        sendProposal() {
            if (this.checkEmailDelivery != 1) this.isCheckMailModalActive = true;
            else window.open(route('proposals.create'), "_self");
        },
        closeModalCheckMail() {
            this.isCheckMailModalActive = false;
            $("#check-email-modal").modal("hide");
        },

        getDealValue() {
            this.axiosGet(route('deal.value')).then((response) => {
                this.options.filters.push({
                    title: this.$t("deal_value"),
                    type: "range-filter",
                    key: "deal-value",
                    minTitle: this.$t("minimum_value"),
                    maxTitle: this.$t("maximum_value"),
                    maxRange: response.data.max_deal_value,
                    minRange:
                        response.data.min_deal_value < response.data.max_deal_value
                            ? response.data.min_deal_value
                            : 0,
                });
            });
        },
    },
};
</script>
