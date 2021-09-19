<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :page-title="$t('people')"
                    :directory="[$t('contacts'), $t('people')]"
                    :icon="'users'"
                />
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
                    <div class="dropdown d-inline-block btn-dropdown">
                        <button
                            type="button"
                            class="btn btn-success dropdown-toggle ml-0 mr-2"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                        >
                            {{ $t("actions") }}
                        </button>
                        <div class="dropdown-menu">
                            <a
                                class="dropdown-item d-flex align-items-center p-3"
                                :href="route('persons.import')"
                                v-if="$can('import_persons')"
                            >
                                <app-icon
                                    stroke-width="1"
                                    :name="'download'"
                                    width="16"
                                    height="16"
                                    class="mr-3"
                                />
                                {{ $t("import_people") }}
                            </a>
                            <a v-if="$can('export_person')"
                               class="dropdown-item d-flex align-items-center p-3"
                               :href="route('person.export')"
                            >
                                <app-icon
                                    stroke-width="1"
                                    :name="'download'"
                                    width="16"
                                    height="16"
                                    class="mr-3"
                                />
                                {{ $t("export_people") }}
                            </a>
                            <a
                                class="dropdown-item d-flex align-items-center p-3"
                                href=""
                                @click.prevent="openContactModal()"
                                v-if="$can('create_types')"
                            >
                                <app-icon
                                    stroke-width="1"
                                    :name="'plus-circle'"
                                    width="16"
                                    height="16"
                                    class="mr-3"
                                />
                                {{ $t("add_leads_group") }}
                            </a>
                            <a
                                class="dropdown-item d-flex align-items-center p-3"
                                :href="route('contact_type.list')"
                                v-if="$can('view_types')"
                            >
                                <app-icon
                                    stroke-width="1"
                                    :name="'settings'"
                                    width="16"
                                    height="16"
                                    class="mr-3"
                                />
                                {{ $t("manage_lead_group") }}
                            </a>
                        </div>
                    </div>

                    <button v-if="$can('create_persons')"
                            type="button"
                            class="btn btn-primary btn-with-shadow"
                            data-toggle="modal"
                            @click="openModal()"
                    >
                        {{ $t("add_person") }}
                    </button>
                </div>
            </div>
        </div>
        <app-table :id="tableId" :options="options" @action="getAction"/>

        <app-person-modal
            v-if="isPersonModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @openOrgModal="openOrganizationModal"
            @close-modal="closeModal"
        />
        <app-person-send-invitation
            v-if="isSendModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @close-modal="sendInvitationCloseModal"
        />
        <app-contact-type-modal
            v-if="isContactModalActive"
            :table-id="contacttableId"
            @close-modal="closeContactModal"
        />
        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="person-delete-modal"
            @cancelled="cancelled"
            @confirmed="confirmed"
        />

        <app-organization-modal
            v-if="organizationModal"
            :table-id="'organization-modal'"
            @close-modal="closeOrgModal"
        />

        <app-common-activity-modal
            v-if="personActivitiesModal"
            :activity="rowData"
            :selected-url="editedUrl"
            @close-modal="closeActivityModal"
        />

        <app-common-all-deals
            v-if="viewAllDeal"
            :id="personId"
            :context-type="'person'"
            :table-id="'details-view-modal'"
            @close-modal="closeViewAllModal"
        />
        <app-common-all-follower
            v-if="viewAllFollower"
            :follower-data="followerUrl"
            @close-modal="closedFollowerModal"
        />
        <app-common-note-modal
            v-if="isNoteModal"
            :note="noteRowData"
            @close-modal="closeNoteModal"
        />
    </div>
</template>
<script>
import CoreLibrary from "@core/helpers/CoreLibrary.js";
import {collection} from "@app/Helpers/helpers";
import {mapGetters} from "vuex";
import {contactType, organization, owner, phoneNumber, tag} from "@app/Mixins/Global/FilterMixins";
import {getCustomFileds} from "@app/Mixins/Global/CustomFieldMixin";
import {DeleteMixin} from "@app/Mixins/Global/DeleteMixin";

