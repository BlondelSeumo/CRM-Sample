<template>

  <app-modal
    modal-alignment="top"
    modal-id="send-proposal"
    modal-size="default"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title">{{ $t("send_proposal") }}</h5>
      <button
        aria-label="Close"
        class="close outline-none"
        data-dismiss="modal"
        type="button"
      >
            <span>
              <app-icon :name="'x'"/>
            </span>
      </button>
    </template>
    <template slot="body">
      <template v-if="dealData.contact_person.length > 0 && dealData.contact_person[0].email[0]">
        <h5 class="primary-text-color mb-2">
          {{ $t("great_exclamation") }}
        </h5>
        <h6 class="text-muted mb-4">
          {{ $t("you_are_going_to_send_a_proposal") }}
        </h6>
        <div class="form-group">
          <app-input
            v-model="proposalSendingType"
            :custom-radio-type="'d-block mb-3'"
            :list="proposalSendingTypeList"
            type="radio"
            @click="proposalDealIdStore(dealData.id)"
          />
        </div>
          <template v-if="proposalSendingType === 'send_quick_proposal'">
              <div class="form-group mb-4">
                  <app-input
                      v-model="proposalChoseType"
                      :list="templateList"
                      :placeholder="$t('choose_one_and_confirm')"
                      :required="true"
                      list-value-field="subject"
                      type="select"
                  />
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
                                         v-model="proposalFormData.email"/>
                          </div>
                      </div>
                  </div>
              </div>
          </template>
      </template>

      <template v-else>
        <template v-if="!dealData.contact_person.length > 0">
          <div class="d-flex h-100 align-items-center">
            <app-note :notes="[$t('the_deal_has_no_contact_person_with_email')]" :show-title="false" class="w-100"
                      padding-class="p-2"
                      title="'send'"/>
          </div>
        </template>
        <template v-else>
          <div class="d-flex h-100 align-items-center">
            <app-note :notes='noteMessage' :show-title="false" class="w-100" content-type="html"
                      padding-class="p-2" title="'send'"/>
          </div>
        </template>
      </template>

    </template>
    <template slot="footer">
      <template v-if="dealData.contact_person.length > 0 && dealData.contact_person[0].email[0]">
        <button
          class="btn btn-secondary mr-2"
          data-dismiss="modal"
          type="button"
        >
          {{ $t("cancel") }}
        </button>
        <button
          class="btn btn-primary"
          type="button"
          @click.prevent="proposalSendMail()">
              <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
           </span>
          <template v-if="!loading"> {{ $t("confirm") }}</template>
        </button>
      </template>

      <template v-else>
        <button
          class="btn btn-lg btn-primary"
          data-dismiss="modal"
          type="button">
          {{ $t("ok") }}
        </button>
      </template>

    </template>
  </app-modal>

</template>

<script>
import moment from "moment";
import {FormMixin} from "@core/mixins/form/FormMixin";
import {urlGenerator} from "@app/Helpers/helpers";
import {api} from "@app/Helpers/api";
import {collect} from "@app/Helpers/Collection";
export default {
name: "SendProposalModal",
  props: ["dealData", "templateList"],
  mixins: [FormMixin],
  data(){
    return{
      urlGenerator,
      loading: false,
      proposalFormData: {},
      proposalChoseType: "",
      proposalSendingType: "",

      proposalSendingTypeList: [
        {
          id: "send_proposal_with_new_template",
          value: "With new template",
        },
        {
          id: "send_quick_proposal",
          value: "Or want to send a quick proposal",
        },
      ],
    }
  },
  computed:{
    emailList(){
        return collect(this.dealData.contact_person).first().email.map((item)=>{
            return {
                id: item.value,
                value: item.value
            }
        })
    },
    noteMessage() {
      let url = route('persons.edit', {id: this.dealData.contact_person[0].id});
      return `
            ${this.$t("the_contact")}
            <a class="alert-link" href="${url}">
                ${this.dealData.contact_person[0].name}
            </a>
            ${this.$t("belongs_to_the_deal_has_no_email")}
        `;
    }
  },
  mounted() {
    if (collect(this.dealData.contact_person).first().email.length == 1){
        this.proposalFormData.email = collect(this.dealData.contact_person).first().email[0].value;
    }
  },
  methods:{
    beforeSubmit() {
      this.loading = true;
    },
    proposalSendMail() {
        this.axiosGet(
            route('statuses.index', {_query: {
                    name: "status_sent",
                    type: "proposal"}})
        ).then((res) => {
          let pipelineStatusId = collect(res.data).first().id;

          if (this.proposalSendingType == "send_quick_proposal") {
            this.proposalFormData.proposal_choice_type = this.proposalChoseType;
            this.proposalFormData.deal_id = Number(this.dealData.id);
            this.proposalFormData.status_id = pipelineStatusId,
              this.proposalFormData.sent_at = moment().format('YYYY-MM-DD H:m:s');
            this.submitFromFixin('post', route('send_deal_person.proposal'), this.proposalFormData)
          } else {
            this.loading = true;
            window.location.replace(route('proposals.create'));
          }
        });
    },
    afterSuccess(response) {
      this.closeModal();
      this.proposalSendingType = "";
      this.proposalChoseType = "";
      this.$toastr.s(response.data.message);
    },
    afterFinalResponse() {
      this.loading = false;
    },
    proposalDealIdStore(id) {
      this.$store.dispatch("setProposalDealId", id);
    },
    closeModal(value) {
      this.$emit("close-modal", value);
    },
  }
}
</script>

