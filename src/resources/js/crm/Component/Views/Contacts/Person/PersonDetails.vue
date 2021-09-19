<template>
	<div>
		<app-overlay-loader v-if="!dataLoaded"/>
		<div v-else class="content-wrapper">
			<div class="row">
				<div class="col-sm-12 col-md-6">
					<app-breadcrumb
						:button="{label: $t('back'), url: route('persons.lists')}"
						:directory="[$t('contacts'), name]"
						:icon="'users'"
						:page-title="name"
					/>
				</div>
				<div class="col-sm-12 col-md-6">
					<div class="float-md-right mb-3 mb-sm-3 mb-md-0">
						<button
							class="btn btn-primary btn-with-shadow"
							data-toggle="modal"
							type="button"
							@click="openDealModal()"
						>{{ $t('add_deal') }}
						</button>
					</div>
				</div>
			</div>

      <component is="app-user-info-details"
                 :user-info-sync-url="route('persons.upload-profile-picture-of', {id: formData.id})"
                 :user-info-data="formData"
                 component-type="person"
                 @update-request="updateRequest"/>

			<div class="row">
				<div class="col-xl-4">


					<app-details-information
						class="mb-primary"
						:details-data="formData"
						:details-sync-url="route('persons.update', {id: formData.id})"
						@update-request="updateRequest"/>



					<component is="app-details-job-title"
						class="mb-primary"
						v-if="isComponent"
						:job-title-data="formData.organizations"
						:job-title-url-sync="route('persons.sync-organizations', {id: formData.id})"
						:data-list="organizationList"
						component-type="person"
						@update-request="updateRequest"/>

          <app-details-contact-info
            :contact-info-data="formData"
            :contact-info-sync-url="route('persons.sync-contact', {id: formData.id})"
            :person-id="formData.id"
            @update-request="updateRequest"
          />


					<app-address-details
						:address-details="formData"
						:address-update-url="route('persons.update', {id: formData.id})"
						class="mb-primary"
						@update-request="updateRequest"
					/>

          <component is="app-details-deals"
                     :Deals="formData.deals"
                     :contact-list="peopleList"
                     :Id="formData.id"
                     component-type="person"
                     class="mb-primary"
          />

                    <details-page-custom-field
                        v-if="dataLoaded && isComponent"
                        :component-type="'person'"
                        :form-data="formData"
                        :update-url="route('persons.update', {id: formData.id})"
                        @update-request="updateRequest"
                    />

					<details-tag-manager
                        :post-url="route('persons.attach-tag', {id: formData.id})"
                        :taggable-id="formData.id"
                        :tagData="formData.tags"
                        class="mb-primary"/>

					<app-follower-details
						:follower-data="formData"
						:follower-sync-url="route('persons.sync-followers', {id: formData.id})"
						:get-follower-url="route('persons.get-followers', {id: formData.id})"
						:people-list="peopleList"
						class="mb-primary"
						@update-request="updateRequest"
					/>

				</div>
				<div class="col-xl-8">
					<app-tab v-if="appTapShow" :tabs="todoActivityTab" class="mb-primary" type="horizontal"/>
          <app-common-activity-show
            :Data="formData"
            :edit-url="selectedUrl"
            :activity-filter-url="route('persons.view-activities', {id: formData.id})"
            :file-filter-url="route('person.get-file', {id: formData.id})"
            :note-filter-url="route('person.get-note', {id: formData.id})"
            :activity-status="activityStatusList"
            component-type="person"
            v-if="isComponent && activityStatusList.length"
            @open-activity="editActivity"
            @open-note-modal="editNote"
          />
				</div>
			</div>

			<app-deal-modal
				v-if="isDealModalActive"
				:pre-selected-option="{'person_id':formData.id}"
        component-type="person"
        :selectedUrlId="formData.id"
				@close-modal="closeDealModal"
			/>


			<app-common-activity-modal

				v-if="personActivitiesModal"
				:activity="rowData"
				:selected-url="editedUrl"
				@close-modal="closeActivityModal"
			/>

			<app-common-note-modal v-if="isNoteModal" :note="noteRowData" @close-modal="closeNoteModal"/>

		</div>
	</div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {formatDateToLocal, onlyTime, onlyTimeFromTime, textTruncate, urlGenerator} from "@app/Helpers/helpers";

