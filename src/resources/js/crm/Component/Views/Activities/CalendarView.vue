<template>
  <div class="content-wrapper activities-calendar-view">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb
          :page-title="$t('calendar_view')"
          :directory="[$t('activities'), $t('calendar_view')]"
          :icon="'activity'"
        />
      </div>
      <div class="col-sm-12 col-md-6" v-if="$can('create_activities')">
        <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
          <button
            type="button"
            class="btn btn-primary btn-with-shadow"
            data-toggle="modal"
            @click.prevent="openModal()"
          >
            {{ $t("add_activity") }}
          </button>
        </div>
      </div>
      <component
        :is="'app-activity-modal'"
        v-if="isActivityModalActive"
        :table-id="activityModalId"
        :selected-url="editedUrl"
        :set-date="getSelectedDate"
        :previous-data="isPreSetData"
        :select-data="activityData"
        @save="closeModal"
        @cancel-modal="cancelModal"
      />
    </div>
    <activity-calendar
      :data-loaded="dataLoaded"
      :options="options"
      @select="handleClick"
      @eventClick="selectedEventHandle"
      @trigger-filter="getActivityData"
    />
    <div v-click-outside="closeEventModal">
      <div class="custom-modal shadow d-none" @close-modal="closeEventModal">
        <template v-if="customModal">
          <div v-if="!activityView">
            <div class="form-row">
              <div class="col-12">
                <app-input
                  type="radio-buttons"
                  :list="activityTypeOption"
                  v-model="eventTitle"
                />
                <app-input
                  type="text"
                  :placeholder="$t('title')"
                  v-model="formData.title"
                  :required="true"
                  class="mt-2"
                />
                <small class="text-danger" v-if="errors.title">{{
                  errors.title[0]
                }}</small>
              </div>
            </div>

            <!-- Activity change input -->
            <div class="form-row mt-2">
              <div class="col-sm-12">
                <app-input
                  type="select"
                  list-value-field="title"
                  :list="typeList"
                  :placeholder="$t('choose_your_activity_type')"
                  :required="true"
                  v-model="formData.type_of_activity"
                />
              </div>
            </div>
            <!-- end of Activity change input -->

            <!-- deal input -->
            <div class="form-row mt-2" v-if="formData.type_of_activity == 1">
              <div class="col-sm-12">
                <app-input
                  type="search-select"
                  list-value-field="title"
                  :list="dealList"
                  :placeholder="$t('choose_a_deal')"
                  :required="true"
                  v-model="formData.contextable_id"
                />
              </div>
            </div>
            <!-- end of deal input -->

            <!-- Person input -->
            <div
              class="form-row mt-2"
              v-else-if="formData.type_of_activity == 2"
            >
              <div class="col-sm-12">
                <app-input
                  type="search-select"
                  :list="personList"
                  :placeholder="$t('choose_a_person')"
                  list-value-field="name"
                  :required="true"
                  v-model="formData.contextable_id"
                />
              </div>
            </div>
            <!-- end of Person input -->

            <!-- Organization input -->
            <div
              class="form-row mt-2"
              v-else-if="formData.type_of_activity == 3"
            >
              <div class="col-sm-12">
                <app-input
                  type="search-select"
                  :list="organizationList"
                  :placeholder="$t('choose_an_organization')"
                  list-value-field="name"
                  :required="true"
                  v-model="formData.contextable_id"
                />
              </div>
            </div>
            <!-- end of Organization input -->

            <div class="mt-1 form-group">
              <div class="form-row">
                <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                  $t("save_as_done")
                }}</label>
                <div class="mt-2 col-sm-2">
                  <app-input
                    type="single-checkbox"
                    :list-value-field="$t(' ')"
                    v-model="formData.is_done_activity"
                  />
                </div>
              </div>
            </div>

            <div class="form-row p-2 mt-2">
              <div class="col-6 px-0">
                <button
                  type="button"
                  class="btn btn-light shadow"
                  @click.prevent="openModal()"
                >
                  {{ $t("add_details") }}
                </button>
              </div>
              <div class="col-6 px-0 d-flex justify-content-end">
                <button
                  type="button"
                  class="btn btn-secondary mr-2"
                  data-dismiss="modal"
                  @click="closeEventModal"
                >
                  {{ $t("cancel") }}
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click.prevent="addEvent"
                >
                  {{ $t("save") }}
                </button>
              </div>
            </div>
          </div>
          <div v-else>
            <!-- Activity done input -->
            <div class="form-row mt-2">
              <div class="col-sm-12 mb-2">
                <h4>
                  <b>#{{ currentActivity.databaseId }}</b> -
                  {{ currentActivity.title }}
                </h4>
              </div>
              <div class="col-sm-12">
                <app-input
                  type="single-checkbox"
                  :required="true"
                  :disabled="
                    activity_done ||
                    !$can('update_activities') ||
                    !$can('done_activities')
                  "
                  :list-value-field="
                    activity_done ? $t('done') : $t('mark_as_done')
                  "
                  v-model="activity_done"
                  @input="activityUpdate(currentActivity.databaseId)"
                />
              </div>
            </div>
            <!-- end of Activity done input -->

            <div class="form-row">
              <div class="col-12">
                <app-input
                  type="radio-buttons"
                  :list="activityTypeOption"
                  v-model="eventId"
                />
              </div>
            </div>
            <div class="form-row p-2">
              <div class="col-6">
                <label>{{ $t("start_time") }}</label>
                <div>{{ currentActivity.start }}</div>
              </div>
              <div class="col-6">
                <label>{{ $t("end_time") }}</label>
                <div>{{ currentActivity.end }}</div>
              </div>
            </div>
            <div class="form-row p-2 mt-2">
              <div class="col-6 px-0">
                <button
                  type="button"
                  class="btn btn-light text-left shadow"
                  @click.prevent="openForEdit()"
                >
                  {{ $t("edit_details") }}
                </button>
              </div>
              <div class="col-6 px-0 d-flex justify-content-end">
                <button
                  type="button"
                  class="btn btn-secondary mr-2"
                  data-dismiss="modal"
                  @click="closeEventModal"
                >
                  {{ $t("cancel") }}
                </button>
                <button
                  type="button"
                  class="btn btn-primary"
                  @click.prevent="updateEvent"
                >
                  {{ $t("update") }}
                </button>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin";
