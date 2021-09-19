<template>
  <app-modal modal-id="person-modal"
             modal-size="large"
             modal-alignment="top"
             @close-modal="closeModal">
    <template slot="header">
      <h5 class="modal-title">
        {{ selectedUrl ? $t("edit_person") : $t("add_person") }}
      </h5>
      <button
          type="button"
          class="close outline-none"
          data-dismiss="modal"
          aria-label="Close">
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <form ref="form" v-if="dataLoaded" :data-url="selectedUrl ? selectedUrl : route('persons.store')">
        <div class="form-group">
          <div class="row">
            <div class="mb-0 col-sm-3 d-flex align-items-center">
              <label>{{ $t("name") }}</label>
            </div>
            <div class="col-sm-9">
              <app-input
                  type="text"
                  :placeholder="$t('enter_name')"
                  name="name"
                  v-model="formData.name"
                  :error-message="$errorMessage(errors, 'name')"
              />
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="mb-0 col-sm-3 d-flex align-items-center">
              <label>{{ $t("lead_group") }}</label>
            </div>
            <div class="col-sm-9">
              <app-input
                  type="select"
                  list-value-field="name"
                  :list="contactTypeList"
                  :placeholder="$t('choose_an_lead_group')"
                  v-model="formData.contact_type_id"
                  :error-message="$errorMessage(errors, 'contact_type_id')"
              />
            </div>
          </div>
        </div>

        <div class="form-group" v-bind:class="{ 'mb-0': organizations.length == 1 }">
          <div class="row mb-2" v-for="(input, idx) in organizations" :key="idx">
            <div class="mb-0 col-sm-3 d-flex align-items-center">
              <label v-if="organizations.length == 1" v-show="idx < 1">{{
                  $t("organization")
                }}</label>
              <label v-else v-show="idx < 1">{{ $t("organization(s)") }}</label>
            </div>
            <div class="col-sm-6 pr-2">
              <app-input
                  type="search-select"
                  list-value-field="name"
                  :list="organizationList"
                  :placeholder="$t('choose_an_organization')"
                  v-model="input.pivot.organization_id"/>
              <a href="" @click.prevent="openModal()" v-show="idx == organizations.length - 1">+{{ $t("create_new") }}</a>
            </div>
            <div class="pl-0" v-bind:class="{'col-sm-3': organizations.length == 1,'col-sm-2 pr-0': organizations.length > 1, }">
              <app-input
                  type="text"
                  :placeholder="$t('enter_job_title')"
                  v-model="input.pivot.job_title"/>
              <a href=""
                  @click.prevent="OrgAndJobAdd(idx)"
                  v-show="idx == organizations.length - 1">{{ $t("add_more") }}</a>
            </div>

            <div class="col-sm-1 pt-2 pr-0">
              <a href=""
                  class="text-muted"
                  @click.prevent="OrgAndJobRemove(idx)"
                  v-show="idx || (!idx && organizations.length > 1)">
                <app-icon width="20" stroke-width="1" name="trash"/>
              </a>
            </div>
          </div>
        </div>

          <h6 class="pb-3 pt-3">{{ $t("contact_info") }}</h6>
          <div class="form-group row">
              <label class="mb-0 col-sm-3">{{ $t("phone") }}</label>
              <div class="col-sm-9">
                  <div class="row no-gutters align-items-center mb-2" v-for="(input, index) in phone" :key="index">
                      <div class="col-sm-7">
                          <app-input
                              class="mr-2"
                              type="tel-input"
                              id="phone"
                              :placeholder="$t('enter_number')"
                              v-model="input.value"/>
                      </div>
                      <div :class="{'col-sm-5': phone.length == 1, 'col-sm-4': phone.length > 1}">
                          <app-input
                              class="mr-3"
                              type="select"
                              list-value-field="name"
                              :placeholder="$t('type')"
                              :list="phoneEmailTypeList"
                              v-model="input.type_id"/>
                      </div>
                      <div :class="{'col-sm-1': phone.length == 1, 'col-sm-1': phone.length > 1}">
                          <div class="d-flex align-items-center">
                              <a href
                                 class="text-muted"
                                 @click.prevent="phoneRemove(index)"
                                 v-show="index || (!index && phone.length > 1)">
                                  <app-icon width="20" stroke-width="1" name="trash"/>
                              </a>
                          </div>
                      </div>
                  </div>

                  <a href
                     @click.prevent="phoneAdd()">
                      {{ $t("add_more") }}
                  </a>
              </div>
          </div>
          <div class="form-group row">
              <label class="mb-0 col-sm-3">{{ $t("email") }}</label>
              <div class="col-sm-9">
                  <div class="row no-gutters align-items-center mb-2" v-for="(input, index) in emails" :key="index">
                      <div class="col-sm-7">
                          <app-input
                              class="mr-2"
                              type="email"
                              id="email"
                              :placeholder="$t('enter_email')"
                              v-model="input.value"/>
                      </div>
                      <div :class="{'col-sm-5': emails.length == 1, 'col-sm-4': emails.length > 1}">
                          <app-input
                              class="mr-3"
                              type="select"
                              list-value-field="name"
                              :placeholder="$t('type')"
                              :list="phoneEmailTypeList"
                              v-model="input.type_id"/>
                      </div>
                      <div :class="{'col-sm-1': emails.length == 1,'col-sm-1': emails.length > 1,}">
                          <div class="d-flex align-items-center">
                              <a href=""
                                 class="text-muted"
                                 @click.prevent="emailRemove(index)"
                                 v-show="index || (!index && emails.length > 1)">
                                  <app-icon width="20" stroke-width="1" name="trash"/>
                              </a>
                          </div>
                      </div>
                  </div>

                  <a href=""
                     @click.prevent="emailAdd()">
                      {{ $t("add_more") }}
                  </a>
              </div>
          </div>


        <div class="form-group" v-if="$can('manage_public_access')">
          <div class="row">
            <div class="mb-0 col-sm-3 d-flex align-items-center">
              <label>{{ $t("owner") }}</label>
            </div>
            <div class="col-sm-9">
              <app-input
                  type="select"
                  list-value-field="full_name"
                  :list="ownerList"
                  :required="true"
                  v-model="formData.owner_id"
              />
              <span class="text-danger" v-if="errors.owner_id">{{
                  errors.owner_id[0]
                }}</span>
            </div>
          </div>
        </div>

        <template v-if="addAddressDetails">
          <h6 class="pb-3 pt-3">{{ $t("address_details") }}</h6>
          <div class="form-group">
            <div class="row">
              <div class="mb-0 col-sm-3 d-flex align-items-center">
                <label>{{ $t('country') }}</label>
              </div>
              <div class="col-sm-9">
                <app-input
                    type="search-select"
                    list-value-field="name"
                    :list="countryList"
                    :placeholder="$t('choose_a_country')"
                    v-model="formData.country_id"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="mb-0 col-sm-3 d-flex align-items-center">
                <label>{{ $t('area') }}</label>
              </div>
              <div class="col-sm-9">
                <app-input
                    type="text"
                    :placeholder="$t('enter_area')"
                    v-model="formData.area"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="mb-0 col-sm-3 d-flex align-items-center">
                <label>{{ $t('city') }}</label>
              </div>
              <div class="col-sm-9">
                <app-input
                    type="text"
                    :placeholder="$t('enter_city')"
                    v-model="formData.city"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="mb-0 col-sm-3 d-flex align-items-center">
                <label>{{ $t('state') }}</label>
              </div>
              <div class="col-sm-9">
                <app-input
                    type="text"
                    :placeholder="$t('enter_state')"
                    v-model="formData.state"
                />
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="mb-0 col-sm-3 d-flex align-items-center">
                <label>{{ $t('zip_code') }}</label>
              </div>
              <div class="col-sm-9">
                <app-input
                    type="text"
                    :placeholder="$t('enter_zip_code')"
                    v-model="formData.zip_code"
                />
              </div>
            </div>
          </div>

          <div class="form-group row">
            <div class="mb-0 col-sm-3 d-flex align-items-center">
              <label>{{ $t("address") }}</label>
            </div>
            <div class="col-sm-9">
              <app-input
                  type="textarea"
                  :placeholder="$t('add_address_details_here')"
                  v-model="formData.address"
              />
            </div>
          </div>

        </template>

        <div class="form-group" v-else>
          <div class="row">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-9">
              <a href
                 @click.prevent="addAddress()">{{ $t("add_address") }}</a>
            </div>
          </div>
        </div>

        <template v-if="customFields.length && customFieldDataLoaded">
          <h6 class="pb-3 pt-3">{{ $t("custom_fields") }}</h6>
          <div class="form-group" v-for="field in customFields">
            <div class="row">
              <div class="mb-0 col-sm-3 d-flex align-items-center">
                <label>{{ field.name }}</label>
              </div>
              <div class="col-md-9">
                <template v-if="field.custom_field_type.name === 'text'">
                  <app-input
                    type="text"
                    :id="field.name"
                    v-model="customFieldValue[field.name]"
                  />
                </template>
                <template v-if="field.custom_field_type.name === 'textarea'">
                  <app-input
                    type="textarea"
                    :id="field.name"
                    v-model="customFieldValue[field.name]"
                  />
                </template>
                <template v-if="field.custom_field_type.name === 'radio'">
                  <app-input
                    type="radio"
                    :radio-checkbox-name="field.name"
                    :list="generateInputList(field)"
                    v-model="customFieldValue[field.name]"
                  />
                </template>
                <template v-if="field.custom_field_type.name === 'checkbox'">
                  <app-input
                    type="checkbox"
                    :radio-checkbox-name="field.name"
                    :list="generateInputList(field)"
                    v-model="customFieldValue[field.name]"
                  />
                </template>
                <template v-if="field.custom_field_type.name === 'select'">
                  <app-input
                    type="select"
                    :list="generateInputList(field)"
                    v-model="customFieldValue[field.name]"
                  />
                </template>
                <template v-if="field.custom_field_type.name === 'number'">
                  <app-input
                    type="number"
                    v-model="customFieldValue[field.name]"
                  />
                </template>
                <template v-if="field.custom_field_type.name === 'date'">
                  <app-input type="date" v-model="customFieldValue[field.name]"/>
                </template>
              </div>
            </div>
          </div>
        </template>
      </form>
      <app-overlay-loader v-else/>
    </template>
    <template slot="footer">
      <button
          type="button"
          class="btn btn-secondary mr-2"
          data-dismiss="modal"
          @click.prevent="closeModal"
      >
        {{ $t("cancel") }}
      </button>
      <button type="button" class="btn btn-primary" @click.prevent="submit">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
        <template v-if="!loading">{{ $t("save") }}</template>
      </button>
    </template>
  </app-modal>
