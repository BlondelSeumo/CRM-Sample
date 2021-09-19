<template>
    <app-modal modal-id="view-user-modal"
               modal-size="default" modal-alignment="top"
               @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ role.name }} : {{ $t('permission') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <template v-if="role.name === 'App Admin'">
                <p>
                    App admin (Application administrator) performs all admin activities and has full management access.
                    Also
                    can add & configure application settings, create users through users invitation & assign a role with
                    application permissions
                </p>
                <p class="mt-3">
                    N.B: Remember that your application must have at least one individually assigned App admin. If you
                    just
                    have one individually assigned super admin, you can't edit or revoke their admin permissions.
                </p>
            </template>
            <template v-if="role.name === 'Manager'">
                <p>
                    Managers perform user-related activities for specific modules of the application. Managers can view
                    and manage(add/edit/delete) as well as all Leads, Deals, Proposals, Activities & Reports. Also can
                    view a presentation of these data in the dashboard.
                </p>
            </template>
            <template v-if="role.name === 'Agent'">
                <p>
                    Agent has a fixed set of permissions, but there are also restrictions on what this role can do.
                </p>

               <p>Agent have the following permissions:</p>

                <ol>
                    <li> Can view Dashboard (Only his/her deals related data).</li>
                    <li> Can add leads People and Organization (manage his/her own created People and Organization).
                    </li>
                    <li>Can view & use lead groups.</li>
                    <li>Can view & use the Pipeline/Kanban view.</li>
                    <li>Can add deals for his/her person and organization.</li>
                    <li>Can manage and comment only on his/her own created deal.</li>
                    <li>Can view and use lost reasons.</li>
                    <li>Can send proposals to his/her own lead of a deal.</li>
                    <li>Can create activities within his/her own People, Organizations and deals.</li>
                </ol>

            </template>
            <template v-if="role.name === 'Client'">
                <p>
                    Clients (Leads) have view and communication access to most data in the application. You can manage
                    Leads by inviting as "Client" users from the <b>Leads (People)</b> module action.
                </p>
                <p>
                    Client can view the following:
                </p>
                <ol>
                    <li>Can view Dashboard (Only his/her deals related data).</li>
                    <li>Can view organizations that he/she belongs to.</li>
                    <li>Can view his/her own deals and comment on these deals.</li>
                    <li>Can view proposals list and Kanban view.</li>
                </ol>
            </template>
        </template>

        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('close') }}
            </button>
        </template>
    </app-modal>
</template>

<script>

import {FormSubmitMixin} from "../../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "ViewModal",
    mixins: [FormSubmitMixin],
    props: {
        role: {}
    },
    data() {
        return {
            formData: {}
        }
    },
}
</script>

