<template>
    <div class="card border-0 card-with-shadow card-with-search">
        <div class="card-header d-flex align-items-center p-primary primary-card-color">
            <h5 class="card-title d-inline-block mb-0">{{ $t('users') }}</h5>
            <app-search @input="getSearchValue"/>
        </div>
        <div class="p-primary d-flex align-items-center primary-card-color">
            <ul class="nav tab-filter-menu justify-content-flex-end">
                <li class="nav-item" v-for="(item, index) in userStatuses" :key="index">
                    <a
                        href="#"
                        class="nav-link py-0 font-size-default"
                        :class="[value === item.id ? 'active' : index === 0 && value === '' ? 'active': '']"
                        @click="filterUser(item.id)"
                    >{{ item.translated_name }}</a>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <app-table
                :id="userTable"
                :options="userTableOptions"
                @action="triggerActions"
                :filtered-data="filteredData"
                :search="search"
            />
        </div>

        <app-user-modal
            v-if="isUserModalActive"
            :tableId="userTable"
            :selected-url="userSelectedUrl"
            @close-modal="closeUserModal"
        />

        <app-confirmation-modal
            v-if="userConfirmationModalActive"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />

        <app-user-manage-role-modal
            v-if="isManageRoleModal"
            :tableId="userTable"
            :modal-scroll="false"
            :selected-url="userSelectedUrl"
            :user-id="userId"
            @close-modal="closeRoleModal"
        />
        <app-transfer-deal-modal
            v-if="isTransferModal"
            :user-id="userId"
            :tableId="userTable"
            @close-modal="closeTransferDealModal"
        />
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {urlGenerator} from "@app/Helpers/helpers";

