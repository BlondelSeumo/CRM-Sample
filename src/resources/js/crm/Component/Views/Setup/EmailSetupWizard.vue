<template>
    <div class="py-5">
        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="mail" class="primary-text-color mr-2"/>
                    {{ $t('email_setup') }}
                </h5>
            </div>
            <div class="card-body">

                <div class="form-group row align-items-center">
                    <label for="emailSettingsProvider" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t("supported_mail_services") }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input
                            id="emailSettingsProvider"
                            type="select"
                            v-model="environment.provider"
                            :list="providerList"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label for="emailSettingsFromName" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t("email_sent_from_name") }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input
                            id="emailSettingsFromName"
                            type="text"
                            v-model="environment.from_name"
                            :placeholder="$t('type_email_sent_from_name')"
                            :required="true"
                        />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="emailSettingsFromEmail" class="col-lg-3 col-xl-3 mb-lg-0">
                        {{ $t("email_sent_from_email") }}
                    </label>
                    <div class="col-lg-8 col-xl-8">
                        <app-input
                            id="emailSettingsFromEmail"
                            type="email"
                            v-model="environment.from_email"
                            :placeholder="$t('type_email_sent_from_email')"
                            :required="true"
                        />
                    </div>
                </div>


                <!-- For Amazon SES -->
                <fieldset v-if="environment.provider === 'amazon_ses'">
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsHostname" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("hostname") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsHostname"
                                type="text"
                                v-model="environment.hostname"
                                :placeholder="$t('type_host_name')"
                                :required="true"
                            />
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="emailSettingsAccessKeyId" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("access_key_id") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsAccessKeyId"
                                type="text"
                                v-model="environment.access_key_id"
                                :placeholder="$t('type_access_key_id')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsSecretAccessKey" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("secret_access_key") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsSecretAccessKey"
                                type="text"
                                v-model="environment.secret_access_key"
                                :placeholder="$t('type_secret_access_key')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsApiRegion" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("region") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsApiRegion"
                                type="text"
                                v-model="environment.region"
                                :placeholder="$t('region')"
                            />
                        </div>
                    </div>
                </fieldset>

                <!-- For Mailgun -->
                <fieldset v-else-if="environment.provider === 'mailgun'">
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsDomainName" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("domain_name") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsDomainName"
                                type="text"
                                v-model="environment.domain_name"
                                :placeholder="$t('type_domain_name')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsApiKey" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("api_key") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsApiKey"
                                type="text"
                                v-model="environment.api_key"
                                :placeholder="$t('type_api_key')"
                                :required="true"
                            />
                        </div>
                    </div>
                </fieldset>

                <!-- For SMTP -->

                <fieldset v-else-if="environment.provider === 'smtp'">

                    <div class="form-group row align-items-center">
                        <label for="user_name" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("user_name") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="user_name"
                                type="text"
                                v-model="environment.smtp_user_name"
                                :placeholder="$t('user_name')"
                                :required="true"
                            />
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label for="emailSettingsSmtpHost" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("smtp_host") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsSmtpHost"
                                type="text"
                                v-model="environment.smtp_host"
                                :placeholder="$t('type_smtp_host')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsSmtpPort" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("port") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsSmtpPort"
                                type="number"
                                v-model="environment.smtp_port"
                                :placeholder="$t('type_port_number')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsEmailPassword" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("password_to_access") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsEmailPassword"
                                type="password"
                                v-model="environment.email_password"
                                :placeholder="$t('type_password_to_access')"
                                :required="true"
                            />
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label for="emailSettingsEncryptionType" class="col-lg-3 col-xl-3 mb-lg-0">
                            {{ $t("encryption_type") }}
                        </label>
                        <div class="col-lg-8 col-xl-8">
                            <app-input
                                id="emailSettingsEncryptionType"
                                type="select"
                                v-model="environment.encryption_type"
                                :placeholder="$t('type_encryption_type')"
                                :list="encryptionType"
                                :required="true"
                            />
                        </div>
                    </div>
                </fieldset>

            </div>
        </div>

        <div class="form-group mt-5">
            <app-submit-button :loading="loading"
                               @click="submitData"
                               btn-class="btn-block"
                               :label="$t('save_&_next')"/>
        </div>
    </div>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin";

export default {
    name: "EmailSetupWizard",
    mixins: [FormMixin],
    props: {
        appName: {}
    },
    data() {
        return {
            loading: false,
            errors: {},
            environment: {region: "us-east-1"},
            providerList: [
                {id: "", value: this.$t("choose_one")},
                {id: "amazon_ses", value: this.$t("amazon_ses")},
                {id: "mailgun", value: this.$t("mailgun")},
                {id: "smtp", value: this.$t("smtp")},
                {id: "sendmail", value: this.$t("sendmail")},
            ],
            encryptionType: [
                {id: "", value: this.$t("choose_one")},
                {id: "tls", value: this.$t("tls")},
                {id: "ssl", value: this.$t("ssl")},
            ],
        }
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            this.submitFromFixin('post', route('environment.email-setting-update-delivery'), this.environment)
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            window.location = route('environment.broadcast-setup');
        },
        afterFinalResponse() {
            this.loading = false;
        },
        afterError(response) {
            // this.$toastr.e(response.data.message);
            this.loading = false;
        }
    }
}
</script>