</template>
<script>

import {FormSubmitMixin} from "@app/Mixins/Global/FormSubmitMixin";
import {getAllCustomFields} from "@app/Mixins/Global/CustomFieldMixin";
import {mapGetters} from "vuex";

export default {
  name: "PersonModal",
  mixins: [FormSubmitMixin, getAllCustomFields],
  data() {
    return {
        route,
      isOrganizationModalActive: false,
      addAddressDetails: false,
      dataLoaded: false,
      formData: {owner_id: user.id},
      addEditData: {},
      customFieldValue:[],
      organizations: [
        {
          pivot: {
            job_title: "",
            organization_id: "",
          },
        },
      ],
      phone: [
        {
          value: "",
          type_id: ""
        },
      ],
      emails: [
        {
          value: "",
          type_id: ""
        },
      ],
    };
  },
  computed: {
    ...mapGetters({
      ownerList: "getOwner",
      organizationList: "getOrganization",
      phoneEmailTypeList: "phoneEmailType",
      contactTypeList: "contentType",
      countryList: "getCountry",
    }),

  },
  methods: {
    addAddress(){
      this.addAddressDetails = true;
    },
    phoneAdd(index) {
      this.phone.push({value: "", type_id: ""});
    },
    phoneRemove(index) {
      this.phone.splice(index, 1);
    },
    OrgAndJobAdd(index) {
      this.organizations.push({
        pivot: {
          job_title: "",
          organization_id: "",
        },
      });
    },
    OrgAndJobRemove(index) {
      this.organizations.splice(index, 1);
    },
    emailAdd(index) {
      this.emails.push({value: "", type_id: ""});
    },
    emailRemove(index) {
      this.emails.splice(index, 1);
    },
    submit() {
      let customData = []
      this.customFields.map((el) => {
          let item = {
              value:
                  el.custom_field_type.name == "checkbox"
                      ? el.meta.split(",").filter((e, i) => {
                          if (
                              this.customFieldValue[el.name].includes(String(i)) ||
                              this.customFieldValue[el.name].includes(i)
                          ) {
                              return e;
                          }
                      })
                      : (el.custom_field_type.name == "select" ||
                      el.custom_field_type.name == "radio")
                      ? el.meta.split(",").find((e, i) => {
                          return i == Number(this.customFieldValue[el.name]);
                      })
                      : this.customFieldValue[el.name],
              custom_field_id: el.id,
          };
          customData.push(item);
      });

      this.addEditData.customs = customData;

      this.addEditData.phone = this.phone.map((item) => {
        return {
          value: item.value,
          type_id: item.type_id
        };
      });
      this.addEditData.email = this.emails.map((item) => {
        return {
          value: item.value,
          type_id: item.type_id
        };
      });


      this.addEditData.name = this.formData.name;
      this.addEditData.contact_type_id = this.formData.contact_type_id;
      this.addEditData.owner_id = this.formData.owner_id;
      this.addEditData.country_id = this.formData.country_id;
      this.addEditData.address = this.formData.address;
      this.addEditData.area = this.formData.area;
      this.addEditData.city = this.formData.city;
      this.addEditData.state = this.formData.state;
      this.addEditData.zip_code = this.formData.zip_code;
      this.addEditData.organizationData = this.organizations.map((v, i) => {
        return {
          job_title: v.pivot.job_title,
          organization_id: v.pivot.organization_id,
        };
      });
      this.save(this.addEditData);
    },
    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
        this.getAllCustomFields("person");
      if (this.formData.country ||
        this.formData.area ||
        this.formData.city ||
        this.formData.state ||
        this.formData.zip_code ||
        this.formData.address){
        this.addAddressDetails = true;
      }
      if (this.formData.phone.length) {
        this.phone = this.formData.phone;
      }
      if (this.formData.email.length) {
        this.emails = this.formData.email;
      }
      if (this.formData.organizations.length) {
        this.organizations = this.formData.organizations;
      }
    },
    openModal() {
      this.$emit("openOrgModal");
    }
  },
  mounted() {
      if (!this.selectedUrl){
          this.getAllCustomFields("person");
      }
    $("#organization-modal").on("hidden.bs.modal", () => {
      this.isOrganizationModalActive = false;
    });
  },
};
</script>
