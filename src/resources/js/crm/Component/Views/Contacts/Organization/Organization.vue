<template>
  <div class="content-wrapper organization">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb
          :page-title="$t('organizations')"
          :directory="[$t('contacts'), $t('organization')]"
          :icon="'users'"
        />
      </div>
      <div class="col-sm-12 col-md-6">
        <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
          <div class="dropdown d-inline-block btn-dropdown"
          v-if="$can('import_organization') ||
                $can('export_organization') ||
                $can('create_types') ||
                $can('view_types')"
          >
            <button
              type="button"
              class="btn btn-success dropdown-toggle ml-0 mr-2"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              {{ $t("actions") }}
            </button>
            <div class="dropdown-menu">
              <a
                class="dropdown-item d-flex align-items-center p-3"
                :href="route('organization.import')"
                v-if="$can('import_organization')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'download'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("import_organization") }}
              </a>
                <a
                    class="dropdown-item d-flex align-items-center p-3"
                    :href="route('organization.export')"
                    v-if="$can('export_organization')"
                >
                    <app-icon
                        stroke-width="1"
                        :name="'download'"
                        width="16"
                        height="16"
                        class="mr-3"
                    />
                    {{ $t("export_organization") }}
                </a>
              <a
                class="dropdown-item d-flex align-items-center p-3"
                href=""
                @click.prevent="openContactModal()"
                v-if="$can('create_types')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'plus-circle'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("add_leads_group") }}
              </a>
              <a
                class="dropdown-item d-flex align-items-center p-3"
                :href="route('contact_type.list')"
                v-if="$can('view_types')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'settings'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("manage_lead_group") }}
              </a>
            </div>
          </div>

          <button
            type="button"
            v-if="$can('create_organizations')"
            class="btn btn-primary btn-with-shadow"
            data-toggle="modal"
            @click="openModal()"
          >
            {{ $t("add_organization") }}
          </button>
        </div>
      </div>
    </div>

    <app-table :id="tableId" :options="options" @action="getAction" />

    <app-organization-modal
      v-if="isModalActive"
      :table-id="tableId"
      :selected-url="organizationUrl"
      @close-modal="closeAddEditModal"
    />
    <app-contact-type-modal
      v-if="isContactModalActive"
      :table-id="contacttableId"
      @close-modal="closeContactModal"
    />

    <app-confirmation-modal
      v-if="confirmationModalActive"
      modal-id="organization-delete-modal"
      @confirmed="confirmed"
      @cancelled="cancelled"
    />

    <app-common-activity-modal
      v-if="orgActivitiesModal"
      :activity="rowData"
      :selected-url="editedUrl"
      @close-modal="closeActivityModal"
    />
    <app-common-all-deals
      v-if="viewAllModal"
      :id="organizationId"
      :context-type="'organization'"
      @close-modal="closedViewModal"
    />
    <app-common-all-follower
      v-if="viewAllFollower"
      :follower-data="followerUrl"
      @close-modal="closedFollowerModal"
    />
    <app-common-note-modal
      v-if="isNoteModal"
      :note="noteRowData"
      @close-modal="closeNoteModal"
    />
  </div>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import OrganizationTable from "../../../DatatableMixins/OrganizationTable";
import { contactType, owner, person, tag } from "@app/Mixins/Global/FilterMixins";
import { getCustomFileds } from "@app/Mixins/Global/CustomFieldMixin";
import { DeleteMixin } from "@app/Mixins/Global/DeleteMixin";

export default {
  name: "Organization",
  mixins: [
    FormMixin,
    OrganizationTable,
    owner,
    contactType,
    person,
    tag,
    getCustomFileds,
    DeleteMixin,
  ],
  data() {
    return {
      route,
      isModalActive: false,
      isContactModalActive: false,
      tableId: "organization-modal",
      contacttableId: "contact-modal",
      organizationUrl: "",
      orgActivitiesModal: false,
      rowData: {},
      editedUrl: "",
      viewAllModal: false,
      viewAllFollower: false,
      isNoteModal: false,
      noteRowData: null,
      followerUrl: "",
      organizationId: null,
      customFieldList: [],
    };
  },

  methods: {
    openContactModal() {
      this.isContactModalActive = true;
    },
    closeContactModal() {
      this.isContactModalActive = false;
      this.organizationUrl = "";
      $("#contact-type-modal").modal("hide");
    },
    openModal() {
      this.isModalActive = true;
    },
    getAction(row, action, active) {
      if (action.title == this.$t("edit")) {
        this.organizationUrl = route('organizations.show', {id: row.id});
        this.isModalActive = true;
      } else if (action.title == this.$t("delete")) {
        this.deleteUrl = route('organizations.destroy', {id: row.id});
        this.confirmationModalActive = true;
      }
    },

    closeAddEditModal() {
      this.isModalActive = false;
      this.organizationUrl = "";
      $("#organization-modal").modal("hide");
    },

    commonActivityModal(activity) {
      this.editedUrl = route('activities.show', {id: activity.id});
      this.rowData = activity;
      this.orgActivitiesModal = true;
    },
    closeActivityModal() {
      this.orgActivitiesModal = false;
    },
    openViewAllModal(organizationId) {
      this.organizationId = organizationId;
      this.viewAllModal = true;
    },
    openViewAllFollower(followerUrl) {
      this.followerUrl = followerUrl;
      this.viewAllFollower = true;
    },
    closedViewModal() {
      this.viewAllModal = false;
      $("#details-view-modal").modal("hide");
    },
    closedFollowerModal() {
      this.viewAllFollower = false;
      $("#details-view-modal").modal("hide");
    },
    editNote(activity) {
      this.noteRowData = activity;
      this.isNoteModal = true;
    },
    closeNoteModal() {
      this.isNoteModal = false;
      $("#note-modal").modal("hide");
    },
      getLeadGroup(){
          this.axiosGet(route('get_lead_groups'))
              .then(({data}) => {
                  let pipeline = this.options.filters.find((item)=> item.title === this.$t('lead_group'))
                  if (data.length){
                      data.map((item)=>{
                          pipeline.option.push({
                              id: item.id,
                              value: item.name
                          })
                      });
                  }
              })
              .catch((error) => console.log(error));
      }
  },
  created() {
      if (!this.$can('manage_public_access') && this.$can('client_access')){
          this.getLeadGroup();
      }
    this.getCustomFiled("organization");
    this.$store.dispatch("getAllCountry");
  },
};
</script>

<style>
.organization .link-list {
  white-space: nowrap !important;
  max-width: 180px;
  text-overflow: ellipsis;
  overflow: hidden;
}
</style>
