<template>
    <div class="card border-0 card-with-shadow mb-primary">
        <div
            class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center"
        >
            <h5 v-show="isDetailsEditable" class="card-title text-muted mb-0">
                {{ $t("details") }}
            </h5>
            <h5 v-show="!isDetailsEditable" class="card-title text-muted mb-0">
                {{ $t("edit_details") }}
            </h5>
            <div>
                <a
                    v-show="!isDetailsEditable"
                    class="text-muted"
                    href="#"
                    @click.prevent="showDetailsHide"
                >
                    <app-icon name="x-square" stroke-width="1"/>
                </a>
                <a
                    v-show="!isDetailsEditable"
                    class="text-muted"
                    href="#"
                    @click.prevent="showDetailsSave"
                >
                    <app-icon name="check-square" stroke-width="1"/>
                </a>

                <a
                    v-if="dealOpen"
                    v-show="isDetailsEditable"
                    class="text-muted"
                    href="#"
                    @click.prevent="showDetailsEditable"
                >
                    <app-icon name="edit" stroke-width="1"/>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div v-show="isDetailsEditable">
                <div class="form-group">
                    <label class="mb-0 text-muted">
                        {{ $t("title") }}
                    </label>
                    <div class="mt-2">
                        {{ dealDetails.title }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="mb-0 text-muted">
                        {{ $t("description") }}
                    </label>
                    <div class="mt-2">
                        {{ dealDetails.description ? dealDetails.description : $t("not_added_yet") }}
                    </div>
                </div>

                <div class="form-group row" v-if="dealDetails.status">
                    <label class="mb-0 text-muted col-4 d-flex align-items-center">
                        {{ $t("status") }}
                    </label>
                    <div class="col-8">
                        <app-badge
                            :className="'badge-sm badge-pill badge-' + dealDetails.status.class"
                            :label="dealDetails.status.translated_name"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="mb-0 text-muted col-4 d-flex align-items-center">
                        {{ $t("value") }}
                    </label>
                    <div class="col-8">
                        {{ dealDetails.value ? dealDetails.value : $t("not_added_yet") }}
                    </div>
                </div>

                <template v-if="dealDetails.contextable">
                    <div class="form-group row">
                        <label class="mb-0 text-muted col-4 d-flex align-items-center">
                            {{ $t("lead_type") }}
                        </label>
                        <div class="col-8">
                            {{ dealDetails.lead_type == 1 ? $t("person") : $t("organization") }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="mb-0 text-muted col-4 d-flex align-items-center">
                            {{ $t("name") }}
                        </label>
                        <div class="col-8">
                            <div class="d-flex align-items-center primary-text-color">
                                <app-avatar
                                    :title="dealDetails.contextable.name"
                                    :avatar-class="'avatars-w-30'"
                                    class="mr-2"
                                    :img="dealDetails.contextable.profile_picture ?
                                        urlGenerator(dealDetails.contextable.profile_picture.path) :
                                        dealDetails.contextable.profile_picture"
                                />
                                <a target="_blank"
                                   :href="urlGenerator(`/${componentType}/${dealDetails.contextable.id}/edit`)">
                                    {{ dealDetails.contextable.name }}
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row" v-if="dealDetails.lead_type == 2">
                        <label class="mb-0 text-muted col-4 d-flex align-items-center">
                            {{ $t("contact_person") }}
                        </label>
                        <template v-for="contactPerson in dealDetails.contact_person">
                            <div class="col-8">
                                <div class="d-flex align-items-center primary-text-color">
                                    <app-avatar
                                        :title="contactPerson.name"
                                        class="mr-2"
                                        :avatar-class="'avatars-w-30'"
                                        :img="contactPerson.profile_picture ?
                                            urlGenerator(contactPerson.profile_picture.path) :
                                            contactPerson.profile_picture"
                                    />
                                    <a target="_blank" :href="route('persons.edit', {id: contactPerson.id})">
                                        {{ contactPerson.name }}</a>
                                </div>
                            </div>
                        </template>
                        <template v-if="!dealDetails.contact_person.length">
                            <p class="col-8 text-muted mb-0">{{ $t("not_added_yet") }}</p>
                        </template>
                    </div>
                </template>

                <div class="form-group row">
                    <label class="mb-0 text-muted col-4 d-flex align-items-center">
                        {{ $t("expected_closing_date") }}
                    </label>
                    <div class="col-8 text-muted">
                        {{
                            dealDetails.expired_at == null
                                ? $t("not_added_yet")
                                : formatDateToLocal(dealDetails.expired_at)
                        }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="mb-0 text-muted col-4 d-flex align-items-center">
                        {{ $t("owner") }}
                    </label>
                    <div v-if="dealDetails.owner" class="col-8">
                        <div class="d-flex align-items-center">
                            <app-avatar
                                :avatar-class="'avatars-w-30'"
                                class="mr-2"
                                :img="dealDetails.owner.profile_picture ?
                                urlGenerator(dealDetails.owner.profile_picture.path) : dealDetails.owner.profile_picture"
                                :title="dealDetails.owner.full_name"
                            />
                            <span>{{ dealDetails.owner.full_name }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <div v-show="!isDetailsEditable">
                <div class="form-group">
                    <label> {{ $t("title") }} </label>
                    <div class="">
                        <app-input v-model="dealDetails.title" type="text" :placeholder="$t('enter_title')"/>
                    </div>
                </div>

                <div class="form-group">
                    <label> {{ $t("description") }} </label>
                    <div class="">
                        <app-input type="textarea" v-model="dealDetails.description" :placeholder="$t('description_here')"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-4 mb-0 d-flex align-items-center">
                        {{ $t("value") }}
                    </label>
                    <div class="col-8">
                        <app-input v-model="dealDetails.value" type="number" :placeholder="$t('value')"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="mb-0 col-4 d-flex align-items-center">
                        {{ $t("lead_type") }}</label>
                    <div class="col-8">
                        <app-input
                            v-model="formData.lead_type"
                            :list="[{id:1, value: 'Person'}, {id:2, value: 'Organization'}]"
                            type="radio"
                            @change="changeLeadType"/>
                    </div>
                </div>

                <div class="form-group row align-items-center" v-if="formData.lead_type == 1">
                    <label class="mb-0 col-4 d-flex align-items-center">
                        {{ $t("lead") }}
                    </label>
                    <div class="col-8">
                        <app-input
                            v-model="formData.contextable_id"
                            :list="personList"
                            :placeholder="$t('choose_one')"
                            list-value-field="name"
                            type="search-select"
                        />
                    </div>
                </div>

                <div class="form-group row align-items-center" v-if="formData.lead_type == 2">
                    <label class="mb-0 col-4 d-flex align-items-center">
                        {{ $t("lead") }}
                    </label>
                    <div class="col-8">
                        <app-input
                            v-model="formData.contextable_id"
                            :list="organizationList"
                            :placeholder="$t('choose_one')"
                            list-value-field="name"
                            type="search-select"
                            @input="resetPerson"
                        />
                    </div>
                </div>

                <div class="form-group row align-items-center" v-if="formData.lead_type == 2">
                    <label class="mb-0 col-4 d-flex align-items-center">
                        {{ $t("contact_person") }}
                    </label>
                    <div v-bind:class="{ 'col-8': !formData.person_id, 'col-7': formData.person_id }">
                        <app-input
                            v-model="formData.person_id"
                            :list="personAsOrg"
                            :placeholder="$t('choose_a_contact_person')"
                            list-value-field="name"
                            type="search-select"
                        />
                    </div>
                    <div class="col-1 pl-0" v-if="formData.lead_type == 2 && formData.person_id">
                        <a
                            class="text-muted"
                            href="#"
                            @click.prevent="detachContactPerson(dealDetails.id)"
                        >
                            <app-icon name="trash" stroke-width="1" width="20"/>
                        </a>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-4 mb-0 d-flex align-items-center">
                        {{ $t("expected_closing_date") }}</label>
                    <div class="col-8">
                        <app-input v-model="formData.expired_at" type="date"/>
                    </div>
                </div>
                <div v-if="$can('manage_public_access')" class="form-group row mb-0">
                    <label class="col-4 mb-0 d-flex align-items-center">
                        {{ $t("owner") }}</label>
                    <div v-if="dealDetails.owner" class="col-8">
                        <app-input
                            v-model="dealDetails.owner_id"
                            :list="ownerList"
                            list-value-field="full_name"
                            type="select"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {formatDateToLocal, urlGenerator} from "@app/Helpers/helpers";

export default {
    name: "DealDetails",
    props: ["dealDetails", "statusList", "ownerList", "personList", "organizationList", 'componentType'],
    mixins: [FormMixin],
    data() {
        return {
            urlGenerator,
            route,
            isDetailsEditable: true,
            formData: {
                contextable_id: null,
                person_id: null,
                lead_type: this.dealDetails.lead_type
            },
            formatDateToLocal,
        };
    },
    computed: {
        dealOpen() {
            return this.dealDetails.status.name == "status_open";
        },
        personAsOrg() {
            return this.personList.filter((item) =>
                item.organizations.find((el) => el.id == this.formData.contextable_id)
            );
        },
    },
    methods: {
        changeLeadType(){
            this.formData.contextable_id = null;
        },
        showDetailsSave() {
            this.formData.title = this.dealDetails.title;
            this.formData.value = this.dealDetails.value;
            this.formData.description = this.dealDetails.description;
            this.formData.lead_type = this.formData.lead_type;
            this.formData.person_id =
                this.formData.lead_type == 1 ? this.formData.contextable_id : this.formData.person_id;

            this.formData.expired_at = this.formData.expired_at ?
                moment(this.formData.expired_at).format("YYYY-MM-DD") : null;
            this.formData.owner_id = this.dealDetails.owner_id;

            this.submitFromFixin("PATCH", route('deals.update', {id: this.dealDetails.id}), this.formData);
        },


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

        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.isDetailsEditable = true;
            this.$emit("update-request");
        },

        showDetailsHide() {
            this.isDetailsEditable = true;
        },
        showDetailsEditable() {
            this.formData.contextable_id = this.dealDetails.contextable_id;
            this.formData.person_id = this.dealDetails.contact_person.length
                ? this.dealDetails.contact_person[0].id
                : null;

            this.isDetailsEditable = false;
        },
    },
    mounted() {
        this.formData.expired_at = this.dealDetails.expired_at
            ? new Date(this.dealDetails.expired_at)
            : null;
    },
};
</script>
