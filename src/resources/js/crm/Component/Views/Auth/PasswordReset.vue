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
          <form class="sign-in-sign-up-form w-100" ref="form" data-url="users/password-reset" @submit.prevent>
            <div class="text-center mb-4">
              <img :src="`${configData.company_logo ? urlGenerator(configData.company_logo) : urlGenerator('/images/core.png')}`" alt=""
                   class="img-fluid logo">
            </div>
            <div class="form-row">
              <div class="form-group col-12">
                <label class="text-center d-block">{{ $t('reset_password') }}</label>
              </div>
            </div>
            <template v-if="!successMessage">
              <div class="form-row">
                <div class="form-group col-12">
                  <label>{{ $t('email') }}</label>
                  <app-input type="email"
                             v-on:keyup.enter="submitData"
                             :placeholder="$t('your_email')"
                             :required="true"
                             v-model="formData.email"
                  />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-12">
                  <button type="button"
                          class="btn btn-primary btn-block text-center"
                          @click.prevent="submitData">
                                    <span class="w-100">
                                        <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                                    </span>
                    <template v-if="!loading">{{ $t('request') }}</template>
                  </button>
                </div>
              </div>
              <div class="form-row">
                <div class="col-6">
                  <a :href="urlGenerator(`/`)" class="bluish-text" @click="backLogin">
                    <i data-feather="home" class="pr-2"/>{{ $t('back_to_login', {destination: $t('login')}) }}
                  </a>
                </div>
              </div>
            </template>

            <template class="mb-0" v-else>
              <div class="form-group text-justify">
                <p class="reset-pass-message">{{ successMessage }}</p>
              </div>

              <div class="form-row">
                <div class="form-group col-12">
                  <button type="button"
                          class="btn btn-primary btn-block text-center"
                          @click="backLogin">
                    {{ $t('thank_you') }}
                  </button>
                </div>
              </div>
            </template>

            <div class="form-group">
              <div class="col-12">
                <p v-bind:class="{
                'mt-3': successMessage != '',
                'mt-5': successMessage == '',
              }" class="text-center footer-copy">
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
  name: "PasswordReset",
  props: ['configData'],
  mixins: [FormMixin, ThemeMixin],

  data() {
    return {
      copyRightText, urlGenerator,
      loading: false,
      successMessage: '',
      formData: {},
    }
  },

  methods: {
    beforeSubmit() {
      this.loading = true;
    },
    submitData() {
      this.successMessage = '';
      this.save(this.formData);
    },

    afterError(response) {
        this.$toastr.e(response.data.message);
        this.loading = false;
    },

    afterSuccess(response) {
        this.successMessage = response.data.message;
    },
    afterFinalResponse() {
      this.loading = false;
    },
    backLogin() {
      location.replace(urlGenerator(`/`));
    }
  }
}
</script>
