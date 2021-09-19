<template>
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-sm-6 col-md-6 col-lg-8">
        <div
          class="back-image"
          :style="'background-image: url(' + urlGenerator(bannerUrl) + ')'"
        ></div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-4 pl-0">
        <div class="login-form d-flex align-items-center">
          <form
            class="sign-in-sign-up-form w-100"
            ref="form"
            data-url="admin/users/login"
          >
            <div class="text-center mb-4">
              <img :src="urlGenerator(logoUrl)" alt="" class="img-fluid logo" />
            </div>
            <div class="form-row">
              <div class="form-group col-12">
                <h6 class="text-center mb-0">{{ $t("hi", { object: $t("there") }) }}!</h6>
                <label class="text-center d-block">{{
                  $t("login_to_your_dashboard")
                }}</label>
              </div>
            </div>
              <div class="form-row" v-if="marketPlaceVersion">
                  <div class="form-group col-12">
                      <label>{{ $t("user_role") }}</label>
                      <app-input type="select"
                                 id="input-select"
                                 :placeholder="$t('user_role', '')"
                                 v-model="userRole"
                                 @change="setUserInfo"
                                 :list="userTypeList"/>
                  </div>
              </div>
            <div class="form-row">
              <div class="form-group col-12">
                <label>{{ $t("email") }}</label>
                <app-input
                  type="email"
                  :placeholder="$t('your_email')"
                  :required="true"
                  v-model="login.email"
                />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-12">
                <label>{{ $t("password") }}</label>
                <app-input
                  type="password"
                  v-on:keyup.enter="submitData"
                  :placeholder="$t('your_password')"
                  :required="true"
                  v-model="login.password"
                />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-12">
                <button
                  type="button"
                  class="btn btn-primary btn-block text-center"
                  @click="submitData"
                >
                  <span class="w-100">
                    <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                  </span>
                  <template v-if="!loading">{{ $t("login") }}</template>
                </button>
              </div>
            </div>
            <div class="form-row">
              <div class="col-6"></div>
              <div class="col-6 text-right">
                <a href="#" class="bluish-text" @click="forgetPassword">
                  <i data-feather="lock" class="pr-2" />{{ $t("forgot_password") }}?
                </a>
              </div>
            </div>
            <div class="form-group">
              <div class="col-12">
                <p class="text-center mt-5 footer-copy">
                  {{ copyRightText() }}
                </p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ThemeMixin from "../../../../core/mixins/global/ThemeMixin";
import { companyName, copyRightText, urlGenerator } from "../../../Helpers/helpers";
import { FormSubmitMixin } from "../../../Mixins/Global/FormSubmitMixin";

export default {
  name: "Login",
  props: {
    configData: {
      type: Object,
    },
    previousUrl: {
      type: String,
    },
      marketPlaceVersion:{
        default: false
      }
  },
  mixins: [FormSubmitMixin, ThemeMixin],
  data() {
    return {
      companyName,
      copyRightText,
      urlGenerator,
      login: {},
        userTypeList: [],
        userRole: null
    };
  },
  computed: {
    bannerUrl() {
      return this.configData.company_banner ?? "/images/banner.jpg";
    },
    logoUrl() {
      return this.configData.company_logo ?? "/images/logo.png";
    },
  },
  methods: {
      setUserInfo(){
          this.userTypeList.find((user)=>{
              if (user.id == this.userRole){
                  this.login.email = user.email;
                  this.login.password = user.password;
              }
          });
      },
    submitData() {
      this.save(this.login);
    },
    afterSuccess({ data }) {
      window.location.href = data;
    },
    afterError(response) {
      this.loading = false;
      this.$toastr.e(response.data.message);
    },
    forgetPassword() {
      location.replace(urlGenerator(`users/password-reset`));
    },
  },

    mounted() {
      if (this.marketPlaceVersion){
          this.userTypeList = [
              {
                  id: 1,
                  value: 'Admin',
                  email: 'admin@demo.com',
                  password: 123456
              },
              {
                  id: 2,
                  value: 'Manager',
                  email: 'manager@demo.com',
                  password: 123456
              },
              {
                  id: 3,
                  value: 'Agent',
                  email: 'agent@demo.com',
                  password: 123456
              },
              {
                  id: 4,
                  value: 'Client',
                  email: 'client@demo.com',
                  password: 123456
              },
          ]
      }
    }
};
</script>
