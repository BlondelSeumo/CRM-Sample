<template>
	<div class="card border-0 card-with-shadow">
		<div class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center">

			<h5 v-show="isDetailsActive" class="card-title text-muted mb-0">{{ $t('details') }}</h5>
			<h5 v-show="!isDetailsActive" class="card-title text-muted mb-0">{{ $t('edit_details') }}</h5>

			<div>
				<a v-show="!isDetailsActive" class="text-muted" href="#" @click.prevent="detailsInfoClose">
					<app-icon name="x-square" stroke-width="1"/>
				</a>
				<a v-show="!isDetailsActive" class="text-muted" href="#" @click.prevent="detailsInfoSync">
					<app-icon name="check-square" stroke-width="1"/>
				</a>
				<a v-show="isDetailsActive" class="text-muted" href="#" @click.prevent="detailsInfoEdit">
					<app-icon name="edit" stroke-width="1"/>
				</a>
			</div>

		</div>
		<div class="card-body">

			<div v-show="isDetailsActive">
				<div class="form-group mb-3">
					<div class="form-row">
						<label class="mb-0 text-muted col-3 d-flex align-items-center">
							{{ $t('name') }}
						</label>
						<div class="col-9">
							{{ detailsData.name }}
						</div>
					</div>
				</div>

				<div class="form-group mb-3">
					<div class="form-row">
						<label class="mb-0 text-muted col-3 d-flex align-items-center">
							{{ $t('lead_group') }}
						</label>
						<div class="col-9">
                                <span v-if="detailsData.contact_type"
                                      :class='`badge badge-sm badge-pill badge-${detailsData.contact_type.class}`'>
                                    {{ detailsData.contact_type.name }}
                                </span>
						</div>
					</div>
				</div>

				<div class="form-group mb-0">
					<div class="form-row">
						<label class="mb-0 text-muted col-3 d-flex align-items-center">
							{{ $t('owner') }}
						</label>
						<div class="col-9">
							<div v-if="detailsData.owner" class="d-flex align-items-center">
								<app-avatar :avatar-class="'avatars-w-30'"
								            :img="detailsData.owner.profile_picture ?
                                            urlGenerator(detailsData.owner.profile_picture.path) :
                                            detailsData.owner.profile_picture"
								            :title="detailsData.owner.full_name"
								            class="mr-2"
								/>
								<span>{{ detailsData.owner.full_name }}</span>
							</div>
                            <div class="d-flex align-items-center" v-else>
                                <span class="font-size-90 text-secondary"> {{ $t("user_deleted") }} </span>
                            </div>
						</div>
					</div>
				</div>
			</div>

			<div v-show="!isDetailsActive">

				<div class="form-group mb-3">
					<div class="form-row">
						<label class="col-3 mb-0 d-flex align-items-center">{{ $t('name') }}</label>
						<div class="col-9">
							<app-input v-model="detailsData.name"
							           :error-message="$errorMessage(errors, 'name')"
							           type="text"/>
						</div>
					</div>
				</div>

				<div class="form-group mb-3">
					<div class="form-row">
						<label class="col-3 mb-0 d-flex align-items-center">{{ $t('lead_group') }}</label>
						<div class="col-9">
							<app-input v-model="detailsData.contact_type_id"
							           :error-message="$errorMessage(errors, 'contact_type_id')"
							           :list="contentTypeList"
							           list-value-field='name'
							           type="select"/>
						</div>
					</div>
				</div>

				<div class="form-group mb-0" v-if="$can('manage_public_access')">
					<div class="form-row">
						<label class="col-3 mb-0 d-flex align-items-center">{{ $t('owner') }}</label>
						<div class="col-9">
							<app-input v-model="detailsData.owner_id"
							           :error-message="$errorMessage(errors, 'owner_id')"
							           :list="ownerList"
							           list-value-field='full_name'
							           type="select"/>
						</div>
					</div>
				</div>

			</div>

		</div>
	</div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {urlGenerator} from "@app/Helpers/helpers";

export default {
	name: "DetailsInformation",
	mixins: [FormMixin],
	props: {
		detailsData: {
			type: Object,
			required: true
		},
		detailsSyncUrl: {
			type: String,
			required: true
		}
	},
	data() {
		return {
			urlGenerator,
			isDetailsActive: true,
			formData: {},
			errors: {}
		}
	},
	computed: {
		contentTypeList() {
			return this.$store.getters.contentType
		},
		ownerList() {
			return this.$store.getters.getOwner
		}
	},
	methods: {
		detailsInfoSync() {

			this.formData.name = this.detailsData.name;
			this.formData.contact_type_id = this.detailsData.contact_type_id;
			this.formData.owner_id = this.detailsData.owner_id;
			this.submitFromFixin('patch', this.detailsSyncUrl, this.formData);
		},

		afterError(response) {
			this.errors = response.data.errors
		},

		afterSuccess(response) {
			this.$toastr.s(response.data.message);
			this.isDetailsActive = true
			this.errors = {}
			this.$emit('update-request');
		},

		detailsInfoClose() {
			this.isDetailsActive = true;
		},
		detailsInfoEdit() {
			this.isDetailsActive = false
		},
	},
	created() {
		this.$store.dispatch('contentType');
		this.$store.dispatch('getOwner');
	},
}
</script>

