<template>
  <app-modal
    modal-id="activity-modal"
    modal-size="large"
    modal-alignment="top"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title">
        {{ selectedUrl ? $t("edit_activity") : $t("add_activity") }}
      </h5>

      <button
        type="button"
        class="close outline-none"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <form
        ref="form"
        :data-url="selectedUrl ? selectedUrl : route(`activities.store`)"
        v-if="dataLoaded"
      >
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("activity")
            }}</label>
            <div class="col-sm-10">
              <app-input
                type="radio-buttons"
                :required="true"
                :list="activityTypeList"
                v-model="activityId"
              />
              <small class="text-danger" v-if="errors.activity_type_id">{{
                errors.activity_type_id[0]
              }}</small>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("title")
            }}</label>
            <div class="col-sm-10">
              <app-input
                type="text"
                :required="true"
                :placeholder="$t('title')"
                v-model="formData.title"
              />
              <small class="text-danger" v-if="errors.title">{{
                errors.title[0]
              }}</small>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2">{{ $t("description") }}</label>
            <div class="col-sm-10">
              <app-input
                type="textarea"
                :text-area-rows="5"
                :placeholder="$t('description_here')"
                v-model="formData.description"
              />
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="form-row">
            <label class="col-sm-2 mb-0 d-flex align-items-center">{{
              $t("set_schedule")
            }}</label>
            <div class="col-sm-10">
              <div class="row mb-1">
                <div class="col-lg-7 pr-lg-0">
                  <app-input
                    type="date"
                    :popover-position="'top-start'"
                    :placeholder="$t('start_date')"
                    v-model="formData.started_at"
                    @input="setEndDateAsStartDate"
                  />
                  <small class="text-danger" v-if="errors.started_at">{{
                    errors.started_at[0]
                  }}</small>
                </div>
                <div class="col-lg-5 pl-lg-0 time-picker-dd-pos">
                  <app-input
                    type="time"
                    :placeholder="$t('start_time')"
                    v-model="startTime"
                    @input="setStart($event)"
                  />
                  <small class="text-danger" v-if="errors.start_time">{{
                    errors.start_time[0]
                  }}</small>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-7 pr-lg-0">
                  <app-input
                    type="date"
                    :popover-position="'top-start'"
                    :min-date="formData.started_at"
                    :placeholder="$t('end_date')"
                    v-model="formData.ended_at"
                  />
                  <small class="text-danger" v-if="errors.ended_at">{{
                    errors.ended_at[0]
                  }}</small>
                </div>
                <div class="col-lg-5 pl-lg-0 time-picker-dd-pos">
                  <app-input
                    type="time"
                    :placeholder="$t('end_time')"
                    v-model="endTime"
                    @input="setEnd($event)"
                  />
                  <small class="text-danger" v-if="errors.end_time">{{
                    errors.end_time[0]
                  }}</small>
                </div>
              </div>
              <div class="mt-2 schedule-default-time-slot">
                <button
                  type="button"
                  class="btn btn-sm btn-light btn-with-shadow font-size-90 rounded-pill"
                  :class="{ active: activePreset == 15 }"
                  @click="setPreset(15)"
                >
                  00:15 H
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-light btn-with-shadow font-size-90 rounded-pill"
                  :class="{ active: activePreset == 30 }"
                  @click="setPreset(30)"
                >
                  00:30 H
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-light btn-with-shadow font-size-90 rounded-pill"
                  :class="{ active: activePreset == 45 }"
                  @click="setPreset(45)"
                >
                  00:45 H
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-light btn-with-shadow font-size-90 rounded-pill"
                  :class="{ active: activePreset == 600 }"
                  @click="setPreset(600)"
                >
                  10:00 H
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Activity change input -->
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("activity_type")
            }}</label>
            <div class="col-sm-10">
              <app-input
                type="select"
                list-value-field="title"
                :list="setTypeActivity"
                :placeholder="$t('choose_your_activity_type')"
                :required="true"
                v-model="formData.type_of_activity"
                @input="activityTypeChanged"
              />
            </div>
          </div>
        </div>
        <!-- end of Activity change input -->

        <!-- deal input -->
        <div class="form-group" v-if="formData.type_of_activity == '1'">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("deal")
            }}</label>
            <div class="col-sm-10">
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
          <!-- deal media object input -->
          <div class="form-group mt-2">
            <div class="form-row">
              <label class="mb-0 col-sm-2 d-flex align-items-center"></label>
              <template v-if="formData.contextable_id">
                <div class="col-sm-5">
                  <app-media-object
                    :title="dealChange.owner.full_name"
                    :mediaTitle="dealChange.owner.full_name"
                    :mediaSubtitle="'Owner'"
                    :imgUrl="
                      dealChange.owner.profile_picture
                        ? urlGenerator(dealChange.owner.profile_picture.path)
                        : dealChange.owner.profile_picture
                    "
                  />
                </div>
                <div class="col-sm-5" v-if="dealChange.contextable">
                  <app-media-object
                    :title="dealChange.contextable.name"
                    :mediaTitle="dealChange.contextable.name"
                    :mediaSubtitle="dealChange.contextable.contact_type['name']"
                    :imgUrl="
                      dealChange.contextable.profile_picture
                        ? urlGenerator(
                            dealChange.contextable.profile_picture.path
                          )
                        : urlGenerator(`/images/${leadType}.png`)
                    "
                  />
                </div>
                <div class="pt-4" v-else>
                  <p class="text-muted font-size-90 mb-2">
                    {{ $t("no_lead_added") }}
                  </p>
                </div>
              </template>
            </div>
          </div>
          <!-- end of deal media object input -->
        </div>
        <!-- end of deal input -->

        <!-- Person input -->
        <div class="form-group" v-else-if="formData.type_of_activity == '2'">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("person")
            }}</label>
            <div class="col-sm-10">
              <app-input
                type="search-select"
                :list="personList"
                :placeholder="$t('choose_a_person')"
                :required="true"
                list-value-field="name"
                v-model="formData.contextable_id"
              />
            </div>
          </div>
        </div>
        <!-- end of Person input -->

        <!-- Organization input -->
        <div class="form-group" v-else-if="formData.type_of_activity == '3'">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("organization")
            }}</label>
            <div class="col-sm-10">
              <app-input
                type="search-select"
                :list="organizationList"
                :placeholder="$t('choose_an_organization')"
                :required="true"
                list-value-field="name"
                v-model="formData.contextable_id"
              />
            </div>
          </div>
        </div>
        <!-- end of Organization input -->

        <!-- Participants input -->
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("participants")
            }}</label>
            <div class="col-sm-10">
              <app-input
                type="multi-select"
                list-value-field="name"
                :list="personList"
                v-model="formData.person_id"
                :is-animated-dropdown="true"
              />
            </div>
          </div>
        </div>
        <!-- end of Participants input -->

        <!-- Collaborators input -->
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("collaborators")
            }}</label>
            <div class="col-sm-10">
              <app-input
                type="multi-select"
                :list="ownerList"
                list-value-field="full_name"
                v-model="formData.owner_id"
                :is-animated-dropdown="true"
              />
            </div>
          </div>
        </div>
        <!-- end of Collaborators input -->
        <div class="form-group">
          <div class="form-row">
            <label class="mb-0 col-sm-2 d-flex align-items-center">{{
              $t("save_as_done")
            }}</label>
            <div class="mt-2 col-sm-10">
              <app-input
                type="single-checkbox"
                :list-value-field="$t(' ')"
                v-model="formData.activity_done"
              />
            </div>
          </div>
        </div>
      </form>
      <app-overlay-loader v-else />
    </template>
    <template slot="footer">
      <button
        type="button"
        class="btn btn-secondary mr-2"
        data-dismiss="modal"
        @click.prevent="closeModal"
      >
        {{ $t("cancel") }}
      </button>
      <button type="button" class="btn btn-primary" @click.prevent="submit">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
        <template v-if="!loading">{{ $t("save") }}</template>
      </button>
    </template>
  </app-modal>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin";
