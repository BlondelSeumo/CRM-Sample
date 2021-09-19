<template>
    <app-overlay-loader v-if="preloader"/>
    <form v-else ref="form" :data-url="route('broadcast.setting-update')" class="mb-0"
          :class="{ 'loading-opacity': preloader }">

        <div class="form-group row align-items-center">
            <label for="provider" class="col-lg-3 col-xl-3 mb-lg-0">
                {{ $t("broadcast_driver") }}
            </label>

            <div class="col-lg-8 col-xl-8">
                <app-input
                    id="provider"
                    type="select"
                    v-model="formData.broadcast_driver"
                    :list="providerList"
                    :error-message="$errorMessage(errors, 'broadcast_driver')"
                />
            </div>
        </div>

        <template v-if="formData.broadcast_driver === 'pusher'">
            <div class="form-group row align-items-center">
                <label class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t("pusher_app_id") }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input type="text"
                               v-model="formData.pusher_app_id"
                               :placeholder="$t('pusher_app_id')"
                               :error-message="$errorMessage(errors, 'pusher_app_id')"/>
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t("pusher_app_key") }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input type="text"
                               v-model="formData.pusher_app_key"
                               :placeholder="$t('pusher_app_key')"
                               :error-message="$errorMessage(errors, 'pusher_app_key')"/>
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t("pusher_app_secret") }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input type="text"
                               v-model="formData.pusher_app_secret"
                               :placeholder="$t('pusher_app_secret')"
                               :error-message="$errorMessage(errors, 'pusher_app_secret')"/>
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t("pusher_app_cluster") }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input type="text"
                               v-model="formData.pusher_app_cluster"
                               :placeholder="$t('pusher_app_cluster')"
                               :error-message="$errorMessage(errors, 'pusher_app_cluster')"/>
                </div>
            </div>
        </template>
        <div class="mt-5">
            <button class="btn btn-primary" type="button" @click.prevent="submitData">
                <span v-if="loading" class="w-100">
                  <app-submit-button-loader></app-submit-button-loader>
                </span>
                <template v-else>{{ $t("save") }}</template>
            </button>

        </div>
    </form>

</template>

<script>
import {urlGenerator} from "../../../Helpers/helpers";
import {FormMixin} from "../../../../core/mixins/form/FormMixin";

export default {
    name: "BroadcastSetup",
    mixins: [FormMixin],
    props: ['props'],
    data() {
        return {
            formData: {
                broadcast_driver: ''
            },
            errors: {},
            loading: false,
            preloader: false,
            route,
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
        afterError(response) {
            this.loading = false;
            this.errors = response.data.errors;
        },
        submitData() {
            if (this.props.isMarketPlaceVersion) {
                this.$toastr.s(this.$t('this_action_is_not_allowed_for_demo'));
            } else {
                this.save(this.formData)
            }
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            this.errors = {};
        },
        afterFinalResponse() {
            this.loading = false;
        },

        getBroadcast() {
            this.preloader = true;
            this.axiosGet(route('view-broadcast-setting')).then((response) => {
                if (!_.isEmpty(response.data)) {
                    this.formData = response.data
                }
            }).finally(() => {
                this.preloader = false;
            })
        }
    },
    created() {
        this.getBroadcast();
    }
}
</script>
