<template>
  <div class="content-wrapper">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb
          :button="{ label: $t('back') }"
          :directory="[$t('proposals'), $t('send_proposal')]"
          :icon="'hexagon'"
          :page-title="$t('send_proposal')"
        />
      </div>
    </div>

    <form v-if="dataLoaded" ref="form" :data-url="submitUrl">
      <div
        :class="{ 'loading-opacity': !editorShow }"
        class="card border-0 card-with-shadow"
      >
        <app-overlay-loader v-if="!editorShow"/>
        <div class="card-body">
          <div class="form-group mb-0">
            <div class="form-row">
              <div class="col-lg-9 col-xl-9">
                <div class="form-group row">
                  <div class="col-lg-2 col-xl-2 d-flex align-items-center">
                    <label class="mb-lg-0">{{ $t("proposal_title") }}</label>
                  </div>
                  <div class="col-lg-10 col-xl-10">
                    <div>
                      <app-input
                        v-model="formData.subject"
                        :placeholder="$t('type_your_proposal_title')"
                        :required="true"
                        type="text"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div
                class="col-lg-3 col-xl-3 d-flex h-100 align-items-center justify-content-lg-end">
                <button
                  class="btn btn-primary btn-with-shadow"
                  data-toggle="modal"
                  type="button"
                  @click="isChooseProposalModal = true">
                  {{ $t("choose_template") }}
                </button>
              </div>
            </div>
          </div>
          <div class="form-group mb-0">
            <div class="form-row">
              <div class="col-lg-9 col-xl-9">
                <div class="form-group row mb-2">
                  <div class="col-lg-2 col-xl-2 d-flex align-items-center">
                    <label>{{ $t("deal_title") }}</label>
                  </div>
                  <div class="col-lg-10 col-xl-10">
                    <div class="mb-2">
                      <app-input
                        v-model="formData.deal_id"
                        :list="dealList"
                        list-value-field="title"
                        type="search-select"
                      />
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-2 col-xl-2 d-flex align-items-center">
                  </div>
                  <div class="col-lg-10 col-xl-10">

                    <template v-if="this.deal">

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
              <div class="col-lg-3 col-xl-3"></div>
            </div>
          </div>

            <div class="form-group mb-0" v-if="emailList.length > 1">
                <div class="form-row">
                    <div class="col-lg-9 col-xl-9">
                        <div class="form-group row">
                            <div class="col-lg-2 col-xl-2">

                            </div>
                            <div class="col-lg-10 col-xl-10 p-0">
                                <div class="col-lg-12 col-xl-12">
                                    <label>{{$t('choose_an_email')}}</label>
                                </div>
                                <div class="col-lg-12 col-xl-12">
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
                    </div>
                    <div class="col-lg-3 col-xl-3"></div>
                </div>
            </div>

          <div class="form-group">
            <div class="form-row">
              <div class="col-sm-12">
                <app-input
                  v-if="editorShow"
                  id="text-editor-id"
                  v-model="formData.content"
                  :required="true"
                  type="text-editor"
                />
              </div>
            </div>
          </div>

          <div class="form-group text-center">
            <button
              v-for="tag in all_tags"
              :title="tag.description"
              class="btn btn-sm btn-primary px-3 py-1 mr-2 mt-4"
              data-placement="top"
              data-toggle="tooltip"
              type="button"
              @click="addTag(tag.tag)"
            >{{ tag.tag }}
            </button>
          </div>

            <template v-if="dataLoaded">
                <button :disabled="!deal || !deal.contact_person.length || !checkContactPersonEmail ? true : false"
                        class="btn btn-primary"
                        type="button"
                        @click.prevent="sendProposal">

                          <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                          </span>

                    <template v-if="!loading">{{ $t("send_proposal") }}</template>
                </button>

                <button
                    class="btn btn-secondary mr-2"
                    data-dismiss="modal"
                    onclick="window.history.back()"
                    type="button">
                    {{ $t("cancel") }}
                </button>
            </template>
        </div>
      </div>
    </form>

    <app-overlay-loader v-else/>

    <app-choose-template-modal
      v-if="isChooseProposalModal"
      :modalId="tableId"
      :table-id="tableId"
      @close="isChooseProposalModal = false"
      @selected="setContent"
    />
  </div>

</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
import {api} from "@app/Helpers/api";
import {collect} from "@app/Helpers/Collection";
import {urlGenerator} from "@app/Helpers/helpers";
import moment from "moment";

