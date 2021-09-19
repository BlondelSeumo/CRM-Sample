<template>
    <div>
        <app-modal modal-id="manage-user" :modal-scroll="modalScroll" modal-size="default" modal-alignment="top"
                   @close-modal="closeManageUserModal">
            <template slot="header">
                <h5 class="modal-title">{{ $t('manage_users') }}</h5>
                <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
                </button>
            </template>
            <template slot="body">
                <app-overlay-loader v-if="preloader"/>
                <template v-else>
                    <div v-for="(user, index) in user_list" :key="index"
                         class="d-flex align-items-center justify-content-between"
                         :class="{'pb-3 mb-3 border-bottom': index !== user_list.length - 1}">
                        <div class="media d-flex align-items-center">
                            <div class="avatars-w-50 ml-2">
                                <app-avatar
                                    :title="user.full_name"
                                    status="success"
                                    :shadow="true"
                                    :img="user.profile_picture ?
                                        user.profile_picture.path :
                                        user.profile_picture"
                                    :alt-text="user.full_name"
                                />
                            </div>
                            <div class="media-body ml-3">
                                {{ user.full_name }}
                                <p class="text-muted font-size-90 mb-0">{{ user.email }}</p>
                            </div>
                        </div>
                    </div>
                    <form ref="form" :data-url="route('roles.attach_app_users_to', {'id': role.id})">
                        <div class="mt-primary" v-if="role.name != 'Client'">
                            <app-input type="multi-select"
                                       :list="attachableUsers"
                                       list-value-field="full_name"
                                       v-model="formData.users"
                                       :required="true"
                                       :is-animated-dropdown="true"
                            />
                            <span class="text-danger" style="font-size: 12px;" v-if="errors.users">{{
                                    errors.users[0]
                                }}</span>
                        </div>
                    </form>
                    <template v-if="role.is_admin">
                        <app-note :title="$t('note')"
                                  :notes="$t('you_can_add_more_users_in_this_app_admin_role_but_you_cant_remove_the_app_admin_users')"
                                  content-type="html" padding-class="p-3"/>
                    </template>

                    <div class="mt-5" v-if="role.name == 'Client'">
                        <app-note :title="$t('note')"
                                  :notes="$t('you_can_manage_invite_client_user_from_people_module')"
                                  content-type="html" padding-class="p-3"/>
                    </div>

                </template>
            </template>
            <template slot="footer" v-if="role.name != 'Client'">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal"
                        @click.prevent="closeManageUserModal">
                    {{ $t('cancel') }}
                </button>
                <button type="button" class="btn btn-primary" @click.prevent="submitData">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                    <template v-if="!loading">{{ $t('save') }}</template>
                </button>
            </template>

        </app-modal>
    </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";

export default {
    name: "ManageUserModal",
    mixins: [FormMixin],
    props: {
        role: {
            required: true
        },
        tableId: String,
        modalScroll: {
            type: Boolean,
            default: true,
        }
    },
    data() {
        return {
            route,
            user_list: [],
            formData: {
                users: []
            },
            loading: false,
            preloader: false,
            userInfo: {},
            errors: {},
        }
    },
    computed: {
        attachableUsers() {
            return this.$store.getters.getUsers
        },
        usersROles() {
            return this.$store.getters.getUsersAndRoles
        },
    },

    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.user_list = this.attachableUsers.filter(user => {
                return this.formData.users.includes(user.id);
            }).concat(this.user_list);

            this.$store.dispatch('getUsers', {
                users: this.role.users.map((item) => {
                    return item.id
                }).concat(this.formData.users)
            });
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.closeManageUserModal();
        },
        afterError(response) {
            this.formData.users = [];
            this.errors = response.data.errors;
        },

        afterFinalResponse() {
            this.loading = false;
        },

        closeManageUserModal(value) {
            this.$emit('close-modal', value);
        },

    },
    watch: {
        'role.users.length': {
            handler: function (users) {
                this.user_list = this.role.users;
                this.$store.dispatch(
                    'getUsers', {
                        users: this.role.users.map((item) => {
                            return item.id
                        })
                    });
            },
            immediate: true
        },

    },


}
</script>
