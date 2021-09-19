<template>
    <div class="py-5">
        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>
        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <app-icon name="database" class="primary-text-color mr-2"/>
                    {{ $t('database_configuration') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('db_connection') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="select"
                                   v-model="environment.database_connection"
                                   :list="[
                               {id: 'mysql', value: $t('mysql')},
                               {id: 'pgsql', value: $t('pgsql')},
                               {id: 'sqlsrv', value: $t('sqlsrv')},
                            ]"
                                   :placeholder="$t('app_environment')"
                                   :error-message="$errorMessage(errors, 'app_environment')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_hostname') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.database_hostname"
                                   :placeholder="$t('database_hostname')"
                                   :error-message="$errorMessage(errors, 'database_hostname')"
                                   :required="true"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_port') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.database_port"
                                   :placeholder="$t('database_port')"
                                   :error-message="$errorMessage(errors, 'database_port')"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_name') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.database_name"
                                   :placeholder="$t('database_name')"
                                   :error-message="$errorMessage(errors, 'database_name')"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_username') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.database_username"
                                   :placeholder="$t('database_username')"
                                   :error-message="$errorMessage(errors, 'database_username')"
                                   autocomplete="off"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('database_password') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="password"
                                   v-model="environment.database_password"
                                   :placeholder="$t('database_password')"
                                   :error-message="$errorMessage(errors, 'database_password')"
                                   autocomplete="off"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="key" class="primary-text-color mr-2"/>
                    {{ $t('purchase_code') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('code') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.code"
                                   :placeholder="$t('code')"
                                   :error-message="$errorMessage(errors, 'code')"/>
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group mt-5">
            <app-submit-button :loading="loading"
                               @click="submitData"
                               btn-class="btn-block"
                               :label="$t('save_&_next')"
            />
        </div>
    </div>
</template>

<script>
    import {FormMixin} from "../../../../core/mixins/form/FormMixin";
    import {urlGenerator} from "../../../Helpers/AxiosHelper";
    import {optional} from "../../../Helpers/helpers";

    export default {
        name: "Environment",
        mixins: [FormMixin],
        props: {
            appName: {}
        },
        data() {
            return {
                environment: {
                    app_name: this.appName,
                    environment: 'production',
                    app_debug: 'false',
                    app_url: window.localStorage.getItem('base_url') ?? window.origin,
                    cache_driver: 'file',
                    queue_connection: 'sync',
                    session_driver: 'file',
                    database_connection: 'mysql',
                    database_hostname: 'localhost',
                    database_port: '3306',
                    code: ''
                },
                errors: {},
                loading: false
            }
        },
        methods: {
            submitData() {
                this.loading = true;
                this.submitFromFixin('post', 'setup/environment', this.environment)
            },

            afterSuccess(response) {
                this.$toastr.s('', response.data.message);
                window.location.href = urlGenerator('setup/admin-info');
            },

            afterError(response) {
                this.message = '';
                this.loading = false;
                this.errors = optional(response.data, 'errors') || {};
                if (response.status != 422)
                    this.$toastr.e(response.data.message || response.statusText)
            }
        },
        computed: {
            names() {
                const full_name_spited = this.environment.full_name.split(' ').filter(name => name);
                if (full_name_spited.length) {
                    if (full_name_spited.length === 2) {
                        return {
                            first_name: full_name_spited[0],
                            last_name: full_name_spited[1]
                        }
                    }else if (full_name_spited.length === 1) {
                        return {
                            first_name: full_name_spited[0],
                            last_name: ''
                        }
                    }else if (full_name_spited.length === 3) {
                        return {
                            first_name: `${full_name_spited[0]} ${full_name_spited[1]}`,
                            last_name: full_name_spited[2]
                        }
                    }else {
                        return {
                            first_name: full_name_spited[0],
                            last_name: full_name_spited.slice(1, full_name_spited.length).join(' ')
                        }
                    }
                }
                return {
                    first_name: '',
                    last_name: ''
                }
            }
        }

    }
</script>

<style scoped>

</style>
