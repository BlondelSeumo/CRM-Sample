<template>
	<div class="content-wrapper">
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<app-breadcrumb :page-title="$t('add_template')"
				                :directory="[$t('proposals'),$t('add_template')]"
				                :icon="'hexagon'"
				                :button="{label: $t('back')}"/>
			</div>
		</div>
		<form ref="form" :data-url="submitUrl">
			<div class="card border-0 card-with-shadow">
				<div class="card-body">
					<div class="form-group row">
						<div class="col-lg-9 col-xl-9">
							<div class="form-group row">
								<div class="col-lg-3 col-xl-3 d-flex align-items-center">
									<label class="mb-lg-0">{{ $t('template_title') }}</label>
								</div>
								<div class="col-lg-9 col-xl-9">
									<div>
										<app-input type="text"
										           :placeholder="$t('type_your_template_title')"
										           :required="true"
										           v-model="formData.subject"/>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-xl-3  d-flex h-100 align-items-center justify-content-lg-end">
							<button type="button"
							        class="btn btn-primary btn-with-shadow"
							        data-toggle="modal"
							        @click="isChooseTemplateModal = true">
								{{ $t('choose_template') }}
							</button>
						</div>
					</div>

					<div class="form-group">
						<div class="form-row">
							<div class="col-sm-12">
								<app-input
									v-if="textEditorReboot"
									type="text-editor"
									:required="true"
									id="text-editor-id"
									v-model="formData.custom_content"/>

								<div class="form-group text-center">
									<button
										type="button"
										class="btn btn-sm btn-primary px-3 py-1 mr-2 mt-4"
										data-toggle="tooltip"
										data-placement="top"
										v-for="tag in all_tags"
										:title="tag.description"
										@click="addTag(tag.tag)"
									>{{ tag.tag }}
									</button>
								</div>
							</div>
						</div>
					</div>

					<div class="mt-5">
						<button type="button" class="btn btn-primary" @click.prevent="saveAsTemplate">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
							<template v-if="!loading">{{ $t('save_as_template') }}</template>
						</button>
						<button type="button" class="btn btn-primary" @click.prevent="saveAndSend">
							{{ $t('save_and_send') }}
						</button>
						<button type="button" class="btn btn-secondary mr-2" data-dismiss="modal"
						        onclick="window.history.back()">
							{{ $t('cancel') }}
						</button>
					</div>
				</div>
			</div>
		</form>

		<app-check-email-deliver v-if="isCheckMailModalActive"
		                         :header-title="$t('send_proposal')"
		                         @close-modal="closeModalCheckMail"/>

		<app-send-proposal-modal v-if="isSendProposalModalActive"
		                         :table-id="tableIdSendProposal"
		                         :template-data="formData"
		                         @close-modal="closeSendProposalModal"/>

		<app-choose-template-modal v-if="isChooseTemplateModal"
		                           :table-id="tableId"
		                           :modalId="tableId"
		                           @selected="setContent"
		                           @close="isChooseTemplateModal = false"/>

	</div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
import {urlGenerator} from "@app/Helpers/helpers";
import {mapGetters} from 'vuex'

export default {
	props: ['selectedUrl', 'action'],
	name: "AddTemplate",
	mixins: [FormMixin],
	data() {
		return {
			formData: {},
			isEditState: '',
			loading: false,
			isChooseTemplateModal: false,
			isSendProposalModalActive: false,
			isCheckMailModalActive: false,
			tableId: "template-choose-modal",
			tableIdSendProposal: "send-proposal-modal",
			textEditorReboot: false,
			tags: {
				'{app_name}': this.$t('Name of the app'),
				'{app_logo}': this.$t('The app logo'),
			},
		}
	},
	mounted() {
		let instance = this;
		$('#template-choose-modal').on('hidden.bs.modal', function (e) {
			instance.isChooseTemplateModal = false;
		});

		$('#send-proposal-modal').on('hidden.bs.modal', function (e) {
			instance.isSendProposalModalActive = false;
		});

		if (this.isCreateState) {
			this.textEditorReboot = true
		}
		this.$store.dispatch('checkEmailDelivery');
	},

	methods: {
		setContent(template) {
			const content = this.getTemplateContent(template);
			if (content !== this.formData.custom_content) {
				this.textEditorReboot = false
				this.formData.custom_content = content
				setTimeout(() => this.textEditorReboot = true)
			}
		},
		getTemplateContent(data) {
			if (data.custom_content)
				return data.custom_content;
			return data.default_content;
		},
		saveAndSend() {
			if (this.checkEmailDelivery != 1) {
				this.isCheckMailModalActive = true;
			} else {
				if (this.formData.subject && this.formData.custom_content) {
					this.isSendProposalModalActive = true;
				}
			}
		},
		beforeSubmit() {
			this.loading = true;
		},
		saveAsTemplate() {
			this.submitFromFixin(
				this.isEditState,
				this.submitUrl,
				this.formData
			);
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
			if (this.isCreateState) {
				this.textEditorReboot = false
				this.formData = {}
				setTimeout(() => this.textEditorReboot = true)
			}
			this.$toastr.s(response.data.message);
			window.location.replace(route('template.view'))
			this.$hub.$emit('reload-' + this.tableId);
		},
		afterFinalResponse() {
			this.loading = false;
			this.formData = '';
		},
		afterSuccessFromGetEditData(response) {
			let instance = this;
			instance.formData = response.data;
			if (this.formData.custom_content) {
				this.formData.custom_content = this.formData.custom_content;
			} else {
				this.formData.custom_content = this.formData.default_content;
			}
			this.textEditorReboot = true
		},
		openModal() {
			let instance = this;
			instance.isChooseTemplateModal = true;
			setTimeout(function () {
				$('#app-template-choose-modal').modal('show');
			});
		},
		closeModalCheckMail() {
			this.isCheckMailModalActive = false;
			$('#check-email-modal').modal('hide');
		},
		closeModal() {
			let instance = this;
			this.isChooseTemplateModal = false;
			$("#app-template-choose-modal").modal('hide');
		},

		closeSendProposalModal() {
			this.isSendProposalModalActive = false;
			$('#send-proposal-modal').modal('hide');
		},
		addTag(tag_name = '{name}') {
			$("#text-editor-id").summernote('editor.saveRange');
			$("#text-editor-id").summernote('editor.restoreRange');
			$("#text-editor-id").summernote('editor.focus');
			$("#text-editor-id").summernote('editor.insertText', tag_name);
		},
	},
	computed: {
		all_tags() {
			const tags = Object.keys(this.tags).filter(tag => {
				return ['{app_name}', '{app_logo}',].includes(tag)
			})
			return tags.map(tag => {
				return {tag, description: this.tags[tag]}
			})
		},
		submitUrl() {
			if (this.action) {
				if (this.action === 'copy') {
					this.isEditState = 'post'
					return route('templates.store')
				}
			} else {
				if (this.selectedUrl) {
					this.isEditState = 'patch'
					return this.selectedUrl;
				} else {
					this.isEditState = 'post'
                    return route('templates.store')
				}
			}
		},
		isCreateState() {
			return (!Boolean(this.selectedUrl));
		},
		...mapGetters([
			'checkEmailDelivery',
		])
	},
}
</script>
