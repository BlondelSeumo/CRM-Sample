<template>
  <div class="card border-0 card-with-shadow mb-primary">
    <div
      class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center"
    >
      <h5 v-show="isPersonalInfoActive" class="card-title text-muted mb-0">
        {{ $t("lead_info") }}
      </h5>

      <h5 v-show="!isPersonalInfoActive" class="card-title text-muted mb-0">
        {{ $t("change_lead_info") }}
      </h5>

      <div>
        <a
          v-show="!isPersonalInfoActive"
          class="text-muted"
          href="#"
          @click.prevent="personalInfoClose"
        >
          <app-icon name="x-square" stroke-width="1" />
        </a>

        <a
          v-show="!isPersonalInfoActive"
          class="text-muted"
          href="#"
          @click.prevent="personalInfoUpdate"
        >
          <app-icon name="check-square" stroke-width="1" />
        </a>
        <a
          v-if="dealStatus"
          v-show="isPersonalInfoActive"
          class="text-muted"
          href="#"
          @click.prevent="personalInfoEdit"
        >
          <app-icon name="edit" stroke-width="1" />
        </a>
      </div>
    </div>

    <div class="card-body">
      <div v-show="isPersonalInfoActive">
        <template v-if="dealLeadInfo.contextable">
          <div class="form-group">
            <div class="form-row">
              <label class="mb-0 text-muted col-4 d-flex align-items-center">
                {{ $t("lead_type") }}
              </label>
              <div class="col-8">
                {{ dealLeadInfo.lead_type == 1 ? $t("person") : $t("organization") }}
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="form-row">
              <label class="mb-0 text-muted col-4 d-flex align-items-center">
                {{ $t("name") }}
              </label>
              <div class="col-8">
                <div class="d-flex align-items-center primary-text-color">
                  <app-avatar
                    :title="dealLeadInfo.contextable.name"
                    class="mr-2"
                    :avatar-class="'avatars-w-30'"
                    :img="
                      dealLeadInfo.contextable.profile_picture
                        ? urlGenerator(dealLeadInfo.contextable.profile_picture.path)
                        : dealLeadInfo.contextable.profile_picture
                    "
                  />
                  <a target="_blank" :href="urlGenerator(`crm/${componentType}/${dealLeadInfo.contextable.id}/edit`)">{{ dealLeadInfo.contextable.name }}</a>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group" v-if="dealLeadInfo.lead_type == 2">
            <div class="form-row">
              <label class="mb-0 text-muted col-4 d-flex align-items-center">
                {{ $t("contact_person") }}
              </label>
              <template v-for="contactPerson in dealLeadInfo.contact_person">
                <div class="col-8">
                  <div class="d-flex align-items-center primary-text-color">
                    <app-avatar
                      :title="contactPerson.name"
                      class="mr-2"
                      :avatar-class="'avatars-w-30'"
                      :img="
                      contactPerson.profile_picture
                        ? urlGenerator(contactPerson.profile_picture.path)
                        : contactPerson.profile_picture
                    "
                    />
                    <a target="_blank" :href="urlGenerator(`crm/persons/${contactPerson.id}/edit`)">{{ contactPerson.name }}</a>
                  </div>
                </div>
              </template>
              <template v-if="!dealLeadInfo.contact_person.length">
                <p class="col-8 text-muted mb-0">{{ $t("not_added_yet") }}</p>
              </template>
            </div>
          </div>

          <div class="form-group mb-0">
            <div class="form-row">
              <label class="mb-0 text-muted col-4 d-flex align-items-center">
                {{ $t("owner") }}
              </label>

              <div class="col-8">
                <div class="d-flex align-items-center">
                  <app-avatar
                    :avatar-class="'avatars-w-30'"
                    :img="
                      dealLeadInfo.contextable.owner.profile_picture
                        ? urlGenerator(
                            dealLeadInfo.contextable.owner.profile_picture.path
                          )
                        : dealLeadInfo.contextable.owner.profile_picture
                    "
                    :title="dealLeadInfo.contextable.owner.full_name"
                    class="mr-2"
                  />
                  <span>{{ dealLeadInfo.contextable.owner.full_name }}</span>
                </div>
              </div>
            </div>
          </div>
        </template>

        <template v-else>
          <p class="text-muted">{{ $t("no_lead_linked_yet") }}</p>
          <a class="font-size-80" href="#" @click.prevent="personalInfoEdit">
            {{ $t("link_lead_the_deal") }}
          </a>
        </template>
      </div>

      <div v-show="!isPersonalInfoActive">
        <form ref="form">
          <div class="form-group" v-if="dealLeadInfo.lead_type == 2">
            <div class="form-row align-items-center">
              <label class="mb-0 text-muted col-4 d-flex align-items-center">
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
          </div>

          <div class="form-group">
            <div class="form-row align-items-center">
              <label class="mb-0 text-muted col-4 d-flex align-items-center">
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
              <div class="col-1" v-if="dealLeadInfo.lead_type == 2 && formData.person_id">
                <a
                  class="text-muted"
                  href="#"
                  @click.prevent="detachContactPerson(dealLeadInfo.id)"
                >
                  <app-icon name="trash" stroke-width="1" width="20" />
                </a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
import { urlGenerator } from "../../../../Helpers/helpers";

export default {
  name: "DealLeadInfo",
  props: ["dealLeadInfo", "personList", "organizationList", 'componentType'],
  mixins: [FormMixin],
  data() {
    return {
      isPersonalInfoActive: true,
      personalInfo: {},
      formData: {
        contextable_id: null,
        person_id: null,
      },
      preLoader: false,
      urlGenerator,
    };
  },
  computed: {
    personAsOrg() {
      if (this.dealLeadInfo.lead_type == 2) {
        return this.personList.filter((item) =>
          item.organizations.find((el) => el.id == this.formData.contextable_id)
        );
      }
      return this.personList;
    },
    dealStatus() {
      return (
        this.dealLeadInfo.status.name == "status_open" &&
        this.dealLeadInfo.status.type == "deal"
      );
    },
  },
  methods: {
    resetPerson() {
      this.formData.person_id = null;
    },
    personalInfoClose() {
      this.isPersonalInfoActive = true;
    },
    personalInfoEdit() {
      this.formData.contextable_id = this.dealLeadInfo.contextable_id;
      this.formData.person_id = this.dealLeadInfo.contact_person.length
        ? this.dealLeadInfo.contact_person[0].id
        : null;
      this.isPersonalInfoActive = false;
    },
    personalInfoUpdate() {
      this.formData.title = this.dealLeadInfo.title;
      this.formData.lead_type = this.dealLeadInfo.lead_type;
      this.formData.contextable_id =
        this.dealLeadInfo.lead_type == 1
          ? this.formData.person_id
          : this.formData.contextable_id;
      this.submitFromFixin("PATCH", `crm/deals/${this.dealLeadInfo.id}`, this.formData);
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.isPersonalInfoActive = true;
      this.$emit("update-request");
    },
    afterError(error) {
      this.$toastr.e(error.data.message);
    },
    detachContactPerson(id) {
      this.axiosPut({
        url: `crm/deal/person/delete/${id}`,
        data: { deal_id: id },
      })
        .then((response) => {
          this.$toastr.e(response.data.message);
          this.isPersonalInfoActive = true;
          this.preLoader = true;
          this.$emit("update-request");
        })
        .catch(({ error }) => {});
    },
  },
};
</script>
