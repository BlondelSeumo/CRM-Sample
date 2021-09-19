<template>
    <app-modal modal-id="manage-user-role" :modal-scroll="modalScroll" modal-size="default" modal-alignment="top"
               @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('manage_users') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon name="x"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <template v-else>
                <app-input type="multi-select"
                           :list="roleList"
                           list-value-field="name"
                           v-model="formData.roles"
                           :required="true"
                           :is-animated-dropdown="true"/>
            </template>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal"
                    @click.prevent="closeModal">
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
    name: "ManageUserRoleModal",
    mixins: [FormSubmitMixin],
    props: {
        modalScroll: {
            type: Boolean,
            default: true,
        },
        userId:{
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            //roleList: [],
            formData: {
                roles: []
            },
            preloader: false,
        }
    },
    computed: {
        roleList() {
            return this.$store.getters.getRole;
        }
    },
    methods: {
        submitData() {
            if (this.formData.roles.length > 1){
                this.$toastr.e(this.$t('more_than_one_role_is_not_allow_for_a_user'));
            }else {
                this.submitFromFixin('post', route('core.users.attach-roles', {'id': this.userId}), this.formData)
            }
        },
        afterError(response) {
            this.$toastr.e(response.data.message)
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message)
            this.$hub.$emit('reload-' + this.tableId)
            this.$hub.$emit('reload-role-modal')
            this.closeModal();
        },
        beforeGetEditData() {
            this.preloader = true;
        },
        afterSuccessFromGetEditData({data}) {
            this.formData.roles = data.roles.map(role => role.id);
            this.preloader = false;
        },

    },
    created() {
        this.$store.dispatch('getRole')
    }
}
</script>

