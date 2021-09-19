<template>
    <div>
        <app-overlay-loader v-if="!dataLoaded"/>
        <div v-else :class="{ 'deal-closed': !dealStatus }" class="content-wrapper">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <app-breadcrumb
                        :button="{ label: $t('back'), url: route('deals.lists') }"
                        :directory="[$t('deals'), formData.title]"
                        :icon="'clipboard'"
                        :page-title="formData.title"
                    />
                </div>
                <div class="col-sm-12 col-md-6" v-if="$can('proposal_send_deal_person')">
                    <div v-if="dealStatus" class="float-md-right mb-3 mb-sm-3 mb-md-0">
                        <button
                            class="btn btn-primary btn-with-shadow"
                            data-toggle="modal"
                            type="button"
                            @click="openSendProposalModal()"
                        >
                            {{ $t("send_proposal") }}
                        </button>
                    </div>
                </div>
            </div>

            <!--        deal contact person-->

            <deal-contact-person
                :dealPerson="formData"
                :lead-type="formData.lead_type == 1 ? 'person' : 'org'"
                @update-request="dealUpdateRequest"
            />

            <div class="row">
                <div class="col-xl-4 mb-primary">
                    <app-deal-detail
                        v-if="dataLoaded"
                        :dealDetails="formData"
                        :ownerList="ownerList"
                        :statusList="statusList"
                        :organizationList="organizationList"
                        :personList="personList"
                        :component-type="formData.lead_type == 1 ? 'persons' : 'organizations'"
                        @update-request="dealUpdateRequest"
                    />
                    <!--Deal details end-->

                    <!--Contact info start-->

                    <app-details-contact-info
                        v-if="dataLoaded"
                        :contact-info-data="formData.contact_person[0]"
                        :contact-info-sync-url="formData.contact_person.length ? route('persons.sync-contact', {id: formData.contact_person[0].id}) : ''"
                        :person-id="formData.contact_person.length ? formData.contact_person[0].id : null"
                        :status-check="dealStatus"
                        @update-request="dealUpdateRequest"
                    />

                    <!--Contact info end-->

                    <details-page-custom-field
                        v-if="dataLoaded && isComponent"
                        :status-check="dealStatus"
                        :component-type="'deal'"
                        :form-data="formData"
                        :update-url="route('deals.update', {id: formData.id})"
                        @update-request="dealUpdateRequest"
                    />

                    <!--Tags start-->
                    <details-tag-manager
                        :post-url="route('deal.attach-tag', {id: formData.id})"
                        :taggable-id="formData.id"
                        class="mb-primary"
                        :tagData="formData.tags"
                    />
                    <!--Tags end-->

                    <app-follower-details
                        v-if="dataLoaded"
                        :follower-data="formData"
                        :follower-sync-url="route('deal.sync-followers', {id: formData.id})"
                        :get-follower-url="route('deal.get-followers', {id: formData.id})"
                        :people-list="personList"
                        :permission-check="syncPermission"
                        :status-check="dealStatus"
                        class="mb-primary"
                        @update-request="dealUpdateRequest"/>
                    <!--Followers info end-->

                    <app-deal-participation-info
                        v-if="dataLoaded && Object.values(groupByParticipant).length > 0"
                        :activityParticipations="groupByParticipant"
                        :participantsDeal="formData"
                    />
                    <!--Participation info end-->

                    <app-deal-activity-collaborator
                        v-if="dataLoaded && Object.values(groupByCollaborator).length > 0"
                        :activityCollaborators="groupByCollaborator"
                        :collaboratorsDeal="formData"
                    />

                </div>
                <div class="col-xl-8">
                    <app-tab v-if="appTapShow" :tabs="todoActivityTab" class="mb-primary" type="horizontal"/>

                    <app-common-activity-show
                        :activity-status="activityStatusList"
                        v-if="isComponent && activityStatusList.length"
                        :Data="formData"
                        :activity-filter-url="route('deal.view-activities', {id: formData.id})"
                        :edit-url="selectedUrl"
                        :file-filter-url="route('deal.get-file', {id: formData.id})"
                        :note-filter-url="route('deal.get-note', {id: formData.id})"
                        component-type="deal"
                        @open-activity="editActivity"
                        @open-note-modal="editNote"
                        @update-data="groupByCollaboratorParticipant"
                    />

                </div>
            </div>

            <app-check-email-deliver v-if="isCheckMailModalActive"
                                     :header-title="$t('send_proposal')"
                                     @close-modal="closeModalCheckMail"/>
            <!--Send Proposal Modal-->
            <deal-send-proposal-modal
                v-if="isSendProposalModalActive"
                :deal-data="formData"
                :template-list="templateList"
                @close-modal="closeSendProposalModal"
            />

            <app-deal-activities-modal
                v-if="dealActivitiesModal"
                :activity="rowData"
                :selected-url="editedUrl"
                @close-modal="closeDealActivitiesModal"
            />

            <app-common-note-modal
                v-if="dealNoteModal"
                :note="noteRowData"
                @close-modal="closeDealNoteModal"
            />
        </div>
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
import {formatDateToLocal, onlyTime, onlyTimeFromTime, textTruncate} from "@app/Helpers/helpers";
import {mapGetters} from "vuex";
import moment from "moment";

