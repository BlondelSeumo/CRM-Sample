<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb p-0 d-flex align-items-center mb-primary">
                        <li class="breadcrumb-item page-header d-flex align-items-center">
                            <h4 class="mb-0">
                                {{ $t('user_and_role') }}
                            </h4>
                            <app-icon class="ml-2 page-icon" :name="'user'"/>
                        </li>
                        <li class="breadcrumb-item">
                            {{ $t('user_and_role') }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button
                        type="button"
                        class="btn btn-success btn-with-shadow mr-2"
                        @click.prevent="inviteUserOpenModal()">
                        {{ $t('invite_user') }}
                    </button>
                </div>
            </div>
        </div>

        <!--Users And Roles Pages Started Here....-->

        <div class="row">
            <div class="col-sm-5">
                <app-users></app-users>
            </div>

            <div class="col-sm-7">
                <app-roles
                    :roleModal="isRoleModalActive"
                    @roleModalHide="isRoleModalActive = null">
                </app-roles>
            </div>
        </div>


        <app-user-invite-modal
            v-if="isInviterOpenModalActive"
            :check-email-delivery="checkEmailDelivery"
            :tableId="userTable"
            @close-modal="closeInviteModal"
        />

    </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {mapGetters} from "vuex";

export default {
    name: "UsersRoles",
    mixins: [FormMixin],
    components: {
        'app-users': require('./users/Users').default,
        'app-roles': require('./roles/Roles').default,
    },
    data() {
        return {
            userTable: 'user-modal',
            roleTableId: 'role-modal',
            isRoleModalActive: null,
            isInviterOpenModalActive: false,
        }
    },
    methods: {
        inviteUserOpenModal() {
            this.isInviterOpenModalActive = true;
            setTimeout(function () {
                $('#inviteModal').modal('show');
            });
        },
        closeInviteModal() {
            this.isInviterOpenModalActive = false;
            $("#inviteModal").modal('hide');
        }
    },
    created() {
        this.$store.dispatch('checkEmailDelivery');
    },
    computed: {
        ...mapGetters([
            'checkEmailDelivery',
        ])
    },
}
</script>


