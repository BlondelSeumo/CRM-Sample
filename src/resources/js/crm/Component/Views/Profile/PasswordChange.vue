<template>
  <app-overlay-loader v-if="preLoader" />
  <div class="content py-primary" v-else>
    <form ref="form" :data-url="`admin/auth/users/${formData.id}/password/change`">
      <div class="form-group mb-primary">
        <div class="row">
          <div class="col-xl-3 d-flex align-items-center">
            <label
              for="input-text-old-password"
              class="text-left d-block mb-lg-2 mb-xl-0"
            >{{$t('old_password')}}</label>
          </div>
          <div class="col-xl-8">
            <app-input
              type="password"
              id="input-text-old-password"
              :placeholder="$t('old_password')"
              :required="true"
              v-model="formData.old_password"
            />
            <small class="text-danger" v-if="errors.old_password">{{errors.old_password[0]}}</small>
          </div>
        </div>
      </div>
      <div class="form-group mb-primary">
        <div class="row">
          <div class="col-xl-3 d-flex py-2">
            <label
              for="input-text-new-password"
              class="text-left d-block mb-lg-2 mb-xl-0"
            >{{$t('new_password')}}</label>
          </div>
          <div class="col-xl-8">
            <app-input
              type="password"
              id="input-text-new-password"
              :placeholder="$t('new_password')"
              :required="true"
              v-model="formData.password"
            />
            <small class="text-danger" v-if="errors.password">{{errors.password[0]}}</small>
            <div class="note note-warning p-4 mt-2">
              <p
                class="m-1"
              >The password should contain one upper case, one lower case, one special character, and numbers. It should be a minimum of 8 characters.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group mb-primary">
        <div class="row">
          <div class="col-xl-3 d-flex align-items-center">
            <label
              for="input-text-confirm-password"
              class="text-left d-block mb-lg-2 mb-xl-0"
            >{{$t('confirm_password')}}</label>
          </div>
          <div class="col-xl-8">
            <app-input
              type="password"
              id="input-text-confirm-password"
              :placeholder="$t('confirm_password')"
              :required="true"
              v-model="formData.password_confirmation"
            />
            <small
              class="text-danger"
              v-if="errors.password_confirmation"
            >{{errors.password_confirmation[0]}}</small>
          </div>
        </div>
      </div>
      <div class="form-group mt-5">
        <div class="row">
          <div class="col-12">
            <button type="button" class="btn btn-primary mr-3" @click="submitData">
              <span class="w-100">
                <app-submit-button-loader v-if="loading"></app-submit-button-loader>
              </span>
              <template v-if="!loading">{{ $t('save') }}</template>
            </button>
            <button type="button" class="btn btn-secondary" @click="cancelData">{{$t('cancel')}}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";

export default {
  name: "PasswordChange",
    props: ['props'],
  mixins: [FormMixin],
  data() {
    return {
      formData: {},
      loading: false,
      preLoader:false,
      errors: [],
    };
  },
  methods: {
    beforeSubmit() {
      this.preLoader = true;
      this.loading = true;
    },
    submitData() {
        if (this.props.isMarketPlaceVersion){
            this.$toastr.s(this.$t('this_action_is_not_allowed_for_demo'));
        }else {
            this.save(this.formData);
        }
    },

    afterError(response) {
      this.loading = false;
      this.preLoader = false;
      this.errors = response.data.errors;
    },

    afterSuccess(response) {
      this.formData = {};
      this.$toastr.s(response.data.message);
      setTimeout(() => {
        location.reload();
      }, 1000);
    },
    afterFinalResponse() {
      this.loading = false;
    },
    cancelData() {
      location.reload();
    },
  },
  computed: {
    userInfo() {
      return this.$store.getters.getUserInformation;
    },
  },
  created(){
    this.preLoader = true;
  },
  mounted() {
    this.$store.dispatch("getUserInformation");
  },

  watch: {
    userInfo: {
      handler: function (user) {
        this.preLoader = false;
        this.formData = user;
      },
      deep: true,
    },
  },
};
</script>
