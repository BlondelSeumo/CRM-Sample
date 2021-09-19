<template>
    <div class="border-bottom mb-4 pb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <p class="mb-2 font-weight-bold">
                {{ $t("lead_type") }} :
                {{
                    formData.contextable_type ==
                    "App\\Models\\CRM\\Person\\Person"
                        ? $t("person")
                        : $t("organization")
                }}
            </p>
            <div>
                <a
                    v-show="!isEditLeadType"
                    class="text-muted"
                    href="#"
                    @click.prevent="closeLeadTypeEdit"
                >
                    <app-icon name="x-square" class='size-20' stroke-width="1" />
                </a>
                <a
                    v-show="!isEditLeadType"
                    class="text-muted"
                    href="#"
                    @click.prevent="updateLeadInfo"
                >
                    <app-icon name="check-square" class='size-20' stroke-width="1" />
                </a>
            </div>
            <a
                v-show="isEditLeadType && clientAccess && dealStatus"
                class="text-muted"
                href="#"
                @click.prevent="editLeadInfo"
            >
                <app-icon name="edit" class='size-20' stroke-width="1" />
            </a>
        </div>
        <div v-if="isEditLeadType">
            <div class="d-flex justify-content-start mb-2">
                <app-avatar
                    avatar-class="avatars-w-20"
                    title="John Doe"
                    img="/images/profile.png"
                />
                <p class="text-muted font-size-90 mb-0 ml-2">
                    {{ formData.contextable ? formData.contextable.name : $t("no_lead_added") }}
                </p>
            </div>
            <div class="d-flex justify-content-start" v-if="formData.lead_type == 2">
                <app-avatar
                    avatar-class="avatars-w-20"
                    title="John Doe"
                    img="/images/profile.png"
                />
                <p class="text-muted font-size-90 mb-0 ml-2">
                    {{
                        formData.contact_person.length
                            ? formData.contact_person[0].name
                            : $t("no_contact")
                    }}
                </p>
            </div>
        </div>
        <div v-show="!isEditLeadType">
            <template v-if="formData.lead_type == 2">
                <div class="form-group row align-items-center">
                    <label class="mb-0 col-4 d-flex align-items-center">
                        {{ $t("lead") }}
                    </label>
                    <div class="col-8">
                        <app-input
                            v-model="formData.contextable_id"
                            :list="organizationList"
                            :placeholder="$t('choose_one')"
                            list-value-field="name"
                            type="select"
                            @input="resetPerson"
                        />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="mb-0 col-4 d-flex align-items-center">
                        {{ $t("contact_person") }}
                    </label>
                    <div v-bind:class="{ 'col-8': !formData.person_id, 'col-6': (formData.person_id && formData.lead_type == 2) }">
                        <app-input
                            v-model="formData.person_id"
                            :list="personAsOrg"
                            :placeholder="$t('choose_a_contact_person')"
                            list-value-field="name"
                            type="select"
                        />
                    </div>
                    <div class="col-1" v-if="formData.lead_type == 2 && formData.person_id">
                        <a
                            class="text-muted"
                            href="#"
                            @click.prevent="detachContactPerson(formData.id)"
                        >
                            <app-icon name="trash" stroke-width="1" width="20"/>
                        </a>
                    </div>
                </div>
            </template>
            <template v-else>
                <div class="form-group row align-items-center">
                    <label class="mb-0 col-4 d-flex align-items-center">
                        {{ $t("lead") }}
                    </label>
                    <div class="col-8">
                        <app-input
                            v-model="formData.contextable_id"
                            :list="personList"
                            :placeholder="$t('choose_one')"
                            list-value-field="name"
                            type="select"
                        />
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
import {mapGetters} from "vuex";
export default {
    name: "LeadInfoAddEdit",
    props:['formData', 'stages', 'clientAccess', 'dealStatus'],
    mixins: [FormMixin],
    data(){
        return{
            errors: [],
            isEditLeadType: true,
        }
    },
    computed:{
        ...mapGetters({
            personList: "getPerson",
            organizationList: 'getOrganization'
        }),
        personAsOrg() {
            if (this.formData.lead_type == 2) {
                return this.personList.filter((item) =>
                    item.organizations.find((el) => el.id == this.formData.contextable_id)
                );
            }
            return this.personList;
        },
    },
    mounted() {
        if (!(!this.$can('manage_public_access') && this.$can('client_access'))){
            this.$store.dispatch("getPerson");
            this.$store.dispatch("getOrganization");
        }

    },
    methods:{
        resetPerson() {
            this.formData.person_id = null;
        },
        detachContactPerson(id) {
            this.axiosPut({
                url: route('deal.delete-person', {id: id}),
                data: {deal_id: id},
            }).then((response) => {
                this.afterSuccess(response)
            })
        },
        editLeadInfo(){
            this.formData.person_id = this.formData.contact_person.length ?
                this.formData.contact_person[0].id :
                null;
            this.isEditLeadType = false;
        },
        closeLeadTypeEdit(){
            this.isEditLeadType = true;
        },
        updateLeadInfo(){
            let dealData = {};
            dealData.title = this.formData.title;
            dealData.lead_type = this.formData.lead_type;
            dealData.contextable_id = this.formData.contextable_id;
            dealData.person_id = this.formData.lead_type == 1 ?
                                 this.formData.contextable_id :
                                 this.formData.person_id;
            this.submitFromFixin('patch', route('deals.update', {id: this.formData.id}), dealData)
        },
        afterSuccess(response) {
            this.isEditLeadType = true;
            this.$toastr.s(response.data.message);
            this.$emit("update-request");
        },
    }
}
</script>
