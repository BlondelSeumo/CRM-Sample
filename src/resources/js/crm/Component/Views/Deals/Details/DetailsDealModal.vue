<template>
  <app-modal
    modal-alignment="top"
    modal-body-class="quick-view-modal"
    modal-id="detailsViewModal"
    modal-size="extra-large"
    @close-modal="closeModal"
  >
    <template slot="header">
      <div class="d-flex align-items-center justify-content-between w-100">
        <div class="d-flex align-items-center">
          <app-avatar
            avatar-class="avatars-w-50"
            :title="selectedDeal.owner.full_name"
            :img="
              selectedDeal.owner.profile_picture
                ? urlGenerator(selectedDeal.owner.profile_picture.path)
                : urlGenerator('/images/profile.png')
            "
          />
          <div class="ml-3">
            <h5>{{ selectedDeal.owner.full_name }}</h5>
            <div class="font-size-90 text-muted">
              {{
                $t("deal_owner") +
                " - " +
                formatDateToLocal(selectedDeal.created_at)
              }}
              -
              {{ $t("age") + " - " + dealAgeHumanize(this.formData.created_at, this.formData.updated_at, this.formData.status) }}
              {{
                formData.discussions.length > 0
                  ? " - " + formData.discussions.length + " " + $t("comments")
                  : ""
              }}
            </div>
          </div>
        </div>

        <div>
          <button
            v-if="
              !(
                !this.$can('manage_public_access') && this.$can('client_access')
              )
            "
            type="button"
            class="btn btn-primary btn-with-shadow btn-sm mr-2"
            data-toggle="modal"
            @click="showDealDetails()"
          >
            <app-icon name="external-link" class="mb-1" />
            {{ $t("view_details") }}
          </button>

          <button
            aria-label="Close"
            class="close outline-none m-0 p-0"
            @click.prevent="closeModal"
            type="button"
          >
            <span>
              <app-icon name="x" />
            </span>
          </button>
        </div>
      </div>
    </template>
    <template slot="body" v-if="dataLoaded">
      <div class="row">
        <div class="col-xl-9 mb-4 mb-xl-0">
          <!--Title-->
          <h4 class="mb-4">
            <b>#{{ selectedDeal.id }} </b>
            {{ selectedDeal.title }}
            <span
              :class="`badge badge-pill badge-${selectedDeal.status.class}`"
              >{{ selectedDeal.status.translated_name }}</span
            >
          </h4>
          <!--Description-->
          <div class="card border-0 mb-3">
            <div class="card-header">
              <b>{{ $t("description") }}</b>
              <div
                v-if="isEditDescription && checkDealStatus && clientRoleAccess"
                class="
                  d-flex
                  align-items-center
                  justify-content-start
                  header-actions
                  float-right
                "
              >
                <a href="#" @click.prevent="editDescription()">
                  <app-icon class="size-18" name="edit" />
                </a>
              </div>
            </div>
            <div class="card-body comment-card">
              <div
                v-if="isEditDescription"
                class="d-flex align-items-center justify-content-between mb-4"
              >
                <div class="d-flex align-items-center">
                  <p class="mb-0">
                    {{
                      formData.description
                        ? formData.description
                        : $t("no_description")
                    }}
                  </p>
                </div>
              </div>
              <template v-else>
                <app-input
                  type="textarea"
                  :text-area-rows="5"
                  v-model="description"
                  :placeholder="$t('enter_description')"
                />
                <div class="d-flex justify-content-end mt-2">
                  <button
                    class="btn btn-secondary mr-2"
                    @click.prevent="cancelEditDescription"
                  >
                    {{ $t("cancel") }}
                  </button>
                  <button
                    class="btn btn-primary"
                    @click="updateDescription(selectedDeal.id)"
                  >
                    <span class="w-100">
                      <app-submit-button-loader
                        v-if="buttonLoading"
                      ></app-submit-button-loader>
                    </span>
                    <template v-if="!buttonLoading">{{
                      formData.description ? $t("update") : $t("add")
                    }}</template>
                  </button>
                </div>
              </template>
            </div>
          </div>
          <!--Log-->

          <template v-for="(event, index) in computedTimeLine">
            <!-- Not for client section -->
            <div
              class="d-flex justify-content-start mb-3"
              :key="index"
              v-if="event['changed_key'] && clientRoleAccess"
            >
              <app-avatar
                avatar-class="avatars-w-30"
                title="John Doe"
                :img="
                  event.responsible_user.profile_picture
                    ? urlGenerator(event.responsible_user.profile_picture.path)
                    : null
                "
              />
              <div class="text-muted ml-3 mt-1 mb-0">
                <span class="font-weight-bold">
                  {{ event.responsible_user.full_name }}</span
                >
                <template
                  v-if="
                    event.changed_key == 'expired_at' && event.old_value == null
                  "
                >
                  {{ $t("expecting_closing_date_added") }}
                </template>

                <template v-else>
                  {{
                    $t("changed_the_pipe_or_stage", {
                      key: $t(event.changed_key),
                    })
                  }}
                </template>

                <span class="font-weight-bold">{{
                  event.old_value == null
                    ? event.old_value
                    : event.old_value.name
                    ? event.old_value.name
                    : event.changed_key == "description"
                    ? textTruncate(event.old_value, 22, "...")
                    : event.changed_key == "expired_at"
                    ? formatDateToLocal(event.old_value)
                    : event.old_value
                }}</span>
                to
                <span class="font-weight-bold">{{
                  event.new_value == null
                    ? event.new_value
                    : event.new_value.name
                    ? event.new_value.name
                    : event.changed_key == "description"
                    ? textTruncate(event.new_value, 22, "...")
                    : event.changed_key == "expired_at"
                    ? formatDateToLocal(event.new_value)
                    : event.new_value
                }}</span>
                <span class="font-italic">
                  {{ createdInfoShowAsHumanize(event.created_at) }}</span
                >
              </div>
            </div>
            <!-- End of Not for client section -->
            <!--Comment & Edit-->
            <div
              class="card border-0 mb-3"
              v-else-if="event.comment_body"
              :key="index"
            >
              <div class="card-body comment-card">
                <template v-if="deleteableCommentId !== event.id">
                  <div
                    class="
                      d-flex
                      align-items-center
                      justify-content-between
                      mb-4
                    "
                  >
                    <div class="d-flex align-items-center">
                      <app-avatar
                        avatar-class="avatars-w-40"
                        :title="event.responsible_user.full_name"
                        :img="
                          event.responsible_user.profile_picture
                            ? urlGenerator(
                                event.responsible_user.profile_picture.path
                              )
                            : null
                        "
                      />
                      <div class="ml-3">
                        <h6 class="mb-1">
                          {{ event.responsible_user.full_name }}
                        </h6>
                        <p class="text-muted font-size-90 mb-0">
                          {{ formatDateTimeToLocal(event.created_at) }}
                        </p>
                      </div>
                    </div>
                    <div
                      class="
                        d-flex
                        align-items-center
                        justify-content-start
                        comments-actions
                      "
                      v-if="
                        event.responsible_user.id == authUser.id &&
                        editableId !== event.id
                      "
                    >
                      <a href="#" @click.prevent="editComment(event)">
                        <app-icon class="size-18" name="edit" />
                      </a>
                      <a
                        href="#"
                        class="ml-2"
                        @click.prevent="deleteComment(event.id)"
                      >
                        <app-icon class="size-18" name="trash" />
                      </a>
                    </div>
                  </div>

                  <template v-if="editableId == event.id">
                    <app-input
                      type="text-editor"
                      :id="event.id + 'editor'"
                      v-model="comment"
                      :height="200"
                      :minimal="true"
                    />
                    <div class="d-flex justify-content-end mt-2">
                      <button
                        class="btn btn-secondary mr-2"
                        @click.prevent="cancelEditComment"
                      >
                        {{ $t("cancel") }}
                      </button>
                      <button
                        class="btn btn-primary"
                        @click="updateComment(event)"
                      >
                        <span class="w-100">
                          <app-submit-button-loader
                            v-if="updateLoading"
                          ></app-submit-button-loader>
                        </span>
                        <template v-if="!updateLoading">{{
                          $t("update")
                        }}</template>
                      </button>
                    </div>
                  </template>
                  <template v-else>
                    <p class="text-muted mb-0" v-html="event.comment_body"></p>
                  </template>
                </template>
                <app-overlay-loader v-else />
              </div>
            </div>
            <!--Log with badges-->
            <div
              class="d-flex justify-content-start mb-3"
              v-else-if="event['tag_removed'] && clientRoleAccess"
              :key="index"
            >
              <app-avatar
                avatar-class="avatars-w-30"
                title="John Doe"
                :img="
                  event.responsible_user.profile_picture
                    ? urlGenerator(event.responsible_user.profile_picture.path)
                    : null
                "
              />
              <div class="text-muted ml-3 mt-1 mb-0">
                <span class="font-weight-bold"
                  >{{ event.responsible_user.full_name }}
                </span>
                {{ $t("removed") }}
                <span
                  class="
                    badge badge-primary badge-sm
                    rounded-pill
                    font-weight-normal
                  "
                  :style="{ background: event.tag.color_code }"
                  >{{ event.tag.name }}</span
                >
                {{ $t("tag") }}
                <span class="font-weight-normal font-italic">{{
                  createdInfoShowAsHumanize(event.created_at)
                }}</span>
              </div>
            </div>
            <div
              class="d-flex justify-content-start mb-3"
              v-else-if="event['tag_added'] && clientRoleAccess"
              :key="index"
            >
              <app-avatar
                avatar-class="avatars-w-30"
                title="John Doe"
                :img="
                  event.responsible_user.profile_picture
                    ? urlGenerator(event.responsible_user.profile_picture.path)
                    : null
                "
              />
              <div class="text-muted ml-3 mt-1 mb-0">
                <span class="font-weight-bold"
                  >{{ event.responsible_user.full_name }}
                </span>
                {{ $t("added") }}
                <span
                  class="
                    badge badge-primary badge-sm
                    rounded-pill
                    font-weight-normal
                  "
                  :style="{ background: event.tag.color_code }"
                  >{{ event.tag.name }}</span
                >
                {{ $t("tag") }}
                <span class="font-weight-normal font-italic">{{
                  createdInfoShowAsHumanize(event.created_at)
                }}</span>
              </div>
            </div>

              <!--Status change-->
              <div
                  class="d-flex justify-content-start mb-3"
                  v-else-if="event['status_change']"
                  :key="index"
              >
                  <app-avatar
                      avatar-class="avatars-w-30"
                      title="John Doe"
                      :img="
                  event.responsible_user.profile_picture
                    ? urlGenerator(event.responsible_user.profile_picture.path)
                    : null
                "
                  />
                  <div class="text-muted ml-3 mt-1 mb-0">
                <span class="font-weight-bold"
                >{{ event.responsible_user.full_name }}
                </span>
                      <template v-if="event.new_value.name == 'status_open'">
                          {{ $t("reopen_this_deal") }}
                      </template>
                      <template v-if="event.new_value.name == 'status_won'">
                          {{ $t("won_this_deal") }}
                      </template>
                      <template v-if="event.new_value.name == 'status_lost'">
                          {{ $t("lost_this_deal") }}
                      </template>
                      <span class="font-weight-normal font-italic">{{
                              formatDateToLocal(event.created_at)
                          }}</span>
                  </div>
              </div>

              <!--Lost reason add -->
              <div
                  class="d-flex justify-content-start mb-3"
                  v-else-if="event['lost_reason_add']"
                  :key="index"
              >
                  <app-avatar
                      avatar-class="avatars-w-30"
                      title="John Doe"
                      :img="
                  event.responsible_user.profile_picture
                    ? urlGenerator(event.responsible_user.profile_picture.path)
                    : null
                "
                  />
                  <div class="text-muted ml-3 mt-1 mb-0">
                <span class="font-weight-bold"
                >{{ event.responsible_user.full_name }}
                </span>
                      {{ $t("add_lost_reason") }}
                      <span class="font-weight-bold">{{ event.new_value }}</span>
                      {{ $t("for_this_deal") }}
                      <span class="font-weight-normal font-italic">{{
                              formatDateToLocal(event.created_at)
                          }}</span>
                  </div>
              </div>

            <!--Log Contact person-->
            <div
              class="d-flex justify-content-start mb-3"
              v-else-if="event['contact_person_removed']"
              :key="index"
            >
              <app-avatar
                avatar-class="avatars-w-30"
                title="John Doe"
                :img="
                  event.responsible_user.profile_picture
                    ? urlGenerator(event.responsible_user.profile_picture.path)
                    : null
                "
              />
              <div class="text-muted ml-3 mt-1 mb-0">
                <span class="font-weight-bold"
                  >{{ event.responsible_user.full_name }}
                </span>
                {{ $t("removed") }}
                <span
                  class="
                    badge badge-primary badge-sm
                    rounded-pill
                    font-weight-normal
                  "
                  >{{ event.contact_person_remove.name }}</span
                >
                {{ $t("from_contact_person") }}
                <span class="font-weight-normal font-italic">{{
                  createdInfoShowAsHumanize(event.created_at)
                }}</span>
              </div>
            </div>
            <div
              class="d-flex justify-content-start mb-3"
              v-else-if="event['contact_person_added']"
              :key="index"
            >
              <app-avatar
                avatar-class="avatars-w-30"
                title="John Doe"
                :img="
                  event.responsible_user.profile_picture
                    ? urlGenerator(event.responsible_user.profile_picture.path)
                    : null
                "
              />
              <div class="text-muted ml-3 mt-1 mb-0">
                <span class="font-weight-bold"
                  >{{ event.responsible_user.full_name }}
                </span>
                {{ $t("added") }}
                <span
                  class="
                    badge badge-primary badge-sm
                    rounded-pill
                    font-weight-normal
                  "
                  >{{ event.contact_person_add.name }}</span
                >
                {{ $t("as_a_contact_person") }}
                <span class="font-weight-normal font-italic">{{
                  createdInfoShowAsHumanize(event.created_at)
                }}</span>
              </div>
            </div>
          </template>

          <!--Compose Comment-->
          <div class="card border-0">
            <div class="card-body">
              <div class="d-flex align-items-center mb-4">
                <app-avatar
                  avatar-class="avatars-w-40"
                  title="John Doe"
                  :img="
                    authUser.profile_picture
                      ? urlGenerator(authUser.profile_picture.path)
                      : null
                  "
                />
                <div class="ml-3">
                  <h6 class="mb-1">{{ authUser.full_name }}</h6>
                </div>
              </div>
              <app-input
                type="text-editor"
                v-model="comment"
                :height="200"
                :minimal="true"
              />
              <div class="d-flex justify-content-end mt-2">
                <button
                  type="button"
                  class="btn btn-primary"
                  @click.prevent="addComment"
                >
                  <span class="w-100">
                    <app-submit-button-loader
                      v-if="btnLoading"
                    ></app-submit-button-loader>
                  </span>
                  <template v-if="!btnLoading">{{ $t("comment") }}</template>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3">
          <deal-quick-view-stage
            :deal-status="checkDealStatus"
            :form-data="formData"
            :stages="stages"
            :client-access="clientRoleAccess"
            @update-request="afterDealUpdated"
          />

          <deal-quick-view-deal-value
            :deal-status="checkDealStatus"
            :form-data="formData"
            :stages="stages"
            :client-access="clientRoleAccess"
            @update-request="afterDealUpdated"
          />

          <deal-quick-view-lead-info
            :deal-status="checkDealStatus"
            :form-data="formData"
            :stages="stages"
            :client-access="clientRoleAccess"
            @update-request="afterDealUpdated"
          />

          <div class="border-bottom mb-4 pb-4">
            <p class="mb-2 font-weight-bold">
              {{ $t("Proposal") }} ({{ selectedDeal.proposals_count }})
            </p>
            <div
              class="
                d-flex
                align-items-center
                justify-content-between
                font-size-90
                mb-2
              "
              v-for="proposal in selectedDeal.proposals"
              :key="proposal.id"
            >
              <a>{{ textTruncate(proposal.subject, 22, "...") }}</a>
              <span :class="`badge badge-pill badge-${proposal.status.class}`">
                {{ proposal.status.translated_name }}
              </span>
            </div>
          </div>
          <!--Tags start-->
          <template v-if="clientRoleAccess">
            <deal-quick-view-tags
              :post-url="route('deal.attach-tag', { id: formData.id })"
              :taggable-id="formData.id"
              class="mb-primary"
              :tagData="formData.tags"
              @update-request="afterDealUpdated"
            />
          </template>
          <!--Tags end-->

          <deal-quick-view-expire-date
            :deal-status="checkDealStatus"
            :form-data="formData"
            :stages="stages"
            :client-access="clientRoleAccess"
            @update-request="afterDealUpdated"
          />

          <template v-if="clientRoleAccess">
            <div class="pb-4">
              <deal-quick-view-followers
                :deal-status="checkDealStatus"
                :follower-data="formData"
                :follower-sync-url="
                  route('deal.sync-followers', { id: formData.id })
                "
                :get-follower-url="
                  route('deal.get-followers', { id: formData.id })
                "
                :people-list="personList"
                :permission-check="syncPermission"
                :status-check="true"
                class="mb-primary"
                @update-request="afterDealUpdated"
              />
              <!--Followers info end-->
            </div>
          </template>
        </div>
      </div>
    </template>
    <app-overlay-loader v-else />
  </app-modal>
