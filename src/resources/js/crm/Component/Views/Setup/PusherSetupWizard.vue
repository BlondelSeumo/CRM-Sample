<template>
    <div class="py-5">
        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="user" class="primary-text-color mr-2"/>
                    {{ $t('broadcasting_configuration') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('broadcast_driver') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            id="provider"
                            type="select"
                            v-model="environment.broadcast_driver"
                            :list="providerList"
                            :required="true"
                        />
                    </div>
                </div>
                <fieldset v-if="environment.broadcast_driver === 'pusher'">
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('pusher_app_id') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.pusher_app_id"
                                   :placeholder="$t('pusher_app_id')"
                                   :error-message="$errorMessage(errors, 'pusher_app_id')"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('pusher_app_key') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.pusher_app_key"
                                   :placeholder="$t('pusher_app_key')"
                                   :error-message="$errorMessage(errors, 'pusher_app_key')"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('pusher_app_secret') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.pusher_app_secret"
                                   :placeholder="$t('pusher_app_secret')"
                                   :error-message="$errorMessage(errors, 'pusher_app_secret')"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('pusher_app_cluster') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.pusher_app_cluster"
                                   :placeholder="$t('pusher_app_cluster')"
                                   :error-message="$errorMessage(errors, 'pusher_app_cluster')"/>
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
import {urlGenerator} from "../../../Helpers/helpers";

export default {
    name: "PusherSetupWizard",
    mixins: [FormMixin],
    props: {
        appName: {}
    },
    data() {
        return {
            environment: {},
            errors: {},
            loading: false,
            providerList: [
                {id: "", value: this.$t("choose_one")},
                {id: "pusher", value: this.$t("pusher")},
                {id: "ajax", value: this.$t("ajax")},
            ]
        }
    },
    methods: {
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            this.submitFromFixin('post', route('environment.broadcast-setting-update'), this.environment)
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            window.location = urlGenerator('/admin/users/login')
        },
        afterFinalResponse() {
            this.loading = false;
        },
        afterError(response) {
            this.loading = false;
        }
    }
}
</script>

