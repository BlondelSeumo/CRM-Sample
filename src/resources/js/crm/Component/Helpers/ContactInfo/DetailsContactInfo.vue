<template>
  <div class="card border-0 card-with-shadow mb-primary">
    <div class="card-header d-flex align-items-center justify-content-between p-primary bg-transparent">

      <h5 v-show="isContactInfoActive" class="card-title text-muted m-0">
        {{ $t("contact_info") }}
      </h5>

      <h5 v-show="!isContactInfoActive" class="card-title text-muted m-0">
        {{ $t("edit_contact_info") }}
      </h5>

      <div>
        <a v-show="!isContactInfoActive" class="text-muted" href="#" @click.prevent="contactInfoClose">
          <app-icon name="x-square" stroke-width="1"/>
        </a>

        <template v-if="personId">

          <a v-show="!isContactInfoActive" class="text-muted" href="#" @click.prevent="contactInfoSync">
            <app-icon name="check-square" stroke-width="1"/>
          </a>

        </template>

        <a v-if="statusCheck && personId" v-show="isContactInfoActive" class="text-muted" href="#"
           @click.prevent="contactInfoEdit">
          <app-icon name="edit" stroke-width="1"/>
        </a>

        <!--          <a v-else v-show="isContactInfoActive" class="text-muted" href="#" @click.prevent="">-->
        <!--            <app-icon name="edit" stroke-width="1"/>-->
        <!--          </a>-->

      </div>
    </div>

    <div class="card-body">

      <template v-if="personId">

        <div v-show="isContactInfoActive">

          <div class="form-group">
            <div class="form-row">
              <label class="text-muted col-12">

                <template v-if="contactInfoData && contactInfoData.phone.length">
                  <span v-if="contactInfoData.phone.length > 1">{{ $t("phones") }}</span>
                  <span v-else>{{ $t("phone") }}</span>

                </template>

                <template v-else>
                  <span>{{ $t("no_phone_number_added") }}</span>
                </template>

              </label>

              <div v-if="contactInfoData && contactInfoData.phone" class="col-12">

                <div
                  v-for="(phoneNumber, index) in contactInfoData.phone"
                  :key="index"
                  class="d-flex justify-content-between">

                  <div>{{ phoneNumber.value }}</div>

                  <div
                    :class="{'mb-2': contactInfoData && contactInfoData.phone.length > 1 && index !== contactInfoData.phone.length - 1}">

                    <span v-if="phoneNumber.type">
                      <app-badge v-if="phoneNumber.type.class"
                        :class-name="`badge-sm badge-pill badge-${phoneNumber.type.class}`"
                        :label="phoneNumber.type.name"/>

                      <app-badge v-else
                        :class-name="`badge-sm badge-pill badge-secondary`"
                        :label="phoneNumber.type.name"/>
                    </span>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group mb-0">

            <div class="form-row">
              <label class="text-muted col-12">

                <template v-if="contactInfoData && contactInfoData.email.length">
                  <span v-if="contactInfoData.email.length > 1">{{ $t("emails") }}</span>
                  <span v-else>{{ $t("email") }}</span>
                </template>

                <template v-else>
                  <span>{{ $t("no_email_added") }}</span>
                </template>

              </label>

              <div v-if="contactInfoData && contactInfoData.email" class="col-12">
                <div v-for="(email, index) in contactInfoData.email" :key="index" class="d-flex justify-content-between">

                  <div class="text-break">{{ email.value }}</div>

                  <div :class="{ 'mb-2': contactInfoData.email.length > 1 && index !== contactInfoData.email.length - 1}">

                    <span v-if="email.type">
                      <app-badge
                        v-if="email.type.class"
                        :class-name="`badge-sm badge-pill badge-${email.type.class}`"
                        :label="email.type.name"/>

                      <app-badge v-else :class-name="`badge-sm badge-pill badge-secondary`" :label="email.type.name"/>
                    </span>

                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div v-show="!isContactInfoActive">
          <div class="form-group">
            <div class="form-row">
              <label v-if="contactInfoData && contactInfoData.phone.length > 1" class="col-12">

                {{ $t("phones") }}</label>

              <label v-else class="col-12">{{ $t("phone") }}</label>

              <div v-if="contactInfoData && contactInfoData.phone" class="col-12">
                <div v-for="(phoneNumber, index) in contactInfoData.phone" :key="index"
                     :class="{ 'mb-3': contactInfoData.phone.length > 1 && index !== contactInfoData.phone.length - 1}">

                  <div class="form-row">
                    <div class="col-12 mb-1">
                      <app-input
                        v-model="phoneNumber.value"
                        :placeholder="$t('enter_phone')"
                        type="tel-input"
                      />
                      <small v-if="errors['phone.'+index+'.value']"
                             class="text-danger">{{ errors['phone.' + index + '.value'][0] }}</small>
                    </div>

                    <div class="col-10">
                      <app-input
                        v-model="phoneNumber.type_id"
                        :list="typeList"
                        :placeholder="$t('type')"
                        list-value-field="name"
                        type="select"
                      />
                    </div>

                    <div class="col-2 d-flex justify-content-center align-items-center">
                      <a class="text-muted" href="#" @click.prevent="deleteContactInfo('phone', index)">
                        <app-icon name="trash" stroke-width="1" width="20"/>
                      </a>
                    </div>
                  </div>
                </div>
                <a class="font-size-90" href="#" @click.prevent="addMoreContactInfo('phone')">
                  {{ $t("add_more") }}
                </a>
              </div>
            </div>
          </div>

          <div class="form-group mb-0">
            <div class="form-row">

              <label v-if="contactInfoData && contactInfoData.email.length > 1" class="col-12">
                {{ $t("emails") }}
              </label>

              <label v-else class="col-12">{{ $t("email") }}</label>

              <div v-if="contactInfoData && contactInfoData.email" class="col-12">
                <div v-for="(email, index) in contactInfoData.email" :key="index"
                     :class="{ 'mb-3':  contactInfoData.email.length > 1 &&  index !== contactInfoData.email.length - 1}">

                  <div class="form-row">
                    <div class="col-12 mb-1">
                      <app-input
                        v-model="email.value"
                        :placeholder="$t('enter_email')"
                        type="text"/>

                      <small v-if="errors['email.'+index+'.value']" class="text-danger">
                        {{ generateValidationMessage(errors['email.' + index + '.value'][0], 'email') }}
                      </small>

                    </div>
                    <div class="col-10">
                      <app-input
                        v-model="email.type_id"
                        :list="typeList"
                        :placeholder="$t('type')"
                        list-value-field="name"
                        type="select"
                      />
                    </div>

                    <div class="col-2 d-flex justify-content-center align-items-center">
                      <a class="text-muted" href="#" @click.prevent="deleteContactInfo('email', index)">
                        <app-icon name="trash" stroke-width="1" width="20"/>
                      </a>
                    </div>

                  </div>
                </div>

                <a class="font-size-90" href="#" @click.prevent="addMoreContactInfo('email')">
                  {{ $t("add_more") }}
                </a>
              </div>
            </div>
          </div>
        </div>
      </template>

      <template v-else>
        <p class="text-muted">{{ $t("not_added_yet") }}</p>
      </template>

    </div>
  </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";

