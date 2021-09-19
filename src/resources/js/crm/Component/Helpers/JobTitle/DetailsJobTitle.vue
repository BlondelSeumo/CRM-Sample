<template>
	<div class="card border-0 card-with-shadow">
		<div class="card-header bg-transparent p-primary">

			<div class="row">
				<div class="col-8">
					<h5 v-show="isJobTitleActive" class="card-title text-muted m-0">
						{{
							componentType == 'organization' ? $t('people_and_job_title') : $t('organizations_and_job_title')
						}}
					</h5>

					<h5 v-show="!isJobTitleActive" class="card-title text-muted m-0">
						{{
							componentType == 'organization' ? $t('edit_people_and_job_title') : $t('edit_organizations_and_job_title')
						}}
					</h5>
				</div>

				<div class="col-4">

					<div class="d-flex align-items-center justify-content-end h-100">

						<a v-show="!isJobTitleActive" class="text-muted" href="#"
						   @click.prevent="jobTitleClose">
							<app-icon name="x-square" stroke-width="1"/>
						</a>

						<a v-show="!isJobTitleActive" class="text-muted" href="#"
						   @click.prevent="jobTitleSync">
							<app-icon name="check-square" stroke-width="1"/>
						</a>

						<a v-show="isJobTitleActive" class="text-muted" href="#"
						   @click.prevent="jobTitleEdit">
							<app-icon name="edit" stroke-width="1"/>
						</a>

					</div>
				</div>
			</div>

		</div>

		<div class="card-body">

			<div v-show="isJobTitleActive">

				<template v-if="jobTitleData.length">

					<div v-for="(jobTitle, index) in jobTitleData"
					     :key="index"
					     :class="{'mb-3': jobTitle.length > 1 && index !== jobTitle.length - 1}"
					     class="d-flex justify-content-between align-items-center">

						<div class="media d-flex align-items-center mt-2">
							<app-avatar
								:img="jobTitle.profile_picture ? urlGenerator(jobTitle.profile_picture.path) : jobTitle.profile_picture"
								:title="jobTitle.name"
								avatar-class="avatars-w-40"
								class="mr-2"/>

							<div class="media-body">
                                <template v-if="jobTitle.id">
                                    <template v-if="componentType == 'organization'">
                                        <template v-if="adminAccess(jobTitle)">
                                            <a :href="route('persons.edit', {id: jobTitle.id})">{{ jobTitle.name }}</a>
                                        </template>
                                        <template v-else>
                                            <a>{{ jobTitle.name }}</a>
                                        </template>
                                    </template>
                                    <template v-else>
                                        <template v-if="adminAccess(jobTitle)">
                                            <a :href="route('organizations.edit', {id: jobTitle.id})"> {{ jobTitle.name }}</a>
                                        </template>
                                        <template v-else>
                                            <a> {{ jobTitle.name }}</a>
                                        </template>
                                    </template>
                                </template>

								<p class="text-muted font-size-90 mb-0">
									<span v-if="jobTitle.pivot !== ''"> {{ jobTitle.pivot.job_title }} </span>
									<span v-else> {{ $t('no_job_title_here') }} </span>
								</p>
							</div>

						</div>

						<template v-if="jobTitle.contact_type">
							<app-badge :className="`badge-sm badge-pill badge-${jobTitle.contact_type.class}`"
							           :label="jobTitle.contact_type.name"/>

						</template>

					</div>
				</template>

				<template v-else>
					<p class="text-muted">{{ $t('no_organization_linked_yet') }}</p>
					<a class="font-size-90" href="#" @click.prevent="jobTitleEdit">
						{{ componentType == 'organization' ? $t('link_a_person') : $t('link_an_organization') }}
					</a>
				</template>

			</div>

			<div v-show="!isJobTitleActive">

				<div v-for="(jobTitle, index) in jobTitleData"
				     :key="index"
				     :class="{'mb-3': jobTitle.length > 1 && index !== jobTitle.length - 1}"
				     class="form-group">

					<div class="form-row align-items-center">

						<div v-if="componentType == 'organization'" class="col-12 mb-1">
							<app-input v-model="jobTitle.pivot.person_id"
							           :list="dataList"
							           :placeholder="$t('choose_one')"
							           list-value-field='name'
							           type="search-select"/>

							<small v-if="errors[index + '.person_id']" class="text-danger">
								{{ errors[index + '.person_id'][0] }}
							</small>
						</div>

						<div v-else class="col-12 mb-1">
							<app-input v-model="jobTitle.pivot.organization_id"
							           :list="dataList"
							           :placeholder="$t('choose_one')"
							           list-value-field='name'
							           type="search-select"/>

							<small v-if="errors[index + '.organization_id']" class="text-danger">
								{{ errors[index + '.organization_id'][0] }}
							</small>
						</div>

						<div class="col-10">
							<app-input v-model="jobTitle.pivot.job_title"
							           :placeholder="$t('type_job_title')"
							           type="text"/>
						</div>

						<div class="col-2 d-flex justify-content-center">
							<a class="text-muted" href="#" @click.prevent="removeJobTitle(index)">
								<app-icon name="trash" stroke-width="1" width="20"/>
							</a>
						</div>

					</div>
				</div>
				<a href="#" @click.prevent="addMoreJobTitle">
					{{ $t('add_more') }}
				</a>
			</div>
		</div>
	</div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {urlGenerator} from "@app/Helpers/helpers";