export default {
	props: ["selectedUrl", "action"],
	name: "SendProposal",
	mixins: [FormMixin],
	data() {
		return {
			formData: {deal_id: "",},
			dealList: [],
            emailList: [],
			isEditState: "",
			deal: {},
			loading: false,
			dataLoaded: false,
			editorShow: false,
			isChooseProposalModal: false,
			tableId: "proposal-choose-modal",
			textEditorReboot: false,
			tags: {
				'{app_name}': this.$t('Name of the app'),
				'{app_logo}': this.$t('The app logo'),
			},
		};
	},
	computed: {
		all_tags() {
			const tags = Object.keys(this.tags).filter(tag => {
				return ['{app_name}', '{app_logo}',].includes(tag)
			})
			return tags.map(tag => { return { tag, description: this.tags[tag] } })
		},
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
            ${this.$t("belongs_to_the_deal_has_no_email")}
        `;
		},
		submitUrl() {
			if (this.action) {
				if (this.action === "copy") {
					this.isEditState = "post";
					return route('proposals.store');
				}
			} else {
				if (this.selectedUrl) {
					this.isEditState = "patch";
					return this.selectedUrl;
				} else {
					this.isEditState = "post";
                    return route('proposals.store');
				}
			}
		},
		isCreateState() {
			return !Boolean(this.selectedUrl);
		},
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
                if (this.deal != undefined && Object.keys(this.deal).length){
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
            immediate: true,
        },
    },
	methods: {
		setContent(template) {
			const content = this.getTemplateContent(template);
			if (content !== this.formData.content) {
				this.formData.content = content;
				this.editorShow = false;
				setTimeout(() => {
					this.editorShow = true;
				}, 1500);
			}
		},
		getTemplateContent(data) {
			if (data.custom_content) return data.custom_content;
			return data.default_content;
		},
		beforeSubmit() {
			this.loading = true;
		},
		sendProposal() {
			this.formData.custom_content = this.formData.content;
            this.formData.sent_at = this.formData.sent_at ?
                this.formData.sent_at :
                moment().format('YYYY-MM-DD H:m:s');
            this.formData.expired_at = this.formData.expired_at ?
                this.formData.expired_at :
                null;
              let proposalData = {
                  subject: this.formData.subject,
                  deal_id: this.formData.deal_id,
                  custom_content: this.formData.content,
                  content: this.formData.content,
                  status_id: this.formData.status_id,
                  sent_at: this.formData.sent_at,
                  expired_at: this.formData.expired_at,
                  email: this.formData.email
              }
			this.$store.getters.setProposalDealId
				? this.submitFromFixin('post', route('send_deal_person.proposal'), proposalData)
				: this.submitFromFixin(this.isEditState, this.submitUrl, proposalData);
		},
		getStatus() {
            this.axiosGet(
                route('statuses.index', {_query: {
                                name: "status_sent",
                                type: "proposal"}})
            ).then((res) => {
                    this.formData.status_id  = this.formData.status_id ? this.formData.status_id :
                        collect(res.data).first().id;
                });

		},
		getOwner() {
			return (this.formData.owner_id = null);
		},
		afterError(response) {
			this.loading = false;
			if (response) {
				if (response.data.errors) {
					this.$toastr.e(response.data.message);
				}
			}
		},
		afterSuccess(response) {
			this.loading = false;
            this.$store.dispatch("clearDealID");
			if (this.isCreateState) {
				this.formData = {};
			}
            this.$toastr.s(response.data.message);
			window.location.replace(route('proposals.lists'));
			this.$hub.$emit("reload-" + this.tableId);
		},
		afterFinalResponse() {
			this.loading = false;
			this.formData = "";
		},
		afterSuccessFromGetEditData(response) {
			this.formData = response.data;
			this.formData.deal_id = this.formData.deal_id
				? this.formData.deal_id
				: null;
			this.deal = this.formData.deal ? this.formData.deal : {};
			$("#note").summernote({
				callbacks: {
					onChange: function () {
						let code = $(this).summernote("code");
						if (this.formData.content) {
							this.formData.content = code;
						}
					},
				},
			});
		},
		openModal() {
			this.isChooseProposalModal = true;
		},
		closeModal() {
			this.isChooseProposalModal = false;
			$("#proposal-choose-modal").modal("hide");
		},
		getDeals() {
			this.axiosGet(route('deals.index',{_query:{all:true}}))
				.then((response) => {
					this.dealList = response.data;
          if (!this.formData.deal_id){
            this.formData.deal_id = this.formData.deal_id
              ? this.dealList[0]?.id
              : null;
          }
					this.dataLoaded = true;
				})
				.finally(() => {
          if (this.$store.getters.setProposalDealId){
            this.formData.deal_id = this.$store.getters.setProposalDealId;
          }
					setTimeout(() => {
						this.editorShow = true;
					}, 1500);
				});
		},

		addTag(tag_name = '{name}') {
			$("#text-editor-id").summernote('editor.saveRange');
			$("#text-editor-id").summernote('editor.restoreRange');
			$("#text-editor-id").summernote('editor.focus');
			$("#text-editor-id").summernote('editor.insertText', tag_name);
		},
	},
	mounted() {
		this.getDeals();
		if (!this.formData.status_id) {
			this.getStatus();
		}
		if (!this.formData.owner_id) {
			this.getOwner();
		}
	},
};
</script>
