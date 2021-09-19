<template>
    <div class="py-5">
        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="user" class="primary-text-color mr-2"/>
                    {{ $t('admin_login_details') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-12">
                        <app-note :title="$t('password_requirements')"
                                  :notes="[$t('password_requirements_message')]"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('name') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.full_name"
                                   :placeholder="$t('name')"
                                   :error-message="$errorMessage(errors, 'full_name')"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('email') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="text"
                                   v-model="environment.email"
                                   :placeholder="$t('email')"
                                   :error-message="$errorMessage(errors, 'email')"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 d-flex align-items-center">
                        {{ $t('password') }}
                    </label>
                    <div class="col-md-9">
                        <app-input type="password"
                                   v-model="environment.password"
                                   :placeholder="$t('password')"
                                   :error-message="$errorMessage(errors, 'password')"/>
                    </div>
                </div>
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
import {urlGenerator} from "../../../Helpers/AxiosHelper";

export default {
    name: "AdminWizard",
    mixins: [FormMixin],
    props: {
        appName: {}
    },
    data() {
        return {
            environment: {
                full_name: '',
                email: '',
                password: '',
            },
            errors: {},
            loading: false
        }
    },
    methods: {
        submitData() {
            this.loading = true;
            const {first_name, last_name} = this.names;
            const formData = {...this.environment, first_name, last_name}
            this.submitFromFixin('post', 'setup/install', formData)
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            this.loading = false;
           // window.location = urlGenerator('/admin/users/login')
            window.location = urlGenerator('/setup/email-setup')
        },
        afterFinalResponse(){
            this.loading = false;
        },

        afterError(response) {
            this.errors = response.data.errors;
            this.loading = false;
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
