<template>
  <div>
    <app-overlay-loader v-if="!dataLoaded"/>
    <div class="content-wrapper" v-else>
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <app-breadcrumb
              :page-title="name"
              :directory="[$t('contacts'), name]"
              :icon="'users'"
              :button="{label: $t('back'), url:route('organizations.lists')}"
          />
        </div>
        <div class="col-sm-12 col-md-6">
          <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
            <button
                type="button"
                class="btn btn-primary btn-with-shadow"
                data-toggle="modal"
                @click="openDealModal()"
            >{{ $t('add_deal') }}
            </button>
          </div>
        </div>
      </div>

      <component is="app-user-info-details"
                 :user-info-sync-url="route('organizations.upload-profile-picture-of', {id: formData.id})"
                 :user-info-data="formData"
                 component-type="org"
                 @update-request="updateRequest"/>

      <div class="row">

        <div class="col-xl-4">

          <app-details-information
            class="mb-primary"
            :details-data="formData"
            :details-sync-url="route('organizations.update', {id: formData.id})"
            @update-request="updateRequest"/>

	        <component is="app-details-job-title"
		        class="mb-primary"
		        v-if="isComponent"
		        :job-title-data="formData.persons"
		        :job-title-url-sync="route('organizations.sync-person', {id: formData.id})"
		         component-type="organization"
		        :data-list="peopleList"
		        @update-request="updateRequest"/>

	        <app-address-details
		        class="mb-primary"
		        :address-details="formData"
		        :address-update-url="route('organizations.update', {id: formData.id})"
		        @update-request="updateRequest"
	        />

          <component is="app-details-deals"
                     :Deals="formData.deals"
                     :contact-list="peopleList"
                     :Id="formData.id"
                     component-type="org"
                     class="mb-primary"
          />

            <details-page-custom-field
                v-if="dataLoaded && isComponent"
                :component-type="'organization'"
                :form-data="formData"
                :update-url="route('organizations.update', {id: formData.id})"
                @update-request="updateRequest"
            />

          <details-tag-manager
              :post-url="route('organizations.attach-tag', {id: formData.id})"
              :taggable-id="formData.id"
              class="mb-primary"
              :tagData="formData.tags"
          />

	        <app-follower-details
		        class="mb-primary"
		        :follower-data="formData"
		        :follower-sync-url="route('organizations.sync-follower', {id: formData.id})"
		        :get-follower-url="route('organizations.get-follower', {id: formData.id})"
		        :people-list="peopleList"
		        @update-request="updateRequest"/>

        </div>

        <div class="col-xl-8">
          <app-tab class="mb-primary" type="horizontal" :tabs="todoActivityTab" v-if="appTapShow"/>
          <app-common-activity-show
            :Data="formData"
            :edit-url="selectedUrl"
            :activity-filter-url="route('organizations.view-activities', {id: formData.id})"
            :file-filter-url="route('organization.get-file', {id: formData.id})"
            :note-filter-url="route('organization.get-note', {id: formData.id})"
            component-type="org"
            :activity-status="activityStatusList"
            v-if="isComponent && activityStatusList.length"
            @open-activity="editActivity"
            @open-note-modal="editNote"
          />
        </div>
      </div>
      <app-deal-modal
          v-if="isDealModalActive"
          :pre-selected-option="{'organization_id':formData.id}"
          component-type="org"
          :selectedUrlId="formData.id"
          @close-modal="closeDealModal"
      />

      <app-common-activity-modal
          v-if="orgActivitiesModal"
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
import {formatDateToLocal, textTruncate, onlyTime, onlyTimeFromTime, urlGenerator} from "@app/Helpers/helpers";
import {collect} from "@app/Helpers/Collection";


export default {
  props: ["selectedUrl"],
  name: "OrganizationView",
  mixins: [FormMixin],
  data() {
    return {
        urlGenerator,
        route,
      dataLoaded: false,
      isDealModalActive: false,
      isComponent: true,
      formData: {},
      editedUrl: "",
      name: "",
      appTapShow: false,
      isfileNoteFilter: true,
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

      activitiesList: [],
      rowData: null,
      orgActivitiesModal: false,
      dealNoteModal: false,
      isNoteModal: false,
      noteRowData: null,
      value: "",
      formatDateToLocal,
      onlyTimeFromTime,
      onlyTime,
      textTruncate
    };
  },
  computed: {
      peopleList() {
        return this.$store.getters.getPerson
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
          organization: "organization",
          contextable_type: "App\\Models\\CRM\\Organization\\Organization",
            activitySyncUrl: route('organization.sync-activities', {id: this.formData.id}),
            fileSyncUrl: route('organization.sync-file', {id: this.formData.id}),
            noteSyncUrl: route('organization.sync-note', {id: this.formData.id}),
        };
      });

      this.appTapShow = true;
      this.dataLoaded = true;
    },
    updateRequest() {
      this.isComponent = false;
      this.axiosGet(this.selectedUrl)
          .then((response) => {
            this.formData = response.data;
            this.name = this.formData.name;
            this.isComponent = true;
          })
          .catch((error) => console.log(error));
    },
    editActivity(activity) {
      this.editedUrl = route('activities.show', {id: activity.id});
      this.rowData = activity;
      this.orgActivitiesModal = true;
    },
    openDealModal() {
      this.isDealModalActive = true;
      setTimeout(() => {
        $("#deal-modal").modal("show");
      });
    },
    closeDealModal() {
      this.updateRequest();
      this.isDealModalActive = false;
      $("#deal-modal").modal("hide");
    },
    closeActivityModal() {
      this.orgActivitiesModal = false;
      $("#common-activity-modal").modal("hide");
    },

    updateTagList() {
      this.$hub.$on("tag-list", (value = true) => {
        if (value) {
          this.updateRequest();
        }
      });
    },
    updateDealList() {
      this.$hub.$on('deal-update-list', (value = true) => {
        if (value) {
          this.updateRequest();
        }
      })
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
    this.$store.dispatch("getPerson");
    this.$store.dispatch("getOrganization");
      this.$store.dispatch('getActivityStatus');
    this.updateTagList();
    this.updateDealList();
  },
};
</script>
