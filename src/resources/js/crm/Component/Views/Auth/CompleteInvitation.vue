<template>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-8">
                <div :style="'background-image: url('+urlGenerator(configData.company_banner)+')'"
                     class="back-image">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 pl-0">
                <div class="login-form d-flex align-items-center">
                    <form ref="form" class="sign-in-sign-up-form w-100" data-url="users/confirm">
                        <div class="text-center mb-4">
                            <img
                                :src="`${configData.company_logo ? urlGenerator(configData.company_logo) : urlGenerator('/images/core.png')}`"
                                alt="" class="img-fluid logo">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <h6 class="text-center mb-0">{{ $t('sign_up') }}</h6>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label>{{ $t('first_name') }}</label>
                                <app-input v-model="userData.first_name"
                                           :error-message="$errorMessage(errors, 'first_name')"
                                           :placeholder="$t('first_name')"
                                           :required="true"
                                           type="text"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label>{{ $t('last_name') }}</label>
                                <app-input v-model="userData.last_name"
                                           :error-message="$errorMessage(errors, 'last_name')"
                                           :placeholder="$t('last_name')"
                                           :required="true"
                                           type="text"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label>{{ $t('email') }}</label>
                                <app-input v-model="formData.email"
                                           :disabled="true"
                                           :placeholder="$t('email')"
                                           type="text"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label>{{ $t('password') }}</label>
                                <app-input v-model="userData.password"
                                           :error-message="$errorMessage(errors, 'password')"
                                           :placeholder="$t('your_password')"
                                           :required="true"
                                           type="password"
                                />
                                <app-note :notes="[$t('password_hint_note')]"
                                          :show-title="false"
                                          class="mt-2"
                                          note-type="note-warning"/>
                            </div>
                            <div class="form-group col-12">
                                <label>{{ $t('confirm_password') }}</label>
                                <app-input v-model="userData.password_confirmation"
                                           :error-message="$errorMessage(errors, 'password')"
                                           :placeholder="$t('confirm_password')"
                                           :required="true"
                                           type="password"
                                />
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-12">
                                <button class="btn btn-primary btn-block text-center"
                                        type="button"
                                        @click="submitData">
                                    <span class="w-100">
                                        <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                                    </span>
                                    <template v-if="!loading">{{ $t('sign_up') }}</template>
                                </button>
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
import {companyName, copyRightText, urlGenerator} from "../../../Helpers/helpers";

export default {
    name: "CompleteInvitation",
    props: {
        configData: {},
        user: {
            required: true
        }
    },
    mixins: [FormMixin, ThemeMixin],

    data() {
        return {
            companyName, copyRightText, urlGenerator,
            loading: false,
            formData: this.user,
            userData: {
                invitation_token: this.user.invitation_token
            },
            errors: []
        }
    },
    created() {
        let url_string = window.location.href,
            url = new URL(url_string),
            params = url.searchParams.get('name');
    },

    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            this.message = '';
            this.save(this.userData);
        },

        afterError(response) {
            this.loading = false;
            this.errors = response.data.errors;
        },

        afterSuccess({data}) {
            location.replace(urlGenerator(`/`));
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
