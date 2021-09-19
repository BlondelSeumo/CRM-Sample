<template>
    <app-modal modal-id="role-modal"
               modal-size="default" modal-alignment="top"
               @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title" v-if="modalTitle">{{ modalTitle }}</h5>
            <h5 class="modal-title" v-else>{{ selectedUrl ? $t('edit') : $t('add') }} {{ $t('role_lowercase') }}</h5>

            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form v-else ref="form" :data-url='selectedUrl ? selectedUrl : route(`core.roles.store`)'>
                <template v-if="modalTitle === ''">
                    <div class="form-group">
                        <label>{{ $t('role_name') }}</label>
                        <app-input
                            :placeholder="$t('role_name')"
                            :required="true"
                            v-model="formData.name"
                        />
                        <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
                    </div>
                    <div class="form-group text-center">
                        <small>{{ $t('click_on_the_group_to_expand') }}</small>
                    </div>
                </template>
                <template v-else>
                    <app-input
                        :placeholder="$t('role_name')"
                        :required="true"
                        :hidden="modalTitle ? true : false"
                        v-model="formData.name"
                    />
                    <label>{{ $t('permission') }}</label>
                </template>

                <div class="form-group" v-if="Object.keys(data.permissions).length">
                    <div id="accordionExample" class="accordion">
                        <div class="card" v-for="(permission, index) in Object.keys(data.permissions)">
                            <div class="card-header border-0">
                                <div
                                    class="custom-checkbox-default d-block position-relative text-capitalize collapsible-link py-2 cursor-pointer"
                                    data-toggle="collapse"
                                    :data-target="`#${permission}`"
                                    aria-expanded="false"
                                    :aria-expanded="`${checkForVisibility(index, permission)? true : false}`"
                                    aria-controls="permission">

                                    <div class="customized-checkbox checkbox-default">
                                        <input type="checkbox"
                                               :name="`single-checkbox-${permission}`"
                                               :id="`single-checkbox-${permission}`"
                                               :value="permission"
                                               :checked="ifChecked(permission)"
                                               @input="checkGroup($event, permission)"
                                               ref="checkbox"
                                               v-if="loadChecked"
                                               :disabled="formData.type_id ? true : false"
                                               @click="$event.stopPropagation()"/>
                                        <label class="mb-0"
                                               :for="`single-checkbox-${permission}`"
                                               @click="$event.stopPropagation()">
                                            {{ $t(permission) }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div :id="permission"
                                 data-parent="#accordionExample"
                                 :class="`collapse ${checkForVisibility(index, permission)? 'show' : ''}`">
                                <div class="card-body p-primary">
                                    <app-input type="checkbox"
                                               v-if="loaded"
                                               :list="data.permissions[permission]"
                                               v-model="checkedPermissions[permission]"
                                               @input="checkPermissions(permission)"
                                               :disabled="formData.type_id ? true : false"
                                               list-value-field="translated_name"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </template>

        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
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

</template>

<script>

import {FormSubmitMixin} from "../../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "rolesModal",
    mixins: [FormSubmitMixin],
    props: {
        modalTitle: {
            type: String
        },
        data: {
            default: function () {
                return {
                    permissions: {},
                }
            }
        }
    },
    data() {
        return {
            route,
            permissions: [],
            checkedPermissions: {},
            types: [],
            formData: {},
            loading: false,
            loadChecked: true,
            loaded: true,
            preloader: false,
            errors: []
        }
    },
    created() {
        Object.keys(this.data.permissions)
            .forEach(permission => this.checkedPermissions[permission] = []);
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            const role = {
                ...this.formData,
                permissions: this.permissions.map(permission => {
                    return {
                        permission_id: permission
                    }
                })
            }
            this.save(role);
        },
        beforeGetEditData() {
            this.preloader = true;
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.preloader = false;
            this.permissions = this.collection(response.data.permissions).pluck('id');
            Object.keys(this.data.permissions).map(permission => {
                let checked = this.data.permissions[permission].filter(p => {
                    return this.permissions.includes(p.id);
                })
                const checkPermission = checked.map((permission) => {
                    return permission.id
                })
                this.checkedPermissions[permission] = checkPermission;
            })
        },

        checkGroup(event, permission) {
            this.loaded = false;
            const permissions = this.collection(this.data.permissions[permission]).pluck('id');
            if (event.target.checked) {
                this.$set(this.checkedPermissions, permission, permissions);
                this.checkPermissions(permission);
            } else {
                this.$set(this.checkedPermissions, permission, []);
                this.permissions = this.permissions.filter(p => !permissions.includes(parseInt(p)));
            }
            this.loaded = true;
        },
        checkForVisibility(index, permission) {
            return (this.checkedPermissions[permission] && this.checkedPermissions[permission].length)
        },
        checkPermissions(permission) {
            this.loadChecked = false;
            const all_permission_of_group = this.collection(this.data.permissions[permission]).pluck('id');
            const checked_permissions = this.checkedPermissions[permission].map(p => parseInt(p));
            const removable_permissions = all_permission_of_group.filter(permission => !checked_permissions.includes(permission));
            this.permissions = this.permissions.filter(permission => !removable_permissions.includes(parseInt(permission)));
            this.permissions = Array.from(new Set(this.permissions.concat(checked_permissions)));
            this.loadChecked = true;
        },
        ifChecked(permission) {
            const permissions = this.collection(this.data.permissions[permission]).pluck();
            const checked = this.checkedPermissions[permission];
            return permissions.length === checked.length;
        },
    },

}
</script>
