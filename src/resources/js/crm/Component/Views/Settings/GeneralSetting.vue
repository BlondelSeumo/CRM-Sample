<template>
    <div>
        <app-overlay-loader v-if="preloader"/>
        <form ref="form" v-else :data-url="route('core.settings.index')" enctype="multipart/form-data">
            <!-- Company Info -->
            <fieldset class="form-group mb-5">
                <div class="row">
                    <legend class="col-12 col-form-label text-primary pt-0 mb-3">{{ $t('company_info') }}</legend>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ $t('company_name') }}</label>
                            <div class="col-sm-9">
                                <app-input type="text"
                                           v-model="appSettings.company_name"
                                           :placeholder="$t('type_your_company_name')"
                                           :required="true"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label h-fit-content">
                                {{ $t('company_logo') }}<br>
                                <small class="text-muted font-italic">{{ $t('recommended_company_logo_size') }}</small>
                            </label>
                            <div class="col-sm-9">
                                <app-input type="custom-file-upload"
                                           v-model="appSettings.company_logo"
                                           :generate-file-url="false"
                                           @change="companyLogo = true"
                                           :label="$t('change_logo')"/>
                                <span class="text-danger" v-if="errors.company_logo">{{ errors.company_logo[0] }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label h-fit-content">
                                {{ $t('company_icon') }}<br>
                                <small class="text-muted font-italic">{{ $t('recommended_company_icon_size') }}</small>
                            </label>
                            <div class="col-sm-9">
                                <app-input type="custom-file-upload"
                                           v-model="appSettings.company_icon"
                                           :generate-file-url="false"
                                           @change="companyIcon = true"
                                           :label="$t('change_icon')"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label h-fit-content">
                                {{ $t('company_banner') }}<br>
                                <small class="text-muted font-italic">{{
                                        $t('recommended_company_banner_size')
                                    }}</small>
                            </label>
                            <div class="col-sm-9">
                                <app-input type="custom-file-upload"
                                           v-model="appSettings.company_banner"
                                           :generate-file-url="false"
                                           @change="companyBanner = true"
                                           :label="$t('change_banner')"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('language') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="select"
                                           v-model="appSettings.language"
                                           :list="languageData"
                                           :required="true"/>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Date & Time Setting -->
            <fieldset class="form-group mb-5">
                <div class="row">
                    <legend class="col-12 col-form-label text-primary pt-0 mb-3">{{ $t('date_and_time_setting') }}
                    </legend>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('date_format') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="select"
                                           v-model="appSettings.date_format"
                                           :list="dateFormats"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('time_format') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="radio-buttons"
                                           v-model="appSettings.time_format"
                                           :list="timeFormats"
                                />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('time_zone') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="select"
                                           v-model="appSettings.time_zone"
                                           :list="timeZones"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <!-- Currency Settings -->
            <fieldset class="form-group mb-5">
                <div class="row">
                    <legend class="col-12 col-form-label text-primary pt-0 mb-3">{{ $t('currency_setting') }}</legend>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('currency_symbol') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="text"
                                           v-model="appSettings.currency_symbol"
                                           :required="true"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('decimal_separator') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="radio-buttons"
                                           v-model="appSettings.decimal_separator"
                                           @input="changeValue('decimal_separator')"
                                           :list="decimalSeparators"
                                           :required="true"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('thousand_separator') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="radio-buttons"
                                           v-model="appSettings.thousand_separator"
                                           @input="changeValue('thousand_separator')"
                                           :list="thousandSeparators"
                                           :required="true"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('number_of_decimal') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="radio-buttons"
                                           v-model="appSettings.number_of_decimal"
                                           :list="numberOfDecimals"
                                           :required="true"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">
                                {{ $t('currency_position') }}
                            </label>
                            <div class="col-sm-9">
                                <app-input type="radio-buttons"
                                           v-model="appSettings.currency_position"
                                           :list="currencyPositions"
                                           :required="true"/>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div>
                <button class="btn btn-primary mr-2" @click.prevent="submit">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                    <template v-if="!loading">{{ $t('save') }}</template>
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin.js";
import {formDataAssigner, urlGenerator} from "../../../Helpers/helpers";

export default {
    name: "GeneralSetting",
    mixins: [FormMixin],
    data() {
        return {
            route,
            preloader: false,
            companyLogo: false,
            companyIcon: false,
            companyBanner: false,
            appSettings: {},
            dateFormats: [],
            timeFormats: [],
            timeZones: [],
            decimalSeparators: [],
            numberOfDecimals: [],
            thousandSeparators: [],
            currencyPositions: [],
            imageUrl: '',
            errors: [],
            loading: false,
        }
    },
    computed: {
        languageData() {
            return this.$store.getters.getLocaleOptions.map(item => {
                return {
                    id: item.key,
                    value: item.title
                }
            })
        }
    },
    methods: {
        changeValue(type) {
            if (type === 'thousand_separator') {
                if (this.appSettings.thousand_separator === ',') {
                    this.appSettings.decimal_separator = '.'
                } else if (this.appSettings.thousand_separator === '.') {
                    this.appSettings.decimal_separator = ','
                }
            } else {
                this.appSettings.thousand_separator = this.appSettings.decimal_separator === ',' ? '.' : ','
            }
        },
        getData() {
            this.preloader = true
            this.axiosGet(route('core.settings.index')).then(response => {
                this.appSettings = response.data;
                this.appSettings.company_logo = urlGenerator(this.appSettings.company_logo);
                this.appSettings.company_icon = urlGenerator(this.appSettings.company_icon);
                this.appSettings.company_banner = urlGenerator(this.appSettings.company_banner);

            }).catch(({error}) => {
            }).finally(() => {
                this.getGeneralSettings();
            });
        },
        getGeneralSettings() {
            this.axiosGet(route('settings.general-settings')).then(response => {
                this.currencyPositions = response.data.currency_position;
                this.dateFormats = response.data.date_format;
                this.decimalSeparators = response.data.decimal_separator;
                this.numberOfDecimals = response.data.number_of_decimal;
                this.thousandSeparators = response.data.thousand_separator;
                this.timeFormats = response.data.time_format;
                this.timeZones = response.data.time_zones;
            }).catch(({response}) => {
            }).finally(() => {
                this.preloader = false;
            });
        },
        beforeSubmit() {
            this.loading = true;
            this.preloader = true;
        },
        submit() {
            if (this.companyLogo === false) {
                delete this.appSettings.company_logo;
            }
            if (this.companyIcon === false) {
                delete this.appSettings.company_icon;
            }
            if (this.companyBanner === false) {
                delete this.appSettings.company_banner;
            }
            let formData = formDataAssigner(new FormData, this.appSettings);
            this.save(formData);
        },
        afterSubmit() {

        },
        afterError(response) {
            this.errors = response.data.errors;
        },
        afterSuccess(res) {
            this.$toastr.s(res.data.message);
            location.reload();
        },
        afterFinalResponse() {
            this.loading = false;
            this.preloader = false;
        },
    },
    created() {
        this.getData();
        // this.getGeneralSettings();
    },
}
</script>