export default {
	props: ["selectedUrl"],
	name: "PersonDetails",
	mixins: [FormMixin],
	data() {
		return {
			urlGenerator,
            route,
			dataLoaded: false,
			preLoader: false,
			isDealModalActive: false,
			isNoteModal: false,
			personActivitiesModal: false,
			formData: {},
			appTapShow: false,
			isComponent: true,
			name: "",
			editedUrl: "",
			activitiesList: [],
			isfileNoteFilter: true,

			// Tab Start
			todoActivityTab: [
				{
					name: "activity",
					icon: "activity",
					component: "common-activity-tab",
					props: "",
					permission: "",
				},
				{
					name: "file",
					icon: "paperclip",
					component: "common-file-tab",
					props: "",
					permission: "",
				},
				{
					name: "note",
					icon: "file-text",
					component: "common-note-tab",
					props: "",
					permission: "",
				},
			],


			rowData: null,
			dealNoteModal: false,
			noteRowData: null,
			value: "",
			formatDateToLocal,
			onlyTimeFromTime,
			onlyTime,
			textTruncate,
		};
	},
	computed: {
		peopleList() {
			return this.$store.getters.getPerson
		},

		organizationList() {
			return this.$store.getters.getOrganization
		},

        activityStatusList() {
            return this.$store.getters.getActivityStatus
        },
	},
	methods: {
		afterSuccessFromGetEditData(response) {
			this.formData = response.data;
			this.name = this.formData.name;
			this.todoActivityTab.forEach((el) => {
				el["props"] = {
					id: this.formData.id,
					person: "person",
					contextable_type: "App\\Models\\CRM\\Person\\Person",
					activitySyncUrl: route('person.sync-activities', {id: this.formData.id}),
					fileSyncUrl: route('person.sync-file', {id: this.formData.id}),
					noteSyncUrl: route('person.sync-note', {id: this.formData.id}),
				};
			});

			this.appTapShow = true;
      this.dataLoaded = true;
		},

		updateRequest() {
			this.isComponent = false;
			this.axiosGet(this.selectedUrl).then((response) => {
				this.formData = response.data;
				this.name = this.formData.name;
				this.isComponent = true;
			}).catch((error) => console.log(error));
		},

		updateDealList() {
			this.$hub.$on("deal-update-list", (value = true) => {
				if (value) {
					this.updateRequest();
				}
			});
		},
		updateTagList() {
			this.$hub.$on("tag-list", (value = true) => {
				if (value) {
					this.updateRequest();
				}
			});
		},

		editActivity(activity) {
			this.editedUrl = `/activities/${activity.id}`;
			this.rowData = activity;
			this.personActivitiesModal = true;
		},
		openDealModal() {
			this.isDealModalActive = true;
			setTimeout(function () {
				$("#deal-modal").modal("show");
			});
		},
		closeDealModal() {
			this.isDealModalActive = false;
			$("#deal-modal").modal("hide");
		},
		closeActivityModal() {
			this.personActivitiesModal = false;
			$("#common-activity-modal").modal("hide");
		},

		editNote(activity) {
			this.isNoteModal = true;
			this.noteRowData = activity;
			setTimeout(() => {
				$("#note-modal").modal("show");
			});
		},
		closeNoteModal() {
			this.isNoteModal = false;
			$("#note-modal").modal("hide");
		},
	},

	mounted() {
		this.updateDealList();
		this.updateTagList();
		this.$store.dispatch("getPerson");
		this.$store.dispatch('getOrganization');
		this.$store.dispatch('getActivityStatus');
	},
};
</script>