export default {
	name: "DetailsJobTitle",
	mixins: [FormMixin],
	props: {
		jobTitleData: {
			required: true
		},
		jobTitleUrlSync: {
			type: String,
			required: true
		},
		dataList: {
			type: Array,
			required: true
		},
		componentType: {
			type: String,
			required: true
		},
	},
	data() {
		return {
		    route,
			urlGenerator,
			isJobTitleActive: true,
			formData: {},
			errors: {},
		}
	},

	methods: {
        adminAccess(personOrg){
            return (window.user.roles[0].is_admin || window.user.roles[0].name == 'Manager')  || window.user.id == personOrg.owner_id;
        },
		addMoreJobTitle() {
			if (this.componentType == 'organization') {
				this.jobTitleData.push({
					pivot: {
						person_id: '',
						job_title: ''
					}
				});
			} else {
				this.jobTitleData.push({
					pivot: {
						organization_id: '',
						job_title: ''
					}
				});
			}

		},
		removeJobTitle(index) {
			this.jobTitleData.splice(index, 1)
		},
		jobTitleEdit() {
			this.isJobTitleActive = false;
		},
		jobTitleClose() {
			this.isJobTitleActive = true;
		},

		jobTitleSync() {
			let isExits;
			if (this.componentType == 'organization') {

				this.formData = this.jobTitleData.map((value, index) => {
					return {
						'person_id': parseInt(value.pivot.person_id),
						'job_title': value.pivot.job_title
					}
				});
				isExits = this.formData.map(item => item.person_id);
			} else {
				this.formData = this.jobTitleData.map((value, index) => {
					return {
						'organization_id': parseInt(value.pivot.organization_id),
						'job_title': value.pivot.job_title
					}
				});

				isExits = this.formData.map(item => item.organization_id);
			}

			if (new Set(isExits).size == isExits.length) {

				this.axiosPost({
					url: this.jobTitleUrlSync,
					data: this.formData
				}).then(response => {
					this.afterSuccess(response);
				}).catch((error) => {
					this.errors = error.response.data.errors;
				})

			} else {
				this.componentType == 'organization' ?
					this.$toastr.i(this.$t('person_duplicate'), 'Duplicate') :
					this.$toastr.i(this.$t('organization_duplicate'), 'Duplicate');
			}
		},

		afterSuccess(response) {
			this.$toastr.s(response.data.message);
			this.isJobTitleActive = true
			this.errors = {};
			this.$emit('update-request')
		}

	},
}
</script>
