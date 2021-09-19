<template>
  <app-overlay-loader v-if="preLoader" />
    <div class="content py-primary" v-else>
        <form ref="form" data-url='admin/auth/users/change-settings'>
            <div class="form-group mb-primary">
                <div class="row">
                    <div class="col-xl-3 d-flex align-items-center">
                        <label for="input-text-first-name"
                               class="text-left d-block mb-lg-2 mb-xl-0">{{$t('first_name')}}</label>
                    </div>
                    <div class="col-xl-8">
                        <app-input type="text"
                                   :disabled="clientRoleAccess"
                                   id="input-text-first-name"
                                   :placeholder="$t('first_name')"
                                   v-model="userProfileInfo.first_name"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-primary">
                <div class="row">
                    <div class="col-xl-3 d-flex align-items-center">
                        <label for="input-text-last-name"
                               class="text-left d-block mb-lg-2 mb-xl-0">{{$t('last_name')}}</label>
                    </div>
                    <div class="col-xl-8">
                        <app-input type="text"
                                   :disabled="clientRoleAccess"
                                   id="input-text-last-name"
                                   :placeholder="$t('last_name')"
                                   v-model="userProfileInfo.last_name"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-primary">
                <div class="row">
                    <div class="col-xl-3 d-flex align-items-center">
                        <label for="input-text-email"
                               class="text-left d-block mb-lg-2 mb-xl-0">{{$t('enter_email')}}</label>
                    </div>
                    <div class="col-xl-8" v-if="clientRoleAccess">
                        <app-input type="select"
                                   :disabled="props.isMarketPlaceVersion ? true : false"
                                   id="input-select-email"
                                   :placeholder="$t('email', '')"
                                   v-model="userProfileInfo.email"
                                   :list="personEmail"/>
                    </div>
                    <div class="col-xl-8" v-else>
                        <app-input type="email"
                                   :disabled="props.isMarketPlaceVersion ? true : false"
                                   id="input-text-email"
                                   :placeholder="$t('email', '')"
                                   v-model="userProfileInfo.email"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-primary">
                <div class="row">
                    <div class="col-xl-3 d-flex align-items-center">
                        <label
                            class="text-left d-block mb-lg-2 mb-xl-0">{{$t('gender')}}</label>
                    </div>
                    <div class="col-xl-8" v-if="userProfileInfo.profile">
                        <app-input type="radio"
                                   :list="[{id:'male',
                                    value: $t('male')},
                                    {id:'female',
                                    value:  $t('female')}]"
                                   v-model="userProfileInfo.profile.gender"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-primary">
                <div class="row">
                    <div class="col-xl-3 d-flex align-items-center">
                        <label for="input-text-contact"
                               class="text-left d-block mb-lg-2 mb-xl-0">{{$t('contact_number')}}
                        </label>
                    </div>
                    <div class="col-xl-8" v-if="userProfileInfo.profile">
                        <app-input type="tel-input"
                                   id="input-text-contact"
                                   :placeholder="$t('enter_contact_number')"
                                   v-model="userProfileInfo.profile.contact"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-primary">
                <div class="row">
                    <div class="col-xl-3 d-flex align-items-center">
                        <label for="input-text-address"
                               class="text-left d-block mb-lg-2 mb-xl-0">{{$t('address')}}</label>
                    </div>
                    <div class="col-xl-8" v-if="userProfileInfo.profile">
                        <app-input type="text"
                                   id="input-text-address"
                                   :placeholder="$t('enter_address')"
                                   v-model="userProfileInfo.profile.address"/>
                    </div>
                </div>
            </div>
            <div class="form-group mb-primary">
                <div class="row">
                    <div class="col-xl-3 d-flex align-items-center">
                        <label class="text-left d-block mb-lg-2 mb-xl-0">
                            {{$t('date_of_birth')}}
                        </label>
                    </div>
                    <div class="col-xl-8" v-if="userProfileInfo.profile">
                        <app-input type="date"
                                   v-model="userProfileInfo.profile.date_of_birth"
                                   :placeholder="$t('enter_date_of_birth')"
                                  />
                    </div>
                </div>
            </div>
            <div class="form-group mt-5">
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary mr-3"
                                @click="submitData">
                            <span class="w-100">
                                <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                            </span>
                            <template v-if="!loading">{{ $t('save') }}</template>
                        </button>
                        <button type="button" class="btn btn-secondary" @click="cancelData"> {{$t('cancel')}}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>

    import {FormMixin} from "@core/mixins/form/FormMixin.js";
    import HelperMixin from "@app/Mixins/Global/HelperMixin";

    export default {
        name: "PersonalInfo",
        props: ['props'],
        mixins: [FormMixin,HelperMixin],
        data(){
            return{
                userProfileInfo:{},
                loading: false,
                preLoader:false,
                personEmail: []
            }
        },

        methods:{
            beforeSubmit(){
                this.preLoader = true;
                this.loading = true;
            },
            submitData() {
                let profile = this.userProfileInfo;
                profile.gender = this.userProfileInfo.profile.gender;
                profile.contact = this.userProfileInfo.profile.contact;
                profile.address = this.userProfileInfo.profile.address;
                profile.date_of_birth = this.userProfileInfo.profile.date_of_birth ?
                    moment(this.userProfileInfo.profile.date_of_birth).format('YYYY-MM-DD') : '';
                this.save(profile);
            },

            cancelData(){
              location.reload();
            },

            afterError(response) {
                this.loading = false;
                this.$toastr.e(response.data.message);
            },
            afterSuccess(response) {
                this.$toastr.s(response.data.message);
            },

            afterFinalResponse() {
                this.loading = false;
                this.preLoader = true;
                this.scrollToTop(false);
                 setTimeout(() => location.reload())
            },
            getLeadUserInfo(){
                this.axiosGet(
                    route('lead.user_info')
                ).then((res) => {
                    this.personEmail = res.data.email.map(item => {
                        return {
                            id: item.value,
                            value: item.value
                        }
                    })
                });
            }
        },
        computed: {
            clientRoleAccess(){
                return (!this.$can('manage_public_access') && this.$can('client_access'));
            },
            userInfo() {
                return this.$store.getters.getUserInformation
            }
        },

      created(){
        this.preLoader = true;
      },

        watch: {
            userInfo: {
                handler: function (user) {
                  this.preLoader = false;
                    this.userProfileInfo = {
                        ...user,
                        profile: {
                            ...user.profile,
                            date_of_birth: user.profile ? new Date(user.profile.date_of_birth) : ''
                        }
                    };
                },
                deep: true
            }
        },

        mounted(){
            this.$store.dispatch('getUserInformation');
            if (this.clientRoleAccess){
                this.getLeadUserInfo();
            }
        }

    }
</script>

