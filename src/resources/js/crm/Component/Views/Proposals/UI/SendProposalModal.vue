<template>
    <app-modal modal-id="send-proposal-modal" modal-size="default" :modal-scroll="false" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('send_proposal') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <form ref="form" :data-url="submitUrl" v-if="dataLoaded">

                <template>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="great-text">{{$t('great')}}</h4>
                            </div>
                            <div class="col-sm-12">
                                <h5>{{$t('you_are_going_to_send_a_proposal')}}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>{{$t('deal_name')}}</label>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3">
                                  <app-input type="search-select"
                                             list-value-field='title'
                                             :list="dealList"
                                             v-model="formData.deal_id"/>
                                </div>

                              <template v-if="deal">

                                <template v-if="deal.contact_person.length > 0">
                                  <template v-if="!checkContactPersonEmail">
                                    <app-note :notes='noteMessage' :show-title="false" content-type="html"
                                              padding-class="p-2" title="'send'"/>
                                  </template>
                                </template>
                                <template v-else>
                                  <app-note :notes="[$t('the_deal_has_no_contact_person_with_email')]" :show-title="false" padding-class="p-2"
                                            title="'send'"/>
                                </template>

                              </template>

                              <template v-else>
                                <app-note :notes="[$t('no_deal','There is no deal')]" :show-title="false" content-type="html"
                                          padding-class="p-2" title="'send'"/>
                              </template>

                            </div>
                        </div>
                    </div>

                    <div class="form-group" v-if="emailList.length > 1">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>{{$t('choose_an_email')}}</label>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-3">
                                    <app-input type="select"
                                               list-value-field='value'
                                               :placeholder="$t('choose_an_email')"
                                               :list="emailList"
                                               :required="true"
                                               v-model="formData.email"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </form>
            <app-overlay-loader v-else />
        </template>
        <template slot="footer" v-if="dataLoaded">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
          <button :disabled="!deal || !deal.contact_person.length || !checkContactPersonEmail ? true : false"
                  type="button" class="btn btn-primary" @click.prevent="confirm">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
            <template v-if="!loading">{{ $t('confirm') }}</template>
          </button>
        </template>

    </app-modal>
</template>
<script>
    import {FormMixin} from "@core/mixins/form/FormMixin";
    import { collect } from "@app/Helpers/Collection";
    import {urlGenerator} from "@app/Helpers/helpers";
    import moment from "moment";

    export default {
        name: "SendProposalModal",
        mixins: [FormMixin],
        props:['templateData', 'tableId', 'selectedDeal'],
        data(){
            return{
                urlGenerator,
                loading: false,
                deal: this.selectedDeal,
                formData:{
                    deal_id: null
                },
                sentStatusId: null,
                dataLoaded: false,
                dealList:[],
                emailList:[],
                saveTemplateData: [],
                tableIdReLoad: "send-proposal-modal"
            }
        },
        watch:{
            "formData.deal_id":{
                handler: function (dealId) {
                    if (this.dealList.length){
                        this.deal = this.dealList.find((v) => {
                            if (v.id == dealId){
                                return v;
                            } else {
                                return false
                            }
                        });
                    }
                    if (this.deal != undefined){
                        this.emailList = this.deal.contact_person.length ?
                            collect(this.deal.contact_person).first().email.map((item)=>{
                                return {
                                    id: item.value,
                                    value: item.value
                                }
                            })
                            : [];

                        if (this.emailList.length == 1){
                            this.formData.email = this.emailList[0].value;
                        }
                    }
                },
            },
        },
        methods:{
            beforeSubmit() {
                this.loading = true;
            },
            confirm(){
                this.formData.custom_content = this.formData.content;
                this.formData.status_id = this.sentStatusId;
                this.formData.sent_at = moment().format('YYYY-MM-DD H:m:s');

                this.submitFromFixin(
                    'post',
                    this.submitUrl,
                    this.formData
                );
                if (this.templateData){
                    this.saveTemplate();
                }
            },

            saveTemplate(){
                this.submitFromFixin(
                    'post',
                    route('templates.store'),
                    this.templateData
                );
            },
            getOwner(){
                this.formData.owner_id = null;
            },
            afterError(response){
                if (response){
                    if (response.data.errors){
                        this.$toastr.e(response.data.message);
                    }
                }
            },
            afterSuccess(response) {
                this.$toastr.s(response.data.message);
                window.location.replace(route('proposals.lists'));
            },
            afterFinalResponse() {
                this.loading = false;
                this.closeModal();
            },
            afterSuccessFromGetEditData(response) {
                this.formData = response.data;
            },
            closeModal(value){
                this.$emit('close-modal', value);
            },
            getStatus(){
                this.axiosGet(
                    route(`statuses.index`, {_query: { name: "status_sent", type: "proposal"}}))
                    .then((res) => {
                        this.sentStatusId = collect(res.data).first().id;
                    });
            },
            getDeals(){
                this.axiosGet(route('deals.index', {_query: {all: true}})).then(response =>{
                    this.dealList = response.data;
                    this.formData.deal_id = this.formData.deal_id ? this.formData.deal_id : this.dealList[0].id;
                    this.dataLoaded = true;
                })
            },

        },
        mounted(){
            this.getStatus();
            if (!this.formData.owner_id) {
                this.getOwner();
            }
            this.getDeals();
        },
        computed: {
          checkContactPersonEmail(){
            if (this.deal){
              let hasEmail = false;
              this.deal.contact_person.map((person)=>{
                if (Object.values(person.email).length > 0){
                  return hasEmail = true;
                }
              });
              return hasEmail;
            }
          },
            noteMessage() {
                let url = route('persons.edit', {id: this.deal.contact_person[0].id});
                return `
                    ${this.$t("the_contact")}
                    <a class="alert-link" href="${url}">
                        ${this.deal.contact_person[0].name}
                    </a>
                    ${this.$t("belongs_to_the_deal_has_no_email")}`;
            },
            submitUrl() {
                if (this.templateData){
                    this.formData.subject = this.templateData.subject;
                    this.formData.content = this.templateData.custom_content;
                    return route('proposals.store')
                } else {
                    return route('proposals.send')
                }
            },

        },
    }
</script>
<style>
  .great-text{
    color: blue;
  }
</style>