export default {
  name: "DetailsContactInfo",
  mixins: [FormMixin],
  props: {
    contactInfoData: {
      required: true,
    },
    personId:{
      default: null,
      required: false
    },
    contactInfoSyncUrl: {
      type: String,
      required: true
    },
    statusCheck: {
      default: true,
      required: false
    }
  },
  data() {
    return {
      isContactInfoActive: true,
      errors: {}
    };
  },
  computed: {
    typeList() {
      return this.$store.getters.getPhoneEmailType;
    },
  },
  methods: {
    generateValidationMessage(value, name) {
      let message = value.split(" ");
      message[1] = name;
      return message.join(' ');
    },
    addMoreContactInfo(type) {

      if (type === "phone") {
        this.contactInfoData.phone.push({
          phone: {
            value: "",
            type_id: "",
          },
        });
      } else {

        this.contactInfoData.email.push({
          email: {
            value: "",
            type_id: "",
          },
        });
      }
    },
    contactInfoSync() {
      let phoneFlatArray = this.contactInfoData.phone.map((data) => {
        return {
          value: data.value,
          type_id: data.type_id,
        };
      });

      let emailFlatArray = this.contactInfoData.email.map((data) => {
        return {
          value: data.value,
          type_id: data.type_id,
        };
      });

      this.submitFromFixin('post', this.contactInfoSyncUrl, {
        phone: phoneFlatArray,
        email: emailFlatArray
      })
    },

    afterError(response) {
      this.errors = response.data.errors;
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.isContactInfoActive = true;
      this.$emit("update-request");
    },
    deleteContactInfo(type, index) {
      if (type === "phone") {
        this.contactInfoData.phone.splice(index, 1);
      } else {
        this.contactInfoData.email.splice(index, 1);
      }
    },

    contactInfoClose() {
      this.isContactInfoActive = true;
    },
    contactInfoEdit() {
      this.isContactInfoActive = false;
    },

  },
  created() {
    this.$store.dispatch("getPhoneEmailType");
  },
};
</script>