export default {
    props: ["selectedUrl"],
    name: "DealDetails",
    mixins: [FormMixin],
    data() {
        return {
            textTruncate,
            onlyTimeFromTime,
            route,
            isComponent: true,
            preLoader: false,
            isfileNoteFilter: true,
            dataLoaded: false,
            formData: {},
            activitiesList: [],
            editedUrl: "",
            today: {dateOnly: moment(new Date()).format("YYYY-MM-DD")},
            endTime: {},
            isSendProposalModalActive: false,
            appTapShow: false,
            dealActivitiesModal: false,
            isCheckMailModalActive: false,

            // Tab Start
            todoActivityTab: [
                {
                    name: "activity",
                    icon: "activity",
                    component: "deal-activity-tab",
                    props: "",
                    permission: "",
                },
                {
                    name: "file",
                    icon: "paperclip",
                    component: "deal-file-tab",
                    props: "",
                    permission: "",
                },
                {
                    name: "note",
                    icon: "file-text",
                    component: "deal-note-tab",
                    props: "",
                    permission: "",
                },
            ],

            dealNoteModal: false,
            noteRowData: null,
            rowData: null,
            value: "",
            groupByCollaborator: [],
            collaborators: [],
            groupByParticipant: [],
            participants: [],
            status_id: 7,
            formatDateToLocal,
            onlyTime,
        };
    },
    computed: {
        ...mapGetters({
            ownerList: "getOwner",
            statusList: "getStatus",
            personList: "getPerson",
            organizationList: "getOrganization",
            templateList: "getTemplate",
            checkEmailDelivery: "checkEmailDelivery",
        }),

        dealStatus() {
            return (
                this.formData.status.name == "status_open" &&
                this.formData.status.type == "deal"
            )
        },
        syncPermission() {
            return this.$can('sync_followers_deal') ? true : false;
        },
        activityStatusList() {
            return this.$store.getters.getActivityStatus
        },
    },
    methods: {
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.groupByCollaboratorParticipant(this.formData);

            this.todoActivityTab.forEach((el) => {
                el["props"] = {
                    id: this.formData.id,
                    status: this.dealStatus,
                };
            });
            this.appTapShow = true;
            this.dataLoaded = true;
        },
        dealUpdateRequest() {
            this.isComponent = false;

            this.axiosGet(this.selectedUrl)
                .then(({data}) => {
                    this.formData = data;
                    this.isComponent = true;

                    //Reload tab to send deal update status
                    this.appTapShow = false;
                    this.todoActivityTab.forEach((el) => {
                        el["props"] = {
                            id: this.formData.id,
                            status: this.dealStatus,
                        };
                    });

                    setTimeout(() => {
                        this.appTapShow = true;
                    });

                    this.groupByCollaboratorParticipant(this.formData);
                })
                .catch((error) => console.log(error));
        },
        groupByCollaboratorParticipant(data) {
            let collaborator = [];
            let participant = [];
            // Collaborator Group by
            data.activity.forEach((element, index) => {
                element.collaborators.forEach((item, index) => {
                    collaborator.push(item);
                });
            });
            this.groupByCollaborator = _.groupBy(collaborator, "id");

            // Participant Group By
            data.activity.forEach((element, index) => {
                element.participants.forEach((item, index) => {
                    participant.push(item);
                });
            });
            this.groupByParticipant = _.groupBy(participant, "id");
        },

        updateTagList() {
            this.$hub.$on("tag-list", (value = true) => {
                if (value) {
                    this.dealUpdateRequest();
                }
            });
        },

        openSendProposalModal() {
            if (this.checkEmailDelivery != 1)
                this.isCheckMailModalActive = true;
            else
                this.isSendProposalModalActive = true;
        },
        closeSendProposalModal() {
            this.isSendProposalModalActive = false;
            $("#send-proposal").modal("hide");
        },
        closeModalCheckMail() {
            this.isCheckMailModalActive = false;
            $('#check-email-modal').modal('hide');
        },

        editActivity(activity) {
            this.dealActivitiesModal = true;
            this.editedUrl = route('activities.show', {id: activity.id});
            this.rowData = activity;
        },
        editNote(activity) {
            this.dealNoteModal = true;
            this.noteRowData = activity;
            setTimeout(() => {
                $("#note-modal").modal("show");
            });
        },

        closeDealActivitiesModal() {
            this.dealActivitiesModal = false;
            $("#deal-activity-modal").modal("hide");
        },
        closeDealNoteModal() {
            this.dealNoteModal = false;
            $("#note-modal").modal("hide");
        },

    },
    mounted() {
        this.updateTagList();
        this.$store.dispatch('getActivityStatus');
        this.$store.dispatch("getPerson");
        this.$store.dispatch("getOrganization");
        this.$store.dispatch("getStatus");
        this.$store.dispatch("getOwner");
        this.$store.dispatch("getTemplate");
        this.$store.dispatch('checkEmailDelivery');

    },
};
</script>
<style>
.deal-closed .step-input {
    opacity: 0.5;
    cursor: not-allowed;
}

.deal-closed .step-input:hover {
    opacity: 0.8;
}
</style>
