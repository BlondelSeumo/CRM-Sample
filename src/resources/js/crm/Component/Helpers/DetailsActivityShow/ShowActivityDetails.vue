<template>
  <div class="activity-filter-wrapper">
    <template v-for="(filter, index) in activitiesFilter">
      <button
        :class="[
          value === filter.key
            ? 'text-primary'
            : index === 0 && value === ''
            ? 'text-primary'
            : 'text-muted',
        ]"
        class="btn btn-sm px-3 rounded-pill primary-card-color mr-2"
        @click.prevent="filterActivities(activityFilterUrl, filter.key)"
      >
        {{ filter.name }}
      </button>
    </template>

    <div class="mt-2 activity-filtered-result-wrapper">
      <template v-if="preLoader"></template>
      <template v-else>
        <template v-if="activitiesList.length < 1">
          <div class="card card-with-shadow border-0">
            <div class="card-body">
              <template v-for="filter in activitiesFilter">
                <app-empty-data-block
                  v-if="value === filter.key"
                  :key="filter.key"
                  :message="$t('please_add_something_in') + filter.name"
                />
              </template>
            </div>
          </div>
        </template>
        <div
          v-for="(activity, index) in activitiesList"
          v-else
          :key="index"
          :class="{ 'mb-2': activitiesList.length > 0 }"
        >
          <div class="card card-with-shadow border-0">
            <div class="card-body">
              <div class="d-flex justify-content-between mb-3">
                <div class="d-flex align-items-center">
                  <div
                    class="d-flex align-items-center justify-content-center primary-text-color mr-2 activity-icon"
                  >
                    <app-icon :name="activity.icon" stroke-width="1" />
                  </div>
                  <div>
                    <h6 v-if="activity.title">{{ activity.title }}</h6>
                    <h6 v-if="activity.path">{{ activity.path.split("/").pop() }}</h6>
                    <h6 v-if="activity.note" v-html="textTruncate(activity.note, 15)">
                      {{ $t("note") }}
                    </h6>
                    <div class="d-flex align-items-center font-size-70">
                      <label
                        v-if="activity.status_id == todoStatusId.id"
                        class="customized-radio mini radio-default mr-0"
                      >
                        <input
                          :disabled="
                            $can('update_activities') && $can('done_activities')
                              ? false
                              : true
                          "
                          class="radio-inline"
                          type="radio"
                          @click="activityChangeStatus(activity.id)"
                        />
                        <span class="outside">
                          <span class="inside" />
                        </span>
                      </label>

                      <div v-if="activity.started_at" class="text-success">
                        <span v-if="activity.started_at == today.dateOnly"
                          >{{ $t("today") }} |
                          {{
                            onlyTimeFromTime(activity.started_at, activity.start_time)
                          }}</span
                        >
                        <span v-else-if="activity.ended_at < today.dateOnly"
                          >{{ $t("yesterday") }} |
                          {{
                            onlyTimeFromTime(activity.started_at, activity.start_time)
                          }}</span
                        >
                        <span v-else
                          >{{
                            formatDateToLocal(
                              activity.started_at,
                              true,
                              activity.start_time
                            )
                          }}
                          |
                          {{
                            formatDateToLocal(activity.ended_at, true, activity.end_time)
                          }}</span
                        >
                      </div>

                      <div v-else class="text-success">
                        <span
                          >{{ formatDateToLocal(activity.created_at, true) }} |
                          {{ onlyTime(activity.created_at) }}</span
                        >
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="
                    activity.status_id == todoStatusId.id &&
                    (($can('update_activities') && $can('done_activities')) ||
                      $can('delete_activities'))
                  "
                  class="dropdown options-dropdown"
                >
                  <button class="btn-option btn" data-toggle="dropdown" type="button">
                    <app-icon name="more-vertical" />
                  </button>
                  <div class="dropdown-menu dropdown-menu-right py-2 mt-1 text-muted">
                    <a
                      v-if="$can('update_activities')"
                      class="dropdown-item font-size-80 px-4 py-2"
                      href="#"
                      @click.prevent="editActivity(activity)"
                      >{{ $t("edit") }}</a
                    >
                    <a
                      v-if="$can('update_activities') && $can('done_activities')"
                      class="dropdown-item font-size-80 px-4 py-2"
                      href="#"
                      @click.prevent="activityChangeStatus(activity.id)"
                      >{{ $t("mark_as_done") }}</a
                    >
                    <a
                      v-if="$can('delete_activities')"
                      class="dropdown-item font-size-80 px-4 py-2"
                      href="#"
                      @click.prevent="activityDelete(activity.id)"
                      >{{ $t("delete") }}</a
                    >
                  </div>
                </div>

                <div v-if="activity.path" class="dropdown options-dropdown">
                  <button class="btn-option btn" data-toggle="dropdown" type="button">
                    <app-icon name="more-vertical" />
                  </button>
                  <div class="dropdown-menu dropdown-menu-right py-2 mt-1 text-muted">
                    <a
                      class="dropdown-item font-size-80 px-4 py-2"
                      href="#"
                      @click.prevent="fileDownload(activity)"
                      >{{ $t("download") }}</a
                    >
                  </div>
                </div>
                <div v-if="activity.note" class="dropdown options-dropdown">
                  <button class="btn-option btn" data-toggle="dropdown" type="button">
                    <app-icon name="more-vertical" />
                  </button>
                  <div class="dropdown-menu dropdown-menu-right py-2 mt-1 text-muted">
                    <a
                      class="dropdown-item font-size-80 px-4 py-2"
                      href="#"
                      @click.prevent="editNote(activity)"
                      >{{ $t("edit") }}</a
                    >
                    <a
                      class="dropdown-item font-size-80 px-4 py-2"
                      href="#"
                      @click.prevent="noteDelete(activity.id)"
                      >{{ $t("delete") }}</a
                    >
                  </div>
                </div>
              </div>
              <div v-if="activity.description" class="note p-2 mb-3 note-warning">
                <p class="m-1">
                  {{ activity.description }}
                </p>
              </div>
              <div v-if="activity.note" class="note p-2 mb-3 note-warning">
                <p class="m-1" v-html="activity.note"></p>
              </div>
              <div class="mb-3 d-flex flex-wrap align-items-center">
                <div v-if="formData.owner" class="d-flex align-items-center mr-3">
                  <app-avatar
                    :avatar-class="'avatars-w-30'"
                    :img="
                      formData.owner.profile_picture
                        ? urlGenerator(formData.owner.profile_picture.path)
                        : formData.owner.profile_picture
                    "
                    :title="formData.owner.full_name"
                    class="mr-2"
                  />
                  <span class="text-muted">{{ formData.owner.full_name }}</span>
                </div>

                <template v-if="componentType == 'person'">
                  <div
                    v-for="organization in formData.organizations"
                    v-if="formData.organizations.length == 1"
                    class="d-flex align-items-center mr-3"
                  >
                    <span class="mr-2 text-secondary org-icon"
                      ><app-icon :name="'briefcase'"></app-icon
                    ></span>
                    <span class="text-muted">{{ organization.name }}</span>
                  </div>
                </template>

                <template v-else-if="componentType == 'org'">
                  <div
                    v-for="person in formData.persons"
                    v-if="formData.persons.length == 1"
                    class="d-flex align-items-center mr-3"
                  >
                    <span class="mr-2 text-secondary org-icon">
                      <app-icon :name="'user'"></app-icon>
                    </span>
                    <span class="text-muted">
                      {{ person.name }}
                    </span>
                  </div>
                </template>

                <template v-else>
                  <div class="d-flex align-items-center mr-3" v-if="formData.person">
                    <span class="mr-2 text-secondary person-icon"
                      ><app-icon :name="'user'"></app-icon
                    ></span>
                    <span class="text-muted font-size-90">{{
                      formData.person.name
                    }}</span>
                  </div>
                  <div
                    class="d-flex align-items-center mr-3"
                    v-if="formData.organization"
                  >
                    <span class="mr-2 text-secondary org-icon"
                      ><app-icon :name="'briefcase'"></app-icon
                    ></span>
                    <span class="text-muted font-size-90">{{
                      formData.organization.name
                    }}</span>
                  </div>
                </template>
              </div>

              <div>
                <template v-if="componentType == 'person'">
                  <p
                    v-if="formData.organizations.length > 1"
                    class="d-flex flex-wrap align-items-center"
                  >
                    {{ $t("organizations:") }}
                  </p>

                  <div class="d-flex justify-content-start mb-3">
                    <span
                      v-for="organization in formData.organizations"
                      v-if="formData.organizations.length > 1"
                      class="mr-3"
                    >
                      <span class="mr-2 text-secondary org-icon"
                        ><app-icon :name="'briefcase'"></app-icon
                      ></span>
                      <span class="text-muted">{{ organization.name }}</span>
                    </span>
                  </div>
                </template>

                <template v-else-if="componentType == 'org'">
                  <p
                    v-if="formData.persons.length > 1"
                    class="d-flex flex-wrap align-items-center"
                  >
                    {{ $t("persons:") }}
                  </p>
                  <div class="d-flex justify-content-start mb-3">
                    <span
                      v-for="person in formData.persons"
                      v-if="formData.persons.length > 1"
                      class="mr-3"
                    >
                      <span class="mr-2 text-secondary org-icon"
                        ><app-icon :name="'user'"></app-icon
                      ></span>
                      <span class="text-muted">{{ person.name }}</span>
                    </span>
                  </div>
                </template>
              </div>

              <div v-if="isfileNoteFilter">
                <p
                  v-if="activity.participants.length"
                  class="d-flex flex-wrap align-items-center"
                >
                  {{ $t("participants:") }}
                </p>
                <div
                  class="d-flex flex-wrap align-items-center"
                  v-bind:class="{ 'mb-3': activity.collaborators.length > 0 }"
                >
                  <div
                    v-for="participant in participants"
                    v-if="activity.id == participant.pivot.activity_id"
                    class="mb-1 d-flex align-items-center mr-3"
                  >
                    <app-avatar
                      :avatar-class="'avatars-w-30'"
                      :img="
                        participant.profile_picture
                          ? urlGenerator(participant.profile_picture.path)
                          : participant.profile_picture
                      "
                      :title="participant.name"
                      class="mr-2"
                    />
                    <span class="text-muted">{{ participant.name }}</span>
                  </div>
                </div>

                <p
                  v-if="activity.collaborators.length"
                  class="d-flex flex-wrap align-items-center"
                >
                  {{ $t("collaborators:") }}
                </p>
                <div class="d-flex flex-wrap align-items-center">
                  <div
                    v-for="collaborator in collaborators"
                    v-if="activity.id == collaborator.pivot.activity_id"
                    class="d-flex align-items-center mr-3"
                  >
                    <app-avatar
                      :avatar-class="'avatars-w-30'"
                      :img="
                        collaborator.profile_picture
                          ? urlGenerator(collaborator.profile_picture.path)
                          : collaborator.profile_picture
                      "
                      :title="collaborator.full_name"
                      class="mr-2"
                    />
                    <span class="text-muted">{{ collaborator.full_name }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
import {
  formatDateToLocal,
  onlyTime,
  onlyTimeFromTime,
  textTruncate,
  urlGenerator,
} from "@app/Helpers/helpers";
import ActivityMixin from "@app/Mixins/Global/ActivityMixin";

export default {
  name: "ShowActivityDetails",
  mixins: [ActivityMixin, FormMixin],
  props: {
      activityStatus:{
         type: Array
      },
    Data: {
      type: Object,
      required: true,
    },
    editUrl: {
      type: String,
    },
    fileFilterUrl: {
      type: String,
    },
      noteFilterUrl: {
          type: String,
      },
    activityFilterUrl: {
      type: String,
    },
    componentType: {
      type: String,
    },
    quickView: {
      type: Boolean,
    },
  },
  data() {
    return {
      formatDateToLocal,
      onlyTimeFromTime,
      onlyTime,
      textTruncate,
      urlGenerator,
    };
  },
};
</script>