export default {
    name: "Users",
    mixins: [FormMixin],
    components: {
        "app-user-media": require("./UserMedia").default,
        "app-transfer-deal-modal": require("./TransferDealModal").default,
    },
    data() {
        return {
            urlGenerator,
            isUserModalActive: false,
            userConfirmationModalActive: false,
            isManageRoleModal: false,
            isTransferModal: false,
            userId: "",
            userStatuses: [],
            userSelectedUrl: "",
            filteredData: {},
            search: "",
            value: "",
            hiddenStatus: [],
            userTable: "user-modal",

            userTableOptions: {
                tableShadow: false,
                name: "Users",
                url: route('core.users.index'),
                datatableWrapper: false,
                showHeader: false,
                tablePaddingClass: "pb-primary",
                columns: [
                    {
                        title: this.$t("user"),
                        type: "media-object",
                        key: "profile_picture",
                        mediaTitleKey: "full_name",
                        mediaSubtitleKey: "email",
                        default: "",
                        isVisible: true,
                        modifier: (value, row) => {
                            return value ? urlGenerator(value.path) : "";
                        },
                    },
                    {
                        title: this.$t("status"),
                        type: "custom-html",
                        key: "status",
                        isVisible: true,
                        modifier: (value) => {
                            return `<span class="badge badge-sm badge-pill badge-${value.class}">${value.translated_name}</span>`;
                        },
                    },
                    {
                        title: this.$t("action"),
                        type: "action",
                        key: "invoice",
                        isVisible: true,
                    },
                ],
                showSearch: false,
                showFilter: false,
                paginationType: "loadMore",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: "desc",
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t("edit"),
                        icon: "edit",
                        type: "modal",
                        component: "app-user-modal",
                        modalId: "user-modal-open",
                        name: "edit",
                    },
                    {
                        title: this.$t("delete"),
                        icon: "trash",
                        type: "modal",
                        component: "app-confirmation-modal",
                        modalId: "app-confirmation-modal",
                        name: "delete",
                    },
                    {
                        title: this.$t("status_active"),
                        type: "action",
                        alias: "status_active",
                        modifier: (row) => {
                            return row.status.name != "status_invited" && row.status.name != "status_active" ? true : false;
                        },
                    },
                    {
                        title: this.$t("de_activate"),
                        type: "action",
                        alias: "status_inactive",
                        modifier: (row) => {
                            return row.status.name != "status_invited" && row.status.name != "status_inactive" ? true : false;
                        },
                    },
                    {
                        title: this.$t("manage_role"),
                        icon: "edit",
                        type: "modal",
                        modifier: (row) => {
                            return row.roles.length > 0 ? false : true;
                        },
                    },
                ],
            },
        };
    },

    methods: {
        triggerActions(row, action, active) {
            if (action.type === "action") {
                if (
                    row.id === 1 &&
                    row.roles.map((role) => {
                        role.is_admin && role.is_default;
                    })
                ) {
                    this.$toastr.e("", this.$t("action_not_allowed"));
                    return;
                }
                this.makeAction(row, action.alias);
            }
            if (action.title === this.$t("edit")) {
                this.userSelectedUrl = route('core.users.show', {'id': row.id});
                if (
                    row.id === 1 &&
                    row.roles.map((role) => {
                        role.is_admin && role.is_default;
                    })
                ) {
                    this.$toastr.e("", this.$t("action_not_allowed"));
                    return;
                }
                this.isUserModalActive = true;
            } else if (action.title === this.$t("delete")) {

                let clientRole = row.roles.filter(role => role.name === 'Client');
                let isAdminRole = row.id === 1 && row.roles.map((role) => {
                    role.is_admin && role.is_default
                });

                if (row.status.name === 'status_invited' || Object.keys(clientRole).length > 0) {
                    this.userSelectedUrl = route('core.users.destroy', {'id': row.id});
                    if (isAdminRole) {
                        this.$toastr.e("", this.$t("action_not_allowed"));
                        return;
                    }
                    this.userConfirmationModalActive = true;

                } else if (isAdminRole) {
                    this.$toastr.e("", this.$t("action_not_allowed"));
                    return;
                } else {
                    this.userId = row.id;
                    this.isTransferModal = true;
                }

            } else if (action.title === this.$t("manage_role")) {
                if (
                    row.id === 1 &&
                    row.roles.map((role) => {
                        role.is_admin && role.is_default;
                    })
                ) {
                    this.$toastr.e("", this.$t("action_not_allowed"));
                    return;
                }
                this.userSelectedUrl = route('core.users.show', {'id': row.id});
                this.userId = row.id;
                this.isManageRoleModal = true;
            }
        },
        confirmed() {
            this.axiosDelete(this.userSelectedUrl)
                .then((response) => {
                    this.$hub.$emit("reload-" + this.userTable);
                    this.$hub.$emit('reload-role-modal')
                    this.$toastr.s(response.data.message);
                    this.userConfirmationModalActive = false;
                })
                .catch((error) => {
                    this.$toastr.e(error.response.data.message);
                });
        },
        cancelled() {
            this.userConfirmationModalActive = false;
        },
        closeRoleModal() {
            this.isManageRoleModal = false;
            $("#manage-user-role").modal("hide");
        },
        makeAction(row, alias) {
            const user = {
                first_name: row.first_name,
                last_name: row.last_name,
                status_id: this.userStatuses.find((status) => status.name === alias).id,
            };
            this.axiosPatch({
                url: this.userSelectedUrl = route('core.users.update', {'id': row.id}),
                data: user,
            })
                .then((response) => {
                    this.$toastr.s(response.data.message);
                    this.$hub.$emit("reload-" + this.userTable);
                })
                .catch((error) => {
                    this.$toastr.e(error.response.data.message);
                });
        },
        filterUser(id) {
            this.value = id;
            this.filteredData["status-id"] = id;
            setTimeout(() => {
                this.$hub.$emit("reload-user-modal");
            });
        },
        getSearchValue(event) {
            this.search = event;
            setTimeout(() => {
                this.$hub.$emit("reload-user-modal");
            });
        },
        userStatusSearch() {
            this.axiosGet(route('statuses-user')).then((response) => {
                this.userStatuses = [{id: "", translated_name: this.$t("all_user")}];
                this.userStatuses = this.userStatuses.concat(response.data);
            });
        },
        closeUserModal() {
            this.isUserModalActive = false;
            this.userSelectedUrl = "";
            $("#user-modal").modal("hide");
        },
        closeTransferDealModal() {
            this.isTransferModal = false;
            this.userId = "";
            $("#transfer-deal-modal").modal("hide");
        },
    },
    mounted() {
        this.userStatusSearch();
    },
};
</script>