import { collect } from "@app/Helpers/Collection";
import { mapGetters } from "vuex";
import moment from "moment";
import {
  formatted_date,
  formatted_time,
  time_format,
  urlGenerator,
} from "@app/Helpers/helpers";

export default {
  name: "ActivityModal",
  mixins: [FormMixin],
  props: {
    tableId: String,
    previousData: Boolean,
    setDate: Object,
    selectData: Object,
  },
  data() {
    return {
      route,
      urlGenerator,
      formData: {
        activity_type: null,
        title: "",
        description: "",
        started_at: new Date(),
        ended_at: new Date(),
        person_id: [],
        owner_id: [],
        type_of_activity: null,
        contextable_id: null,
      },
      addEditData: {},
      activityTypeList: [],
      errors: {},
      statusList: [],
      dataLoaded: false,
      loading: false,
      endTime: moment(new Date()).format(`${time_format()}`),
      startTime: moment(new Date())
        .subtract("15", "minutes")
        .format(`${time_format()}`),
      activityId: 1,
      activePreset: 15,
    };
  },
  computed: {
    ...mapGetters({
      ownerList: "getOwner",
      organizationList: "getOrganization",
      personList: "getPerson",
      dealList: "getDeal",
      activityStatusList: "getActivityStatus",
    }),

    dealChange() {
      return this.dealList.find(
        (deal) => deal.id == this.formData.contextable_id
      );
    },
    leadType() {
      return this.dealChange.contextable_type ==
        "App\\Models\\CRM\\Person\\Person"
        ? "person"
        : "org";
    },

    setTypeActivity() {
      return [
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
        },
      ];
    },
  },
    created() {
        this.$store.dispatch("getActivityStatus");
    },
    mounted() {
    if (this.previousData) {
      this.formData.type_of_activity = Number(this.selectData.type_of_activity);
      this.activityId = this.selectData.activity_type_id
        ? Number(this.selectData.activity_type_id)
        : this.activityId;
      this.formData.contextable_id = this.selectData.contextable_id;
      this.formData.title = this.selectData.title;
      this.formData.activity_done = this.selectData.is_done_activity;
      this.dateFormate();
    }

    this.pipeline([this.getActivityType()])
      .then(() => {
        this.dataLoaded = true;
      })
      .catch((err) => console.log(err));

    //for setting up data if update action fired
  },
  methods: {
    setEndDateAsStartDate() {
      // please no need to change this formation of date,
      // it is just use for check logic

      let s = moment(moment(this.formData.started_at).format("YYYY-MM-DD")),
        e = moment(moment(this.formData.ended_at).format("YYYY-MM-DD")),
        diff = e.diff(s, "days");

      if (this.dataLoaded && diff < 0) {
        this.formData.ended_at = this.formData.started_at;
      }
    },

    activityTypeChanged() {
      this.formData.contextable_id = null;
    },
    dateFormate() {
      // add new from calender view
      if (this.setDate) {
        this.activePreset = null;
        this.formData.started_at = new Date(this.setDate.start);
        this.formData.ended_at = new Date(this.setDate.end);
        this.endTime = moment(new Date(this.setDate.end)).format(
          `${time_format()}`
        );
        this.startTime = moment(new Date(this.setDate.start)).format(
          `${time_format()}`
        );
      }
    },
    setStart(v) {
      this.startTime = v;
      this.formData.start_time = v;
      this.activePreset = null;
      if (this.formData.started_at && this.formData.ended_at) {
        if (
          this.formData.started_at.toDateString() ==
          this.formData.ended_at.toDateString()
        ) {
          this.endTime =
            this.convertTime12to24(v) > this.convertTime12to24(this.endTime)
              ? this.startTime
              : this.endTime;
        }
      }
    },
    convertTime12to24(time12h) {
      if (formatted_time() == 24) {
        return time12h;
      }
      const [time, modifier] = time12h.split(" ");

      let [hours, minutes] = time.split(":");

      if (hours === "12") {
        hours = "00";
      }

      if (modifier === "PM") {
        hours = parseInt(hours, 10) + 12;
      }

      return `${hours}:${minutes}`;
    },
    setEnd(v) {
      this.endTime = v;
      this.formData.end_time = v;
      this.activePreset = null;
    },
    setPreset(diff) {
      this.formData.ended_at = this.formData.ended_at
        ? this.formData.ended_at
        : new Date();
      this.endTime = this.endTime
        ? this.endTime
        : moment(new Date()).format(`${time_format()}`);
      let dateTimeString =
        moment(this.formData.ended_at).format(`${formatted_date()}`) +
        " " +
        this.endTime;
      let formattedDate = moment(
        dateTimeString,
        `${formatted_date()} ${time_format()}`
      ).subtract(diff, "minutes");
      this.startTime = formattedDate.format(`${time_format()}`);
      this.formData.started_at = new Date(formattedDate);
      this.activePreset = diff;
    },
      afterSuccessFromGetEditData(response) {
          this.formData.activity_done =
              response.data.status.name == 'status_done' ? true : false;
          this.activePreset = null;
          this.formData.contextable_id = response.data.contextable_id;
          this.formData.description = response.data.description;
          if (response.data.contextable_type) {
              let arr = response.data.contextable_type.split("\\");
              if (arr[arr.length - 1] == "Deal") {
                  this.formData.type_of_activity = 1;
              } else if (arr[arr.length - 1] == "Organization") {
                  this.formData.type_of_activity = 3;
              } else if (arr[arr.length - 1] == "Person") {
                  this.formData.type_of_activity = 2;
              }
          }
          this.formData.activity_type_id = response.data.activity_type.id;
          this.formData.title = response.data.title;
          this.activityId = response.data.activity_type.id;
          this.formData.owner_id =
              response.data.collaborators.length > 0
                  ? collect(response.data.collaborators).pluck("id")
                  : [];
          this.formData.person_id =
              response.data.participants.length > 0
                  ? collect(response.data.participants).pluck("id")
                  : [];

          let d = new Date(
              response.data.started_at + " " + response.data.start_time
          );
          this.formData.started_at = response.data.started_at
              ? new Date(response.data.started_at)
              : "";
          this.startTime = response.data.start_time
              ? moment(d).format(`${time_format()}`)
              : "";

          let e = new Date(
              response.data.ended_at + " " + response.data.end_time
          );
          this.formData.ended_at = response.data.ended_at
              ? new Date(response.data.ended_at)
              : "";
          this.endTime = response.data.end_time
              ? moment(e).format(`${time_format()}`)
              : "";
          this.dataLoaded = true;
      },
    beforeSubmit() {
      this.loading = true;
    },
    submit() {
      this.addEditData.title = this.formData.title;
      this.addEditData.activity_type_id = this.activityId;
      this.addEditData.description = this.formData.description;
      this.addEditData.contextable_type = this.getContextableType();
      this.addEditData.contextable_id = this.formData.contextable_id;
      this.addEditData.started_at = moment(this.formData.started_at).format(
        "YYYY-MM-DD"
      );
      this.addEditData.start_time = this.convertTime12to24(this.startTime);
      this.addEditData.ended_at = moment(this.formData.ended_at).format(
        "YYYY-MM-DD"
      );
      this.addEditData.end_time = this.convertTime12to24(this.endTime);
      this.addEditData.person_id = this.formData.person_id;
      this.addEditData.owner_id = this.formData.owner_id;

        this.addEditData.status_id = this.formData.activity_done
            ? collect(this.activityStatusList).where('name', '=', 'status_done').first().id
            : collect(this.activityStatusList).where('name', '=', 'status_todo').first().id;

      if (this.addEditData.started_at == this.addEditData.ended_at) {
        this.addEditData.start_time > this.addEditData.end_time
          ? this.$toastr.e("End time should be over than start time")
          : this.save(this.addEditData);
      } else {
        this.save(this.addEditData);
      }
    },
    pipeline(funcArr) {
      funcArr.forEach((obj) => {
        if (Promise.resolve(obj) !== obj) {
          throw new Error(
            "Expects all methods are passed in parameter array should return Promise"
          );
        }
      });
      return Promise.all(funcArr);
    },
    getActivityType() {
      return this.axiosGet(route(`activity_types.index`))
        .then((response) => {
          this.activityTypeList = this.collection(response.data.data).shaper(
            "name"
          );
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getContextableType() {
      if (this.formData.type_of_activity == 1) {
        return "App\\Models\\CRM\\Deal\\Deal";
      } else if (this.formData.type_of_activity == 2) {
        return "App\\Models\\CRM\\Person\\Person";
      } else {
        return "App\\Models\\CRM\\Organization\\Organization";
      }
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit("reload-" + this.tableId);
      this.$emit("save");
      this.closeModal();
    },
    afterFinalResponse() {
      this.loading = false;
    },
    closeModal() {
      this.$emit("cancel-modal");
      this.$emit("close-modal");
    },
  },
};
</script>

<style scoped lang="scss">
.schedule-default-time-slot {
  .active {
    color: #4466f2 !important;
    background-color: var(--base-color) !important;
    border-color: var(--default-border-color) !important;
  }
}
</style>
