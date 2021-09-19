<template>
    <app-modal modal-id="deal-activity-modal" modal-size="large" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5  class="modal-title">{{selectedUrl ? $t("edit") : $t("add")}} {{$t('activity_lowercase')}}</h5>
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
            <form v-if="dataLoaded" ref="form" :data-url="selectedUrl ? selectedUrl : route('activities.store')">
                <div class="form-group">
                    <div class="form-row">
                        <label class="col-2 mb-0 d-flex align-items-center">
                            {{ $t('activity') }}
                        </label>
                        <div class="col-10">
                            <app-input type="radio-buttons"
                                       list-value-field='name'
                                       :list="activityTypeList"
                                       v-model="activity.activity_type_id"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="col-2 mb-0 d-flex align-items-center">
                            {{ $t('title') }}
                        </label>
                        <div class="col-10">
                            <app-input type="text"
                                       :placeholder="$t('enter_title')"
                                       v-model="activity.title"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="mb-0 col-sm-2">{{ $t('description') }}</label>
                        <div class="col-sm-10">
                            <app-input
                                    type="textarea"
                                    :text-area-rows="5"
                                    :placeholder="$t('description_here')"
                                    v-model="activity.description"
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
                            <div class="d-flex align-items-center flex-column flex-sm-row">
                                <div class="row">
                                    <div class="col-lg-7 pr-lg-0">
                                        <app-input
                                                type="date"
                                                :placeholder="$t('start_date')"
                                                v-model="formData.started_at"
                                                @input="setEndDateAsStartDate"
                                        />
                                    </div>
                                    <div class="col-lg-5 pl-lg-0">
                                        <app-input
                                                type="time"
                                                :placeholder="$t('start_time')"
                                                v-model="activity.start_time"
                                                @input="setStart($event)"
                                        />
                                    </div>
                                </div>
                                <div
                                        class="d-flex align-items-center justify-content-center schedule-divider"
                                />
                                <div class="row">
                                    <div class="col-lg-7 pr-lg-0">
                                        <app-input
                                                type="date"
                                                :min-date="formData.started_at"
                                                :placeholder="$t('end_date')"
                                                v-model="formData.ended_at"
                                        />
                                    </div>
                                    <div class="col-lg-5 pl-lg-0">
                                        <app-input
                                                type="time"
                                                :placeholder="$t('end_time')"
                                                v-model="activity.end_time"
                                        />
                                    </div>
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
                        <label class="mb-0 col-sm-2 d-flex align-items-center">{{ $t('collaborators') }}</label>
                        <div class="col-sm-10">

                            <app-input type="multi-select"
                                       list-value-field="full_name"
                                       :list="ownerList"
                                       :required="true"
                                       v-model="formData.owner_id"
                                       :is-animated-dropdown="true"/>
                        </div>
                    </div>
                </div>
                <!-- end of Collaborators input -->
            </form>
            <app-overlay-loader v-else />
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="submitData">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                <template v-if="!loading">{{ $t('save') }}</template>
            </button>
        </template>
    </app-modal>
</template>

<script>
    import {FormMixin} from "@core/mixins/form/FormMixin";
    import { mapGetters } from 'vuex'
    import index from "../../../../Store";
    import {
        time_format,
        formatted_date,
        onlyTime,
        formatted_time,
    } from "@app/Helpers/helpers";

    export default {
        name: "DealActivityModal",
        props:['activity'],
        mixins: [FormMixin],
        data(){
          return{
              route,
            dataLoaded: false,
            loading: false,
            activityTypeList: [],
            formData:{
              person_id: [],
              owner_id: [],
            },
          }
        },
        computed:{
            ...mapGetters({
                personList: "getPerson",
                ownerList: "getOwner",
            })
        },
        methods:{
            setEndDateAsStartDate() {
                let s = moment(moment(this.formData.started_at).format("YYYY-MM-DD")),
                    e = moment(moment(this.formData.ended_at).format("YYYY-MM-DD")),
                    diff = e.diff(s, "days");
                if (diff < 0){
                    this.formData.ended_at = this.formData.started_at;
                }
            },
            setStart(v) {
                if (
                    this.formData.started_at.toDateString() ==
                    this.formData.ended_at.toDateString()
                ) {
                    this.activity.end_time =
                        this.convertTime12to24(v) > this.convertTime12to24(this.activity.end_time)
                            ? this.activity.start_time
                            : this.activity.end_time;
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
            submitData(){
            this.loading = true;
            const activityData = this.formData;
            activityData.activity_type_id = this.activity.activity_type_id;
            activityData.title = this.activity.title;
            activityData.description = this.activity.description;
            activityData.started_at = moment(this.formData.started_at).format('YYYY-MM-DD');
            activityData.ended_at = moment(this.formData.ended_at).format('YYYY-MM-DD');
            activityData.start_time = this.convertTime12to24(this.activity.start_time);
            activityData.end_time = this.convertTime12to24(this.activity.end_time);
            activityData.contextable_id = this.activity.contextable_id;
            activityData.contextable_type = this.activity.contextable_type;
            activityData.person_id = this.formData.person_id;
            activityData.owner_id = this.formData.owner_id;
             this.axiosPatch({
                 url:route('activities.update', this.activity.id),
                 data:activityData
             }).then(response => {
                 this.$toastr.s(response.data.message);
                 this.loading = false;
                 this.$hub.$emit('activity-list', `status=11`);
                 this.closeModal();
             }).catch((error) => console.log(error))
            },
            closeModal(value){
                this.$emit("close-modal", value);
            },
            afterSuccessFromGetEditData(response) {
                let d = new Date(
                    response.data.started_at + " " + response.data.start_time
                );
                this.formData.started_at = response.data.started_at
                    ? new Date(response.data.started_at)
                    : "";
                this.activity.start_time = response.data.start_time
                    ? moment(d).format(`${time_format()}`)
                    : "";
                let e = new Date(
                    response.data.ended_at + " " + response.data.end_time
                );
                this.formData.ended_at = response.data.ended_at
                    ? new Date(response.data.ended_at)
                    : "";
                this.activity.end_time = response.data.end_time
                    ? moment(e).format(`${time_format()}`)
                    : "";
                response.data.collaborators.map((v, i) => {
                    this.formData.owner_id.push(v.id)
                });
                response.data.participants.map((v, i) => {
                    this.formData.person_id.push(v.id)
                });
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
                return this.axiosGet(route('activity_types.index'))
                    .then((response) => {
                        this.activityTypeList = this.collection(response.data.data).shaper(
                            "name"
                        );
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
        },
        mounted(){
            if (this.activity) {
                this.formData.started_at = this.activity.started_at
                    ? new Date(this.activity.started_at)
                    : "";
                this.formData.ended_at = this.activity.ended_at
                    ? new Date(this.activity.ended_at)
                    : "";
            }

            this.pipeline([this.getActivityType()])
                .then(() => {
                    this.dataLoaded = true;
                })
                .catch((err) => console.log(err));

        },

    }
</script>

<style scoped>

</style>
