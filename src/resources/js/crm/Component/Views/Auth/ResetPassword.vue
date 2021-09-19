<template>
  <div class="container-fluid p-0">
    <div class="row">
      <div class="col-sm-6 col-md-6 col-lg-8">
        <div class="back-image"
             :style="'background-image: url('+urlGenerator(configData.company_banner)+')'">
        </div>
      </div>
      <div class="col-sm-6 col-md-6 col-lg-4 pl-0">
        <div class="login-form d-flex align-items-center">
          <form class="sign-in-sign-up-form w-100" ref="form" data-url="users/reset-password">
            <div class="text-center mb-4">
              <img :src="`${configData.company_logo ? urlGenerator(configData.company_logo) : urlGenerator('/images/core.png')}`" alt=""
                   class="img-fluid logo">
            </div>
            <div class="form-row">
              <div class="form-group col-12">
                <h6 class="text-center mb-0">{{ $t('hi', {object: $t('there')}) }}!</h6>
                <label class="text-center d-block">{{ $t('login_to_your_dashboard') }}</label>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-12">
                <label>{{ $t('email') }}</label>
                <app-input type="email"
                           :placeholder="$t('your_email')"
                           :required="true"
                           v-model="user.email"
                />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-12">
                <label>{{ $t('password') }}</label>
                <app-input type="password"
                           :placeholder="$t('your_password')"
                           :required="true"
                           v-model="formData.password"/>
                <small class="text-danger" v-if="errors.password">{{errors.password[0]}}</small>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-12">
                <label>{{ $t('confirm_password') }}</label>
                <app-input type="password"
                           :placeholder="$t('confirm_password')"
                           :required="true"
                           v-model="formData.password_confirmation"/>
                <small class="text-danger" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</small>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-12">
                <button type="button"
                        class="btn btn-primary btn-block text-center"
                        @click="submitData">
                                    <span class="w-100">
                                        <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                                    </span>
                  <template v-if="!loading">{{ $t('login') }}</template>
                </button>
              </div>
            </div>
            <div class="form-row">
              <div class="col-6">
                <a :href="urlGenerator(`/`)" class="bluish-text">
                  <i data-feather="home" class="pr-2"/>{{ $t('back_to', {destination: $t('home')}) }}
                </a>
              </div>
              <div class="col-6 text-right">
                <a href="#" class="bluish-text" @click="forgetPassword">
                  <i data-feather="lock" class="pr-2"/>{{ $t('forgot_password') }}?
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
import ThemeMixin from '../../../../core/mixins/global/ThemeMixin';
import {FormMixin} from '../../../../core/mixins/form/FormMixin';
import {copyRightText, urlGenerator} from "../../../Helpers/helpers";

export default {
  name: "ResetPassword",
  props: ['user', 'token', 'configData'],
  mixins: [FormMixin, ThemeMixin],
  data() {
    return {
      copyRightText, urlGenerator,
      loading: false,
      formData: {},
      errors: []
    }
  },

  methods: {
    beforeSubmit() {
      this.loading = true;
    },
    submitData() {
      let data = this.formData;
      data.email = this.user.email
      data.token = this.token
      this.save(data);
    },

    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
    },

    afterSuccess({data}) {
      location.replace(urlGenerator(`admin/users/login`));
    },
    afterFinalResponse() {
      this.loading = false;
    },
    forgetPassword() {
      location.replace(urlGenerator(`users/password-reset`));
    }
  }
}
</script>