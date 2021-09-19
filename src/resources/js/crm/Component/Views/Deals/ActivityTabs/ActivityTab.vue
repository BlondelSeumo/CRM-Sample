<template>
  <form class="mb-0" ref="form" :data-url="route('deal.sync-activities', {id: props.id})">
    <div class="mb-primary">
      <div class="form-group">
        <div class="form-row">
          <label class="mb-0 col-sm-2 d-flex align-items-center">
            {{ $t('activity') }}
          </label>
          <div class="col-sm-10">
            <app-input type="radio-buttons"
                       :list="activityTypeList"
                        v-model="activity_type_id"/>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="form-row">
          <label class="mb-0 col-sm-2 d-flex align-items-center">
            {{ $t('title') }}
          </label>
          <div class="col-sm-10">
            <app-input type="text"
                       :required="true"
                       :placeholder="$t('enter_title')"
                       v-model="formData.title"/>
          </div>
        </div>
      </div>

      <div class="form-group">
        <div class="form-row">
          <label class="mb-0 pt-2 col-sm-2">{{ $t('description') }}</label>
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
          <label class="mb-0 col-sm-2 d-flex align-items-center">
            {{ $t('set_schedule') }}
          </label>
          <div class="col-sm-10">
              <div class="row mb-1">
                <div class="col-lg-7 pr-lg-0">
                  <app-input type="date"
                             :placeholder="$t('start_date')"
                             v-model="formData.started_at"
                             @input="setEndDateAsStartDate"/>
                </div>
                <div class="col-lg-5 pl-lg-0 time-picker-dd-pos">
                  <app-input type="time"
                             :placeholder="$t('start_time')"
                             v-model="formData.start_time"
                             @input="setStart($event)"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-lg-7 pr-lg-0">
                  <app-input type="date"
                             :min-date="formData.started_at"
                             :placeholder="$t('end_date')"
                             v-model="formData.ended_at"/>
                </div>
                <div class="col-lg-5 pl-lg-0 time-picker-dd-pos">
                  <app-input type="time"
                             v-model="formData.end_time"
                  />
                </div>
              </div>
          </div>
        </div>
      </div>
      <!-- Participants input -->
      <div class="form-group">
        <div class="form-row">
          <label class="mb-0 col-sm-2 d-flex align-items-center">{{ $t('participants') }}</label>
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
          <label class="mb-0 col-sm-2 d-flex align-items-center text-break">{{ $t('collaborators') }}</label>
          <div class="col-sm-10">

            <app-input type="multi-select"
                       list-value-field="full_name"
                       :list="ownerList"
                       v-model="formData.owner_id"
                       :is-animated-dropdown="true"/>
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

    </div>
    <div class="pt-primary px-primary border-top mx-minus-primary">
      <button type="button" :disabled="loading" class="btn btn-primary" @click.prevent="submitData">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
        <template v-if="!loading" >{{ $t('save') }}</template>
      </button>
      <button type="button" class="btn btn-secondary" @click="initState">{{ $t('clear') }}</button>
    </div>
  </form>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin";
import {mapGetters} from 'vuex'
import { api } from "@app/Helpers/api";
import { collect } from "@app/Helpers/Collection";
import moment from 'moment';
import {
  time_format,
  formatted_date,
  onlyTime,
  formatted_time,
} from "@app/Helpers/helpers";

export default {
  name: "ActivityTab",
  props: ['props'],
  mixins: [FormMixin],
  data() {
    return {
        route,
      loading: false,
      formData: {
        title: '',
        description:'',
        started_at: new Date(), ended_at: new Date(),
        person_id: [],
        owner_id: [],
        start_time:moment(new Date()).subtract("15", "minutes").format(`${time_format()}`),
        end_time:moment(new Date()).format(`${time_format()}`),
        doneActivityId:null,
      },
      activity_type_id: null,
      activityTypeList:[]
    }
  },

  computed: {
    ...mapGetters({
      personList: "getPerson",
      ownerList: "getOwner",
        statusList: 'getActivityStatus'
    })
  },
  methods: {
    setStart(v) {
        this.formData.start_time = v;
        if (
            this.formData.started_at.toDateString() ==
            this.formData.ended_at.toDateString()
        ) {
            this.formData.end_time =
                this.convertTime12to24(v) > this.convertTime12to24(this.formData.end_time)
                    ? this.formData.start_time
                    : this.formData.end_time;
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
    initState(){
      this.formData= {
        title: '',
        description:'',
        started_at: new Date(),
        ended_at: new Date(),
        person_id: [],
        owner_id: [],
        start_time:moment(new Date()).format(`${time_format()}`),
        end_time:moment(new Date()).format(`${time_format()}`)
      }
      this.activity_type_id = this.activityTypeList.length ? collect(this.activityTypeList).first().id : null;
      this.loading = false;
    },
    setEndDateAsStartDate() {

      // please no need to change this formation of date,
      // it is just use for check logic

      let s = moment(moment(this.formData.started_at).format("YYYY-MM-DD")),
        e = moment(moment(this.formData.ended_at).format("YYYY-MM-DD")),
        diff = e.diff(s, "days");

      if (diff < 0){
        this.formData.ended_at = this.formData.started_at;
      }
    },
    getActivityType() {
      return this.axiosGet(route('activity_types.index'))
          .then((response) => {
            this.activityTypeList = this.collection(response.data.data).shaper(
                "name"
            );
            this.activity_type_id = this.activityTypeList.length ? collect(this.activityTypeList).first().id : null;
          })
          .catch((error) => {
            console.log(error);
          });
    },
    beforeSubmit() {
        this.loading = true;
    },
    submitData() {
      if (this.props.status){
        let activity = this.formData;

        // format date as our database
        activity.started_at = moment(this.formData.started_at).format('YYYY-MM-DD');
        activity.ended_at = moment(this.formData.ended_at).format('YYYY-MM-DD');
        activity.person_id = this.formData.person_id;
        activity.owner_id = this.formData.owner_id;
        activity.activity_type_id = this.activity_type_id;
        activity.start_time = this.convertTime12to24(this.formData.start_time);
        activity.end_time = this.convertTime12to24(this.formData.end_time);
        if (this.formData.activity_done) {
          activity.status_id = collect(this.statusList).where('name', '=', 'status_done').first().id;
        }
        this.save(activity);
      } else {
        this.$toastr.i(this.$t('please_Reopen_deal_before_add_activity'));
      }

    },
    afterError(response) {
      this.loading = false;
      this.$toastr.e(response.data.message);
    },

    afterSuccess(response) {
        let todoStatusId = collect(this.statusList).where('name', '=', 'status_todo').first().id;
        let doneStatusId = collect(this.statusList).where('name', '=', 'status_done').first().id;
        let status = this.formData.activity_done ? doneStatusId : todoStatusId;
      this.initState();
      this.$toastr.s(response.data.message);
      this.loading = false;
      this.$hub.$emit('activity-list', `status=${status}`);
    },
  },
  mounted() {
      this.$store.dispatch("getActivityStatus");
      this.getActivityType();
  }
}
</script>
