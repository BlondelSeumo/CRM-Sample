<template>
    <div class="card border-0 card-with-shadow card-with-search h-100">
        <div class="card-header d-flex align-items-center p-primary bg-transparent">
            <h5 class="card-title d-inline-block mb-0">{{ $t('roles') }}</h5>
            <app-search @input="getSearchValue($event)"/>
        </div>
        <div class="card-body p-0">
            <app-table :id="roleTableId"
                       :options="roleOptions"
                       @action="triggerActions"
                       :search="search">

            </app-table>
        </div>
        <app-roles-modal v-if="modalOpen"
                         :modal-title="modalTitle"
                         :tableId="roleTableId"
                         :selected-url="computedSelectedUrl"
                         :data="{permissions: this.permissions}"
                         @close-modal="closeRoleModal"/>

        <app-confirmation-modal v-if="confirmationRoleModalActive"
                                modal-id="role-delete-modal"
                                @confirmed="confirmed"
                                @cancelled="cancelled"/>

        <app-manage-users-modal v-if="isManageUserModal"
                                modal-id="manage-user"
                                :tableId="roleTableId"
                                :role="role"
                                :modal-scroll="false"
                                @close-modal="closeManageUserModal"/>

        <app-view-user-modal v-if="isViewModalActive"
                                 modal-id="view-user-modal"
                                :tableId="roleTableId"
                                :role="role"
                                @close-modal="closeViewUserModal"/>
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin.js";

export default {
    name: "Roles",
    props: ['roleModal'],
    mixins: [FormMixin],
    data() {
        return {
            modalTitle: '',
            isRoleModalActive: false,
            confirmationRoleModalActive: false,
            isManageUserModal: false,
            isViewModalActive: false,
            roleSelectedUrl: '',
            search: '',
            roleTableId: 'role-modal',
            permissions: {},
            role: {},
            roleOptions: {
                tableShadow: false,
                name: 'roles',
                url: route('core.roles.index'),
                showHeader: true,
                columns: [

                    {
                        title: this.$t('role_name'),
                        type: 'text',
                        key: 'name',
                        sortAble: true,
                    },
                    {
                        title: this.$t('permission'),
                        type: 'button',
                        key: 'name',
                        className: 'btn btn-sm btn-primary rounded-pill px-3 py-1',
                        label: this.$t('manage'),
                        modifier: (value, row) => {
                            return row.is_default ? this.$t('view') : this.$t('manage')
                        }
                    },
                    {
                        title: this.$t('users'),
                        type: 'component',
                        key: 'users',
                        isVisible: true,
                        componentName: 'image-group',
                    },

                    {
                        title: this.$t('manage_users'),
                        type: 'action',
                        key: 'invoice',
                        isActive: true
                    },

                ],
                datatableWrapper: false,
                showFilter: false,
                showSearch: true,
                paginationType: "loadMore",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'modal',
                        component: 'app-roles-modal',
                        modalId: 'role-modal',
                        modifier: (row) => {
                            return row.is_default ? false : true;
                        },
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash',
                        component: 'app-confirmation-modal',
                        type: 'modal',
                        modalId: 'role-delete-modal',
                        modifier: (row) => {
                            return row.is_default || row.type_id ? false : true;
                        },
                    },
                    {
                        title: this.$t('manage_users'),
                        icon: 'tool'
                    }
                ],
            }
        }
    },

    computed: {
        modalOpen() {
            return this.roleModal == null ? this.isRoleModalActive : this.roleModal
        },
        computedSelectedUrl() {
            return this.roleModal == null ? this.roleSelectedUrl : ''
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                if (row.id === 1 && row.is_admin && row.is_default) {
                    this.$toastr.e('', this.$t('action_not_allowed'));
                    return;
                }
                this.isRoleModalActive = true;
                this.roleSelectedUrl = route('core.roles.show', {'id': row.id})

            } else if (action.title === this.$t('delete')) {
                if (row.id === 1 && row.is_admin && row.is_default) {
                    this.$toastr.e('', this.$t('action_not_allowed'));
                    return;
                }
                this.confirmationRoleModalActive = true;
                this.roleSelectedUrl = route('core.roles.show', {'id': row.id})

            } else if (action.title === this.$t('manage_users')) {
                this.isManageUserModal = true;
                this.role = row;
            } else if (action.title === this.$t('permission')) {
                this.isViewModalActive = true;
                this.role = row;
            }
        },
        closeRoleModal() {
            this.isRoleModalActive = false;
            this.modalTitle = "";
            this.$emit('roleModalHide');
            $("#role-modal").modal('hide');
        },
        closeManageUserModal() {
            this.isManageUserModal = false;
            $("#manage-user").modal('hide');
        },
        closeViewUserModal(){
            this.isViewModalActive = false;
            $("#view-user-modal").modal('hide');
            this.role = {};
        },
        confirmed() {
            this.axiosDelete(this.roleSelectedUrl).then(response => {
                this.$toastr.s(response.data.message);
                this.$hub.$emit('reload-' + this.roleTableId);
            }).catch(({error}) => {
            });
        },
        cancelled() {
            this.confirmationRoleModalActive = false;
        },
        getSearchValue(event) {
            this.search = event;
            setTimeout(() => {
                this.$hub.$emit('reload-role-modal');
            });
        },

        getAllPermission() {
            this.axiosGet(route('app.permissions')).then(response => {
                this.permissions = response.data;
            });
        },
    },
    mounted() {
        this.$store.dispatch('getUsersAndRoles');
        this.getAllPermission();
    }
}
</script>