import { owner } from "@app/Mixins/Global/FilterMixins";
import { mapGetters } from "vuex";
import { api } from "@app/Helpers/api";
import { collect } from "@app/Helpers/Collection";
import _ from "lodash";
export default {
  name: "CalendarView",
  mixins: [FormMixin, owner],
  data() {
    return {
      isActivityModalActive: false,
      activity_done: null,
      doneActivityId: null,
      todoActivityId: null,
      statusId: null,
      isPreSetData: false,
      getSelectedDate: {},
      editData: {},
      activityModalId: "activity-modal",
      showAddEventModal: false,
      customModal: false,
      eventData: {},
      editedUrl: "",
      eventTitle: 1,
      activityTypeOption: [],
      typeList: [],
      activityData: {},
      formData: {
        title: "",
        type_of_activity: "",
        contextable_type: "",
        activity_type_id: null,
      },
      filterValues: {},
      options: {
        showSearch: true,
        showFilter: true,
        events: [],
        visibleRange: {},
        filters: [
          {
            title: this.$t("deal"),
            type: "dropdown-menu-filter",
            key: "context",
            initValue: 1,
            listValueField: "name",
            option: [
              {
                id: 1,
                name: "Any",
              },
              {
                id: 2,
                name: "Deal",
              },
              {
                id: 3,
                name: "Person",
              },
              {
                id: 4,
                name: "Organization",
              },
            ],
          },
          {
            title: this.$t("done_activity"),
            type: "radio",
            key: "done_activity",
            header: {
              title: "Do you want to see done activity?",
              description: "",
            },
            option: [
              {
                id: 1,
                name: "Show done activity",
              },
            ],
            listValueField: "name",
          },
          {
            title: this.$t("activity"),
            type: "checkbox",
            key: "activity",
            option: [],
          },
          {
            title: this.$t("Schedule"),
            type: "range-picker",
            key: "schedule",
            option: ["today", "thisMonth", "last7Days", "thisYear"],
          },
          {
            title: this.$t("owner"),
            type: "checkbox",
            key: "owner_is",
            option: [],
            permission: this.$can("manage_public_access") ? true : false,
          },
        ],
      },
      dataLoaded: false,
      activityId: new Set(),
      dealId: new Set(),
      personId: new Set(),
      orgId: new Set(),
      activityView: false,
      eventId: 1,
      currentActivity: {},
      errors: {},
    };
  },
  computed: {
    ...mapGetters({
      personList: "getPerson",
      ownerList: "getOwner",
      organizationList: "getOrganization",
      dealList: "getDeal",
    }),
    activityText() {
      let activeActivity = this.activityTypeOption.filter(
        (v) => v.id === this.eventTitle
      );
      return activeActivity[0];
    },
    showingFormat() {
      let dateFormat = this.$store.state.settings.dateFormat,
        timeFormat = this.$store.state.settings.timeFormat,
        format = `${dateFormat} ${timeFormat === 12 ? "h:mm A" : "HH:mm"}`;
      return format;
    },
  },
    created(){
        this.getStatusId();
    },
  mounted() {
    //get activity data and set in event data
      this.$store.dispatch("getOwner"),
      this.$store.dispatch("getPerson"),
      this.$store.dispatch("getOrganization"),
      this.$store.dispatch("getDeal"),
      this.setTypeActivity();
      this.setFilterOption();
  },
  directives: {
    "click-outside": {
      bind: function (el, binding, vNode) {
        if (typeof binding.value !== "function") {
          const compName = vNode.context.name;
          let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`;
          if (compName) {
            warn += `Found in component '${compName}'`;
          }

          console.warn(warn);
        }
        const bubble = binding.modifiers.bubble;
        const handler = (e) => {
          if (bubble || (!el.contains(e.target) && el !== e.target)) {
            binding.value(e);
          }
        };
        el.__vueClickOutside__ = handler;
        document.addEventListener("click", handler);
      },

      unbind: function (el, binding) {
        document.removeEventListener("click", el.__vueClickOutside__);
        el.__vueClickOutside__ = null;
      },
    },
  },
  methods: {
    getStatusId() {
      this.axiosGet(
        route("statuses.index", {
          _query: {
            type: "activity",
          },
        })
      ).then((res) => {
        this.todoActivityId = collect(res.data).where('name', '=', 'status_todo').first().id;
          this.doneActivityId = collect(res.data).where('name', '=', 'status_done').first().id;
      });
    },
    activityUpdate(id) {
      let updateActivity = this.activityTypeOption.filter(
        (v) => v.id == this.eventId
      )[0];
      if (this.activity_done) {
        this.editData.status_id = this.doneActivityId;
        this.editData.title = this.currentActivity.title;

        this.axiosPut({
          url: route("activities.update", { id: id }),
          data: this.editData,
        }).then((response) => {
          this.$toastr.s(response.data.message);
          this.editData = {};
          this.activity_done = null;
          this.closeEventModal();
          this.getActivityData(this.filterValues);
        });
      }
    },

    setTypeActivity() {
      this.typeList.push(
        {
          id: 1,
          title: this.$t("deal"),
        },
        {
          id: 2,
          title: this.$t("person"),
        },
        {
          id: 3,
          title: this.$t("organization"),
        }
      );
    },
    handleClick(arg) {
      this.customModal = true;
      this.isPreSetData = true;
      this.getSelectedDate = arg;
      this.activityView = false;
      setTimeout(() => {
        this.setCustomModalPos(this.eventData);
      }, 100);
      this.eventData = _.cloneDeep(arg);
    },
    selectedEventHandle(arg) {
      this.customModal = true;
      this.activityView = true;
      setTimeout(() => {
        let currentActivity = _.cloneDeep(
          this.options.events.filter(
            (v) => v.databaseId == this.eventData.event.extendedProps.databaseId
          )
        );

        this.eventId = currentActivity[0].activity_type_id;
        this.currentActivity = currentActivity[0];
        this.currentActivity.status_id = currentActivity[0].status_id;
        this.currentActivity.databaseId = this.eventData.event.extendedProps.databaseId;
        this.currentActivity.start = moment(this.eventData.event.start).format(
          this.showingFormat
        );
        this.currentActivity.end = moment(this.eventData.event.end).format(
          this.showingFormat
        );
        this.setCustomModalPos(this.eventData);
      }, 300);
      this.eventData = _.cloneDeep(arg);
    },
    setCustomModalPos(arg) {
      let customModalDom = document.getElementsByClassName("custom-modal")[0];
      let posLimit = arg.jsEvent.view.innerWidth / 2;
      this.activity_done =
        this.currentActivity.status_id == this.doneActivityId ? true : false;
      customModalDom.classList.remove("d-none");
      //custom modal width
      let w = 510,
        h = 200;
      if (arg.jsEvent.clientX > posLimit) {
        customModalDom.style.left = arg.jsEvent.clientX - w + "px";
      } else {
        customModalDom.style.left = arg.jsEvent.clientX + "px";
      }
      if (arg.jsEvent.clientY > window.innerHeight - 300) {
        customModalDom.style.top = arg.jsEvent.pageY - h + "px";
      } else {
        customModalDom.style.top = arg.jsEvent.pageY + "px";
      }
      // customModalDom.style.top = (arg.jsEvent.clientY -h) + "px";
      this.customModal = true;
    },
    // For close event modal after outside click
    closeEventModal() {
      let customModalDom = document.getElementsByClassName("custom-modal")[0];
      customModalDom ? customModalDom.classList.add("d-none") : false;
      this.customModal = false;
      this.formData = {};
      this.activity_done = null;
      this.currentActivity = {};
    },
    openForEdit() {
      this.editedUrl = route("activities.show", {
        id: this.currentActivity.databaseId,
      });
      this.openModal();
    },
    openModal() {
      this.isActivityModalActive = true;
      this.activityData = this.formData;
      this.closeEventModal();
      this.customModal = false;
      this.formData.activity_type_id = this.activityText.id;
    },

    closeModal() {
      this.isActivityModalActive = false;
      this.editedUrl = "";
      this.getSelectedDate = {};
      this.formData = {};
      this.getActivityData(this.filterValues);
      $("#activity-modal").modal("hide");
    },
    cancelModal() {
      this.isActivityModalActive = false;
      this.editedUrl = "";
      this.getSelectedDate = {};
      this.formData = {};
      $("#activity-modal").modal("hide");
    },
    addEvent() {
      if (this.formData.type_of_activity == 1) {
        this.formData.contextable_type = "App\\Models\\CRM\\Deal\\Deal";
      } else if (this.formData.type_of_activity == 2) {
        this.formData.contextable_type = "App\\Models\\CRM\\Person\\Person";
      } else {
        this.formData.contextable_type =
          "App\\Models\\CRM\\Organization\\Organization";
      }
      if (this.formData.is_done_activity) {
        this.statusId = this.doneActivityId;
      } else {
        this.statusId = this.todoActivityId;
      }

      let formData = {
        // add new event data
        title: this.formData.title,
        contextable_type: this.formData.contextable_type,
        contextable_id: this.formData.contextable_id,
        activity_type_id: this.activityText.id,
        started_at: moment(this.eventData.start).format("YYYY-MM-DD"),
        start_time: moment(this.eventData.start).format("HH:mm"),
        status_id: this.statusId,
        ended_at: moment(this.eventData.end).format("YYYY-MM-DD"),
        end_time: moment(this.eventData.end).format("HH:mm"),
      };

      this.submitFromFixin("post", route("activities.store"), formData);
    },
    afterError(response) {
      this.errors = response.data.errors;
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.getActivityData(this.filterValues);
      this.options.events.push({
        // add new event data
        title: this.formData.title,
        start: this.eventData.start,
        end: this.eventData.end,
      });
      $("#addEventModal").modal("hide");
      this.showAddEventModal = false;
      this.closeEventModal();
      this.formData = {};
    },
    updateEvent() {
      let updateActivity = this.activityTypeOption.filter(
        (v) => v.id == this.eventId
      )[0];
      this.axiosPatch({
        url: route("activities.update", {
          id: this.currentActivity.databaseId,
        }),
        data: {
          // add new event data
          title: this.currentActivity.title,
          activity_type_id: updateActivity.id,
        },
      })
        .then((res) => {
          this.$toastr.s(res.data.message);
          this.getActivityData(this.filterValues);
        })
        .catch((err) => this.$toastr.s(err.message));

      $("#addEventModal").modal("hide");
      this.showAddEventModal = false;
      this.closeEventModal();
    },
    getActivityData(values = {}) {
      this.dataLoaded = false;
      this.filterValues = values;
      if (values["schedule"]) {
        this.options.visibleRange = values.schedule;
        // this.options.def =
        // this.filterValues.schedule = JSON.stringify(values.schedule);
      }

      this.axiosGet(
        route("activities.index", {
          _query: { ...this.filterValues },
        })
      )
        .then((res) => {
          this.options.events = [];
          res.data.forEach((el) => {
            let arr = el.contextable_type
              ? el.contextable_type.split("\\")
              : [];
            let doneClassName =
              el.status_id == this.doneActivityId ? "done" : "todo";
            this.options.events.push({
              title: el.title,
              className: [el.activity_type.name.toLowerCase(), doneClassName],
              start: el.started_at + " " + el.start_time,
              end: el.ended_at + " " + el.end_time,
              contextableType: arr[arr.length - 1],
              activityType: el.activity_type.name,
              databaseId: el.id,
              status_id: el.status_id,
              activity_type_id: el.activity_type_id,
            });
          });
          this.dataLoaded = true;
        })
        .catch((er) => console.log(er.message));
    },
    getAllActivities() {
      this.axiosGet("activity_types.index").then((response) => {
        let activityType = this.options.filters.find(
          (elsement) => elsement.title === this.$t("activity")
        );
        activityType.option = response.data.data.map((activities) => {
          return {
            id: activities.id,
            value: activities.name,
          };
        });
      });
    },
    setFilterOption() {
      this.axiosGet(route("activity_types.index"))
        .then((res) => {
          res.data.data.forEach((el) => {
            if (!this.activityId.has(el.id)) {
              this.activityId.add(el.id);
              el.value = el.name;
              delete el.name;
              this.activityTypeOption.push(el);
            }
          });

          let activityType = this.options.filters.find(
            (elsement) => elsement.title === this.$t("activity")
          );
          activityType.option = this.activityTypeOption.map((v, i) => {
            return { id: v.id, value: v.value };
          });
        })
        .catch((er) => console.log(er.message));
    },
  },
};
</script>
<style>
.custom-modal {
  background: var(--default-card-bg) !important;
  position: absolute;
  border-radius: 5px;
  width: 510px;
  padding: 2rem;
  top: 50%;
  left: 50%;
  z-index: 1200;
  font-size: 12px;
  transition-duration: 300ms;
}

.custom-modal .radio-button-group .btn-group .btn span {
  font-size: 12px !important;
}

.calendar-view .fc-timeGrid-view .fc-widget-content {
  height: 80px !important;
}

.calendar-view .fc-prev-button.fc-button:hover,
.calendar-view .fc-next-button.fc-button:hover {
  background: transparent !important;
}

.calendar-view .fc-prev-button.fc-button,
.calendar-view .fc-next-button.fc-button {
  background: transparent !important;
  color: #919191 !important;
}
.calendar-view .fc-timegrid .fc-timegrid-slot {
  height: 150px !important;
}
</style>
