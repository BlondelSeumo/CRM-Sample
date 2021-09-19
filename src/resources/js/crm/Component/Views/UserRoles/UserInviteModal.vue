<template>
    <app-modal modal-id="inviteModal" :modal-scroll="false" modal-size="large" modal-alignment="top"
               @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('invite_user') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <template v-if="checkEmailDelivery != 1">
                <app-note :title="$t('no_email_settings_found')" :notes="note" content-type="html" padding-class="p-3"/>
            </template>
            <template v-else>
                <form ref="form" data-url='admin/auth/users/invite-user'>
                    <div class="form-group">
                        <label>{{ $t('email') }}</label>
                        <app-input
                            :placeholder="$t('email')"
                            v-model="invite.email"
                            :required="true"
                        />
                        <small class="text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                    </div>

                    <div class="form-group">
                        <label>{{ $t('roles') }}</label>
                        <app-input type="search-select"
                                   list-value-field="name"
                                   :list="roleList"
                                   :required="true"
                                   :placeholder="$t('select_one')"
                                   v-model="invite.role"/>
                        <small class="text-danger" v-if="errors.roles">{{ errors.roles[0] }}</small>
                    </div>
                </form>
            </template>
        </template>

        <template slot="footer">
            <template v-if="checkEmailDelivery != 1">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                    {{ $t('close') }}
                </button>
            </template>
            <template v-else>
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                    {{ $t('close') }}
                </button>
                <button type="button" class="btn btn-primary" @click.prevent="submitData">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                    <template v-if="!loading">{{ $t('invite') }}</template>
                </button>
            </template>
        </template>
    </app-modal>

</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {urlGenerator} from "@app/Helpers/helpers";

export default {
    name: "UserInviteModal",
    mixins: [FormMixin],
    props: {
        tableId: String,
        checkEmailDelivery: {}
    },
    data() {
        return {
            note: `${this.$t('to_invite_warning')}
					<a href="${urlGenerator('settings/page?tab=Email%20setup')}">${this.$t('here')}</a> ${this.$t('to_add_email_setting')}`,

            invite: {
                roles: []
            },
            formData: {},
            loading: false,
            errors: {}
        }
    },
    computed: {
        roleList() {
            return this.$store.getters.getRole.filter(item => item.name !== 'Client');
        },
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },

        submitData() {
            this.invite.roles.push(this.invite.role);
            this.save(this.invite);
        },
        afterError(response) {
            this.loading = false;
            this.errors = response.data.errors ?? this.closeModal();
        },

        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.$hub.$emit('reload-role-modal');
            this.closeModal();
        },

        afterFinalResponse() {
            this.loading = false;
        },

        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
        },
        closeModal(value) {
            this.$emit('close-modal', value);
        },
    },

    created() {
        this.$store.dispatch('getRole');
    }
}
</script>