</template>

<script>

import { FormMixin } from "@core/mixins/form/FormMixin.js";
import {
  formatDateToLocal,
  urlGenerator,
  formatDateTimeToLocal,
  textTruncate,
  numberFormatter,
    dealAgeHumanize
} from "@app/Helpers/helpers";
import { collect } from "@app/Helpers/Collection";
import moment from "moment";
import { mapGetters } from "vuex";

export default {
  props: ["selectedDeal", "pipelines", "stages"],
  name: "DealQuickViewModal",
  mixins: [FormMixin],
  data() {
    return {
      textTruncate,
      numberFormatter,
        dealAgeHumanize,
      route,
      // Sample editable Comment
      authUser: window.user,
      editableId: null,
      formData: this.selectedDeal,
      pageTitle: this.selectedDeal.title,
      formatDateToLocal,
      formatDateTimeToLocal,
      urlGenerator,
      dataLoaded: false,
      isEditDescription: true,
      description: "",
      loader: true,
      errors: [],
      timeline: [],
      comment: "",
      activity: {
        users: "Meeting",
        mail: "Email",
        "phone-call": "Call",
        calendar: "Deadline",
        cpu: "Hacked",
      },
      tagPreloader: false,
      btnLoading: false,
      buttonLoading: false,
      updateLoading: false,
      deleteableCommentId: null,
    };
  },
  computed: {
    checkDealStatus() {
      return this.selectedDeal.status.name == "status_open";
    },
    dealAge() {
      let dateTime = "";
      if (this.checkDealStatus) {
        dateTime = Math.abs(new Date(this.formData.created_at) - new Date());
      } else {
        dateTime = Math.abs(
          new Date(this.formData.updated_at) -
            new Date(this.formData.created_at)
        );
      }

      let days = parseInt(dateTime / (1000 * 60 * 24 * 60));
      let minutes = parseInt(dateTime / (1000 * 60)) % (24 * 60);
      let hours = minutes > 60 ? parseInt(minutes / 60) : 0;
      let minute = minutes > 60 ? minutes % 60 : minutes;

      return days + " days " + hours + " hours " + minute + " minutes ";
    },
    clientRoleAccess() {
      return !(
        !this.$can("manage_public_access") && this.$can("client_access")
      );
    },
    ...mapGetters({
      personList: "getPerson",
    }),
    syncPermission() {
      return this.$can("sync_followers_deal") ? true : false;
    },
    computedTimeLine() {
      return this.formData.proposals.length > 0 &&
        this.formData.activity.length > 0 &&
        this.formData.discussions.length > 0
        ? collect(this.formData.proposals)
            .merge(this.formData.activity)
            .merge(this.formData.discussions)
            .merge(this.timeline)
            .sortBy()
            .get()
        : this.formData.proposals.length > 0 &&
          this.formData.activity.length > 0
        ? collect(this.formData.proposals)
            .merge(this.formData.activity)
            .merge(this.timeline)
            .sortBy()
            .get()
        : this.formData.proposals.length > 0 &&
          this.formData.discussions.length > 0
        ? collect(this.formData.proposals)
            .merge(this.formData.discussions)
            .merge(this.timeline)
            .sortBy()
            .get()
        : this.formData.activity.length > 0 &&
          this.formData.discussions.length > 0
        ? collect(this.formData.activity)
            .merge(this.formData.discussions)
            .merge(this.timeline)
            .sortBy()
            .get()
        : this.formData.proposals.length > 0
        ? collect(this.formData.proposals).merge(this.timeline).sortBy().get()
        : this.formData.activity.length > 0
        ? collect(this.formData.activity).merge(this.timeline).sortBy().get()
        : this.formData.discussions.length > 0
        ? collect(this.formData.discussions).merge(this.timeline).sortBy().get()
        : this.timeline.length > 0
        ? collect(this.timeline).sortBy().get()
        : this.timeline;
    },
  },
  created() {
    this.getRevisionData();
    this.getDiscussionByDeal();
  },
  methods: {
    editComment(comment) {
      this.editableId = comment.id;
      this.comment = comment.comment_body;
    },
    cancelEditComment() {
      this.editableId = null;
      this.comment = "";
      this.btnLoading = false;
    },
    editDescription() {
      this.description = this.formData.description;
      this.isEditDescription = false;
    },
    updateDescription(dealId) {
      this.buttonLoading = true;
      let dealData = {};
      dealData.title = this.formData.title;
      dealData.description = this.description;

      this.axiosPatch({
        url: route("deals.update", { id: dealId }),
        data: dealData,
      })
        .then((response) => {
          this.buttonLoading = false;
          this.afterDealUpdated();
          this.isEditDescription = true;
        })
        .catch((error) => {
          this.buttonLoading = false;
          this.isEditDescription = true;
          console.log(error);
        });
    },
    cancelEditDescription() {
      this.isEditDescription = true;
    },
    getRevisionData() {
      if (!(!this.$can("manage_public_access") && this.$can("client_access"))) {
        this.$store.dispatch("getPerson");

        this.axiosGet(
          route("deal.revision-history", {
            deal: this.selectedDeal.id,
          })
        ).then((res) => {
          this.timeline = [...res.data];
          this.dataLoaded = true;
        });
      } else {
        setTimeout(() => {
          this.dataLoaded = true;
        }, 1000);
      }
    },
    deleteComment(commentID) {
      this.deleteableCommentId = commentID;
      this.axiosDelete(route("discussions.destroy", { id: commentID })).then(
        (e) => {
          this.getDiscussionByDeal().then(() => {
            this.deleteableCommentId = null;
          });
        }
      );
    },
    showDealDetails() {
      window.open(
        route("deal_details.page", { id: this.selectedDeal.id }),
        "_blank"
      );
    },
    closeModal(value) {
      this.$emit("close-modal", value);
    },
    addComment() {
      let discussionData = {};
      discussionData.commented_by = window.user.id;
      discussionData.comment_body = this.comment;
      discussionData.commentable_id = this.formData.id;
      this.btnLoading = true;

      this.axiosPost({
        url: route("discussions.store"),
        data: discussionData,
      })
        .then((response) => {
          this.afterSuccess();
        })
        .catch((error) => {
          this.btnLoading = false;
          console.log(error);
        });
    },
    updateComment(comment) {
      let discussionData = {};
      discussionData.comment_body = this.comment;
      comment.comment_body = this.comment;
      this.updateLoading = true;
      this.axiosPatch({
        url: route("discussions.update", { id: this.editableId }),
        data: discussionData,
      })
        .then((response) => {
          this.afterSuccess();
        })
        .catch((error) => {
          this.btnLoading = false;
          console.log(error);
        });
    },
    afterError(response) {
      this.btnLoading = false;
    },
    afterSuccess(response) {
      if (!this.editableId) {
        this.getDiscussionByDeal().then(() => {
          this.cancelEditComment();
        });
      } else {
        this.cancelEditComment();
      }
    },
    afterFinalResponse() {
        this.updateLoading = false;
    },
    createdInfoShowAsHumanize(createdAt) {
      let duration = moment().diff(createdAt, "seconds");
      return duration < 60 * 60 * 24 * 7
        ? moment.duration(duration, "seconds").humanize() + " ago"
        : moment(createdAt).format("DD MMM YY");
    },

    getDiscussionByDeal() {
      return this.axiosGet(
        route("discussions.index", { _query: { deal: this.formData.id } })
      )
        .then(({ data }) => {
          this.formData.discussions = data;
        })
        .catch((error) => console.log(error));
    },
    afterDealUpdated() {
      this.axiosGet(route("deals.show", { id: this.selectedDeal.id }))
        .then(({ data }) => {
          this.formData = data;
          this.getRevisionData();
          this.$emit("deal-update");
        })
        .catch((error) => console.log(error));
    },
  },
  watch: {},
};
</script>

<style scoped>
.comments-actions,
.header-actions {
  visibility: hidden;
}
.comment-card:hover .comments-actions,
.card-header:hover .header-actions {
  visibility: visible;
}
</style>
