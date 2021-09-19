<template>
  <div class="mb-primary user-profile">
    <div class="card position-relative card-with-shadow py-primary border-0">
      <div class="row align-items-center">
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">
          <div
            class="media border-right px-5 pr-xl-5 pl-xl-0 align-items-center user-header-media"
          >
            <div class="profile-pic-wrapper position-relative">
              <app-input
                v-model="profile_picture"
                :generate-file-url="false"
                :label="$t('change')"
                :wrapper-class="'circle small-wrapper mx-xl-auto'"
                name="organization_logo"
                type="custom-file-upload"
                @change="changeProfile()"
              />
            </div>

            <div class="media-body user-info-header">
              <template v-if="userInfoData.contact_type">
                <app-badge
                  :className="`badge-sm badge-pill badge-${userInfoData.contact_type.class}`"
                  :label="userInfoData.contact_type.name"
                />
              </template>

              <template v-if="componentType == 'org'">
                <div
                  v-if="
                    userInfoData.country ||
                    userInfoData.area ||
                    userInfoData.state ||
                    userInfoData.city ||
                    userInfoData.zip_code ||
                    userInfoData.address
                  "
                  class="d-flex align-items-center text-muted mt-2"
                >
                  <div>
                    <app-icon :name="'map-pin'" class="mr-2 size-20" stroke-width="1" />
                  </div>
                  <app-common-address class="font-size-90" :row-data="userInfoData" />
                </div>
              </template>

              <template v-else>
                <div
                  v-if="userInfoData['email']"
                  class="d-flex align-items-center justify-content-center justify-content-sm-start text-muted font-size-90 mt-2"
                >
                  <app-icon
                    v-if="userInfoData.email.length > 0"
                    :name="'mail'"
                    class="mr-2"
                    stroke-width="1"
                  />
                  <p class="mb-0 text-break">
                    {{ userInfoData.email.length > 0 ? userInfoData.email[0].value : "" }}
                  </p>
                </div>

                <div
                  v-if="userInfoData['phone']"
                  class="d-flex align-items-center justify-content-center justify-content-sm-start text-muted font-size-90 mt-2"
                >
                  <app-icon
                    v-if="userInfoData.phone.length > 0"
                    :name="'phone-call'"
                    class="mr-2"
                    stroke-width="1"
                  />
                  <p class="mb-0">
                    {{ userInfoData.phone.length > 0 ? userInfoData.phone[0].value : "" }}
                  </p>
                </div>
              </template>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-7">
          <div
            class="user-details px-5 px-sm-5 px-md-5 px-lg-0 px-xl-0 mt-5 mt-sm-5 mt-md-0 mt-lg-0 mt-xl-0"
          >
            <div class="row">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <div
                  class="border-right h-100 custom d-flex flex-column justify-content-around"
                >
                  <div class="media mb-4 mb-xl-5">
                    <div class="align-self-center mr-3">
                      <app-icon
                        :name="'user'"
                        class="primary-text-color"
                        stroke-width="1"
                      />
                    </div>

                    <div class="media-body font-size-90" v-if="componentType == 'org'">
                      <p class="text-muted mb-0">{{ $t("have") }}:</p>
                      <p v-if="userInfoData.linked_contact_count > 0" class="mb-0">
                        {{ userInfoData.linked_contact_count }}
                        {{ $t("contact_person") }}
                      </p>
                      <p v-else class="mb-0">{{ $t("no_persons_linked_yet") }}</p>
                    </div>

                    <div v-else class="media-body font-size-90">
                      <p class="text-muted mb-0">{{ $t("working_on") }}:</p>
                      <template v-if="userInfoData.organizations.length > 0">
                        <p class="mb-0">
                          {{ userInfoData.organizations[0].name }}
                          <span
                            v-if="userInfoData.linked_contact_count > 1"
                            class="text-muted"
                          >
                            {{ $t("and") }}
                            {{ userInfoData.linked_contact_count - 1 }} {{ $t("more") }}
                          </span>
                        </p>
                      </template>

                      <template v-else>
                        <p class="mb-0">{{ $t("no_organization_linked_yet") }}</p>
                      </template>
                    </div>
                  </div>

                  <div class="media mb-4 mb-xl-0">
                    <div class="align-self-center mr-3">
                      <app-icon
                        :name="'phone'"
                        class="primary-text-color"
                        stroke-width="1"
                      />
                    </div>
                    <div class="media-body font-size-90">
                      <p class="text-muted mb-0">{{ $t("deals") }}:</p>
                      <p class="mb-0">
                        {{ $t("closed") }}: {{ userInfoData.close_deals_count }},
                        {{ $t("open") }}: {{ userInfoData.open_deals_count }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                <div class="h-100 d-flex flex-column justify-content-around">
                  <div class="media mb-4 mb-xl-5">
                    <div class="align-self-center mr-3">
                      <app-icon
                        :name="'user'"
                        class="primary-text-color"
                        stroke-width="1"
                      />
                    </div>
                    <div class="media-body font-size-90">
                      <p class="text-muted mb-0">{{ $t("owner") }}:</p>
                      <p v-if="userInfoData.owner" class="mb-0">
                        {{ userInfoData.owner.full_name }}
                      </p>
                        <p class="m-0 font-size-90 text-secondary" v-else>
                        {{$t("user_deleted")}}
                        </p>
                    </div>
                  </div>
                  <div class="media mb-4 mb-xl-0">
                    <div class="align-self-center mr-3">
                      <app-icon
                        :name="'calendar'"
                        class="primary-text-color"
                        stroke-width="1"
                      />
                    </div>
                    <div class="media-body font-size-90">
                      <p class="text-muted mb-0">{{ $t("created") }}:</p>
                      <p class="mb-0">{{ formatDateToLocal(userInfoData.created_at) }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
import { formatDateToLocal, urlGenerator } from "@app/Helpers/helpers";

export default {
  name: "PersonOrgDetailsUserInfo",
  props: {
    userInfoData: {
      type: Object,
      required: true,
    },
    userInfoSyncUrl: {
      type: String,
      required: true,
    },
    componentType: {
      type: String,
      required: true,
    },
  },
  mixins: [FormMixin],
  data() {
    return {
      profile_picture: "",
      formatDateToLocal,
    };
  },
  methods: {
    changeProfile() {
      let formData = new FormData();
      formData.append("profile_picture", this.profile_picture);
      this.submitFromFixin("post", this.userInfoSyncUrl, formData);
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$emit("update-request");
    },
  },
  mounted() {
    this.profile_picture = this.userInfoData.profile_picture
      ? urlGenerator(this.userInfoData.profile_picture.path)
      : urlGenerator(`/images/${this.componentType}.png`);
  },
};
</script>