export default {
    name: "Person",
    extends: CoreLibrary,
    mixins: [owner, contactType, organization, tag, phoneNumber, getCustomFileds, DeleteMixin],
    data() {
        return {
            route,
            isPersonModalActive: false,
            isContactModalActive: false,
            isSendModalActive: false,
            tableId: "person-modal",
            contacttableId: "contact-modal",
            confirmationModalActive: false,
            organizationModal: false,
            personActivitiesModal: false,
            editedUrl: "",
            viewAllDeal: false,
            viewAllFollower: false,
            isNoteModal: false,
            noteRowData: null,
            personId: null,
            selectedUrl: "",
            activityData: {},
            followerUrl: "",
            commonColumn: [
                {
                    title: this.$t("name"),
                    type: "component",
                    key: "profile_picture",
                    data: "person",
                    isVisible: true,
                    componentName: "app-media-name-column",
                },
                {
                    title: this.$t("lead_groups"),
                    type: "custom-html",
                    key: "contact_type",
                    modifier: (value, row) => {
                        return value
                            ? `<span class="badge badge-pill badge-${value.class ?? "secondary"}">${
                                value.name
                            }</span>`
                            : "-";
                    },
                },
                {
                    title: this.$t("organization"),
                    type: "component",
                    key: "organizations",
                    componentName: 'app-common-person-org',
                },
                {
                    title: this.$t("emails"),
                    type: "component",
                    key: "email",
                    componentName: 'person-email-phone-column',
                },
                {
                    title: this.$t("phones"),
                    type: "component",
                    key: "phone",
                    componentName: 'person-email-phone-column',
                },
                {
                    title: this.$t("address"),
                    type: "component",
                    isVisible: true,
                    componentName: "app-common-address",
                },
                {
                    title: this.$t("closed_deal"),
                    type: "text",
                    key: "close_deals_count",
                },
                {
                    title: this.$t("opened_deal"),
                    type: "text",
                    key: "open_deals_count",
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
                    isVisible: true,
                    componentName: "tags-type-column",
                },
                {
                    title: "Action",
                    type: "action",
                    key: "invoice",
                    isVisible: true,
                },
            ],
            options: {
                name: this.$t("person_table"),
                url: route('persons.index'),
                showHeader: true,
                columns: [],
                filters: [
                    {
                        title: this.$t("created_date"),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"],
                    },
                    {
                        title: this.$t("owner"),
                        type: "checkbox",
                        key: "owner_is",
                        option: [],
                        permission: this.$can('manage_public_access') ? true : false
                    },
                    {
                        title: this.$t("lead_group"),
                        type: "checkbox",
                        key: "contact_type",
                        option: [],
                    },
                    {
                        title: this.$t("organization"),
                        type: "multi-select-filter",
                        key: "organization",
                        option: [],
                    },
                    {
                        title: this.$t("tag"),
                        type: "multi-select-filter",
                        key: "tags",
                        option: [],
                    },
                    {
                        title: this.$t("phone"),
                        type: "multi-select-filter",
                        key: "phones",
                        option: [],
                        permission: this.$can('manage_public_access') ? true : false
                    },
                ],
                showSearch: true,
                showFilter: true,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: "desc",
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t("send_invitation"),
                        icon: "zap",
                        type: "modal",
                        modifier: () => this.$can("invite_lead_person"),
                    },
                    {
                        title: this.$t("edit"),
                        icon: "edit",
                        type: "modal",
                        component: "app-person-modal",
                        modalId: "person-modal",
                        url: "",
                        modifier: () => this.$can("update_persons"),
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        component: "app-confirmation-modal",
                        modalId: "person-delete-modal",
                        url: "",
                        modifier: () => this.$can("delete_persons"),
                    },
                ],
                showCount: true,
                showClearFilter: true,
            },
        };
    },
    methods: {
        getAction(rowData, actionObj, active) {
            if (actionObj.title == this.$t("send_invitation")) {
                this.isSendModalActive = true;
                this.selectedUrl = route('persons.show', {id: rowData.id});
            } else if (actionObj.title == this.$t("edit")) {
                this.selectedUrl = route('persons.show', {id: rowData.id});
                this.isPersonModalActive = true;
            } else if (actionObj.title == this.$t("delete")) {
                this.deleteUrl = route('persons.destroy', {id: rowData.id});
                this.confirmationModalActive = true;
            }
        },
        openModal() {
            this.isPersonModalActive = true;
        },
        openContactModal() {
            this.isContactModalActive = true;
        },
        closeContactModal() {
            this.isContactModalActive = false;
            this.selectedUrl = "";
            $("#contact-type-modal").modal("hide");
        },
        closeModal() {
            this.isPersonModalActive = false;
            this.selectedUrl = "";
            $("#person-modal").modal("hide");
        },
        commonActivityModal(activity) {
            this.editedUrl = `crm/activities/${activity.id}`;
            this.rowData = activity;
            this.personActivitiesModal = true;
        },
        openViewAllDeal(personId) {
            this.personId = personId;
            this.viewAllDeal = true;
        },
        openViewAllFollower(followerUrl) {
            this.followerUrl = followerUrl;
            this.viewAllFollower = true;
        },
        editNote(activity) {
            this.noteRowData = activity;
            this.isNoteModal = true;
        },
        closeNoteModal() {
            this.isNoteModal = false;
            $("#note-modal").modal("hide");
        },
        closeViewAllModal() {
            this.viewAllDeal = false;
            $("#details-view-modal").modal("hide");
        },
        closedFollowerModal() {
            this.viewAllFollower = false;
            $("#details-view-modal").modal("hide");
        },

        closeActivityModal() {
            this.personActivitiesModal = false;
            $("#common-activity-modal").modal("hide");
        },
        sendInvitationCloseModal() {
            this.selectedUrl = "";
            this.isSendModalActive = false;
            $("#send-invitation-modal").modal("hide");
        },
        openOrganizationModal() {
            this.organizationModal = true;
            setTimeout(() => {
                $("#person-modal").css({
                    //"backdrop-filter": "blur(4px)"
                    opacity: "0.5",
                });
            });
        },
        closeOrgModal() {
            this.$store.dispatch("getOrganization");
            $("#organization-modal").modal("hide");
            this.organizationModal = false;
            $("#person-modal").css({
                opacity: "1",
                //"backdrop-filter": "blur(0px)"
            });
        },
    },
    computed: {
        ...mapGetters({
            organizationList: "getOrganization",
        }),
    },
    mounted() {
        this.getCustomFiled("person");
    },
    created() {
        this.$store.dispatch("phoneEmailType");
        this.$store.dispatch("getAllCountry");
    }
};
</script>
<style>
.person .link-list {
    white-space: nowrap !important;
    max-width: 150px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
