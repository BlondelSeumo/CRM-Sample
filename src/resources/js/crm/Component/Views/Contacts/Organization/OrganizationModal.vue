<template>
  <app-modal
    modal-id="organization-modal"
    modal-size="default"
    modal-alignment="top"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title">{{ selectedUrl ? $t("edit_organization") : $t("add_organization") }}</h5>
      <button
        type="button"
        class="close outline-none"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <form
        ref="form"
        v-if="dataLoaded"
        :data-url="selectedUrl ? selectedUrl : route('organizations.store')"
      >
        <div class="form-group row">
          <div class="mb-0 col-sm-3 d-flex align-items-center">
            <label>{{ $t("name") }}</label>
          </div>
          <div class="col-sm-9">
            <app-input
              type="text"
              :placeholder="$t('organization_name')"
              v-model="formData.name"
              :error-message="$errorMessage(errors, 'name')"
            />
          </div>
        </div>

        <div class="form-group row">
          <div class="mb-0 col-sm-3 d-flex align-items-center">
            <label>{{ $t("lead_group") }}</label>
          </div>
          <div class="col-sm-9">
            <app-input
              type="select"
              list-value-field="name"
              :list="contentTypeList"
              :placeholder="$t('choose_an_lead_group')"
              v-model="formData.contact_type_id"
              :error-message="$errorMessage(errors, 'contact_type_id')"
            />
          </div>
        </div>

        <div class="form-group row" v-if="$can('manage_public_access')">
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
              :error-message="$errorMessage(errors, 'owner_id')"
            />
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
      <app-overlay-loader v-else />
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
      <button type="button" class="btn btn-primary" @click.prevent="submitData">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
        <template v-if="!loading">{{ $t("save") }}</template>
      </button>
    </template>
  </app-modal>
</template>

<script>

import { mapGetters } from "vuex";
import { getAllCustomFields } from "@app/Mixins/Global/CustomFieldMixin";
import {FormSubmitMixin} from "@app/Mixins/Global/FormSubmitMixin";

export default {
  name: "OrganizationModal",
  mixins: [FormSubmitMixin, getAllCustomFields],
  data() {
    return {
        route,
      formData: { owner_id: user.id },
      addAddressDetails: false,
      dataLoaded: false,
      customFieldValue:[],
    };
  },
  computed: {
    ...mapGetters({
      ownerList: "getOwner",
      contentTypeList: "contentType",
      countryList: "getCountry",
    }),
  },
  methods: {
    addAddress(){
      this.addAddressDetails = true;
    },
    submitData() {
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

      this.formData.customs = customData;
      //console.log(this.formData.customs)
      this.save(this.formData);
    },

    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
        this.getAllCustomFields("organization");
      if (this.formData.country ||
        this.formData.area ||
        this.formData.city ||
        this.formData.state ||
        this.formData.zip_code ||
        this.formData.address){
        this.addAddressDetails = true;
      }
    },
  },
  mounted() {
      if (!this.selectedUrl){
          this.getAllCustomFields("organization");
      }
  },
};
</script>
