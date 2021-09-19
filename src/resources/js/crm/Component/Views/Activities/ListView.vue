<template>
    <div class="content-wrapper calendar-position-modified">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :page-title="$t('activity_list')"
                    :directory="$t('activities')"
                    :icon="'activity'"
                />
            </div>
            <div class="col-sm-12 col-md-6" v-if="$can('create_activities')">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button
                        type="button"
                        class="btn btn-primary btn-with-shadow"
                        data-toggle="modal"
                        @click.prevent="openModal()">
                        {{ $t("add_activity") }}
                    </button>
                </div>
            </div>
        </div>

        <app-table
            :id="activityModalId"
            @getFilteredValues="filteredValues"
            :options="options"
            @action="getAction"
        />

        <component
            :is="'app-activity-modal'"
            v-if="isActivityModalActive"
            :table-id="activityModalId"
            :selected-url="editedUrl"
            @close-modal="closeModal"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            modal-id="activity-delete-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
import CoreLibrary from "@core/helpers/CoreLibrary.js";
import {mapGetters} from "vuex";
import {owner} from "@app/Mixins/Global/FilterMixins";
import {formatDateToLocal, urlGenerator} from "@app/Helpers/helpers";
export default {
    name: "ListView",
    mixins: [FormMixin, owner],
    extends: CoreLibrary,
    data() {
        return {
            isActivityModalActive: false,
            activityModalId: "activity-modal",
            confirmationModalActive: false,
            activity: "",
            editedUrl: "",
            activityTypeOption: [],
            dealOptions: [],
            orgOptions: [],
            personOptions: [],
            dealId: new Set(),
            personId: new Set(),
            orgId: new Set(),
            options: {
                name: this.$t("activity_table"),
                url: route('activities.index'),
                showHeader: true,
                columns: [
                    {
                        title: this.$t("done"),
                        type: "component",
                        key: "status_id",
                        isVisible: true,
                        componentName: "app-activity-done",
                    },
                    {
                        title: this.$t("activity"),
                        type: "object",
                        key: "activity_type",
                        modifier: (value, row) => {
                            return value.name ?? null;
                        },
                    },
                    {
                        title: this.$t("title"),
                        type: "component",
                        key: "title",
                        componentName: "activity-details-page-link",
                    },
                    {
                        title: this.$t("related_to"),
                        type: "custom-html",
                        key: "contextable",
                        modifier: (value, row) => {
                            let arr = row.contextable_type.split("\\");
                            let personOrOrg = `${arr[arr.length - 1].toLowerCase()}s`;
                            let url = value['title'] ? route('deal_details.page', {id: value.id}) : route(`${personOrOrg}.edit`, {id: value.id});
                            let text = value['title'] ? value.title : value.name;
                            return `<span class="badge badge-sm badge-pill badge-${
                                row.contextable_type == 'App\\Models\\CRM\\Person\\Person'
                                    ? 'purple'
                                    : row.contextable_type == 'App\\Models\\CRM\\Deal\\Deal'
                                    ? 'success'
                                    : 'warning'}"
                            >${arr[arr.length - 1].toLowerCase()}</span>
                                <a class="mb-1 d-flex"  href="${urlGenerator(url)}" target="_new">
                                    <span class="link-list">${text}</span>

                                        <span class="text-muted mt-1">
                                        <i
                                            data-feather="external-link"
                                        /></i>
                                        </span>
                                                </a>`;
                        },
                    },
                    {
                        title: this.$t("owner"),
                        type: "custom-html",
                        key: "created_by",
                        modifier: (value, row) => {
                            return value ? value.full_name
                                : `<p class="m-0 font-size-90 text-secondary">` +
                                this.$t("user_deleted") +
                                `</p>`;
                        },
                    },
                    {
                        title: this.$t("starting_schedule"),
                        type: "custom-html",
                        key: "started_at",
                        modifier: (date, row) => (date ? formatDateToLocal(date, true, row.start_time) : "-"),
                    },
                    {
                        title: this.$t("ending_schedule"),
                        type: "custom-html",
                        key: "ended_at",
                        modifier: (date, row) => (date ? formatDateToLocal(date, true, row.end_time) : "-"),
                    },
                    {
                        title: "Action",
                        type: "action",
                        key: "invoice",
                        isVisible: true,
                    },
                ],
                filters: [
                    {
                        title: this.$t("deal"),
                        type: "dropdown-menu-filter",
                        key: "context",
                        initValue: 1,
                        listValueField: "name",
                        option: [
                            {
                                id: 1,
                                name: "Any",
                            },
                            {
                                id: 2,
                                name: "Deal",
                            },
                            {
                                id: 3,
                                name: "Person",
                            },
                            {
                                id: 4,
                                name: "Organization",
                            },
                        ],
                    },
                    {
                        title: this.$t("done_activity"),
                        type: "radio",
                        key: "done_activity",
                        header: {
                            "title": "Do you want to see done activity?",
                            "description": ""
                        },
                        option: [
                            {
                                id: 1,
                                name: "Show done activity"
                            },
                        ],
                        listValueField: 'name'
                    },
                    {
                        title: this.$t("activity"),
                        type: "checkbox",
                        key: "activity",
                        option: [],
                    },
                    {
                        title: this.$t("schedule"),
                        type: "range-picker",
                        key: "schedule",
                        option: ["today", "thisMonth", "last7Days", "thisYear"],
                    },
                    {
                        title: this.$t("owner"),
                        type: "checkbox",
                        key: "owner_is",
                        option: [],
                        permission: this.$can('manage_public_access') ? true : false
                    },
                ],
                showSearch: true,
                showFilter: true,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: this.$can('update_activities') || this.$can('delete_activities') ? true : false,
                orderBy: "desc",
                showCount: true,
                actionType: "dropdown",
                showClearFilter: true,
                actions: [
                    {
                        title: this.$t("edit"),
                        icon: "edit",
                        type: "modal",
                        component: "app-activity-modal",
                        modalId: "activity-modal",
                        url: "",
                        modifier: () => this.$can('update_activities')
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        component: "app-confirmation-modal",
                        modalId: "activity-delete-modal",
                        url: "",
                        modifier: () => this.$can('delete_activities')
                    },
                ],
            },
        };
    },

    computed: {
        ...mapGetters({
            personList: "getPerson",
            ownerList: "getOwner",
            organizationList: "getOrganization",
            dealList: "getDeal",
        }),
    },
    mounted() {
        this.$store.dispatch('getActivityStatus')
        this.$store.dispatch("getOwner"),
            this.$store.dispatch("getPerson"),
            this.$store.dispatch("getOrganization"),
            this.$store.dispatch("getDeal");
        let instance = this;
        $("#activity-modal").on("hidden.bs.modal", function (e) {
            instance.isActivityModalActive = false;
            instance.editedUrl = "";
        });

        $("#activity-delete-modal").on("hidden.bs.modal", function (e) {
            instance.confirmationModalActive = false;
        });
        this.getAllActivities();
        this.filteredValues({})
        this.$hub.$on('clearAllFilter-' + this.activityModalId, ()=> {
            this.filteredValues({});
        })
        //this.setFilterOption();
    },

    methods: {
        filteredValues(values) {
            if('done_activity' in values) this.options.url = route('activities.index');
            else this.options.url = route('activities.index', {_query: {'done_activity': ''}});
        },
        setFilterOption() {
            this.axiosGet(route('activities.index')).then((res) => {
                res.data.data.forEach((el) => {
                    if (el.contextable_type) {
                        let arr = el.contextable_type.split("\\");
                        if (arr[arr.length - 1] == "Deal") {
                            if (!this.dealId.has(el.contextable.id)) {
                                this.dealId.add(el.contextable.id);
                                this.dealOptions.push(el.contextable);
                            }
                        } else if (arr[arr.length - 1] == "Organization") {
                            if (!this.orgId.has(el.contextable.id)) {
                                this.orgId.add(el.contextable.id);
                                this.orgOptions.push(el.contextable);
                            }
                        } else if (arr[arr.length - 1] == "Person") {
                            if (!this.personId.has(el.contextable.id)) {
                                this.personId.add(el.contextable.id);
                                this.personOptions.push(el.contextable);
                            }
                        }
                    }
                });
                this.setDealOptions(this.dealOptions);
                this.setOrgOptions(this.orgOptions);
                this.setPersonOptions(this.personOptions);
            });
        },
        setDealOptions(Value) {
            let deal = this.options.filters.find(
                (elsement) => elsement.title === this.$t("deal")
            );
            deal.option = Value.map((deals) => {
                return {
                    id: deals.id,
                    value: deals.title,
                };
            });
        },
        setOrgOptions(Value) {
            let organization = this.options.filters.find(
                (elsement) => elsement.title === this.$t("organizations")
            );
            organization.option = Value.map((organizations) => {
                return {
                    id: organizations.id,
                    name: organizations.name,
                };
            });
        },
        setPersonOptions(Value) {
            let person = this.options.filters.find(
                (elsement) => elsement.title === this.$t("person")
            );
            person.option = Value.map((persons) => {
                return {
                    id: persons.id,
                    name: persons.name,
                };
            });
        },
        getAllActivities() {
            this.axiosGet(route('activity_types.index')).then((response) => {
                let activityType = this.options.filters.find(
                    (elsement) => elsement.title === this.$t("activity")
                );
                activityType.option = response.data.data.map((activities) => {
                    return {
                        id: activities.id,
                        value: activities.name,
                    };
                });
            });
        },
        getAction(rowData, actionObj, active) {
            let instance = this;
            if (actionObj.title == this.$t("edit")) {
                instance.editedUrl = route('activities.show', {'id': rowData.id});
                instance.isActivityModalActive = true;
            } else if (actionObj.title == this.$t("delete")) {
                instance.activity = rowData.id;
                instance.confirmationModalActive = true;
            }
        },
        confirmed() {
            this.axiosDelete(route('activities.destroy', {'id': this.activity}))
                .then((response) => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit("reload-" + this.activityModalId);
                })
                .catch(({error}) => {
                    //trigger after error
                })
                .finally(() => {
                    this.cancelled();
                });
        },

        cancelled() {
            let instance = this;
            instance.confirmationModalActive = false;
        },
        openModal() {
            let instance = this;
            instance.isActivityModalActive = true;
            $("#activity-modal").modal("show");
        },

        closeModal() {
            let instance = this;
            instance.isActivityModalActive = false;
            this.editedUrl = "";
            $("#activity-modal").modal("hide");
        },
    },
};
</script>

<style>
.link-list {
    white-space: nowrap !important;
    max-width: 180px;
    text-overflow: ellipsis;
    overflow: hidden;
}

.purple {
    color: #ffffff;
    background-color: #964ed8;
}

.purple:hover {
    color: #ffffff;
    background-color: #964EED;
}
</style>


