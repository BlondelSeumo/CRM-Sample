<template>
  <div class="content-wrapper kanban-view">
    <div class="row">
      <div class="col-sm-12 col-md-12" v-if="isReloadButton">
        <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
          <button
            class="btn btn-info position-fixed p-0 refresh-btn"
            @click.prevent="refreshState()"
          >
            <span><i class="fas fa-sync"></i></span>
            <span class="btn-text">{{ $t("refresh") }}</span>
          </button>
        </div>
      </div>
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb
          :page-title="currentPipeline"
          :directory="[$t('deals'), $t('pipeline_view'), currentPipeline]"
          :icon="'clipboard'"
        />
      </div>

      <div class="col-sm-12 col-md-6">
        <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
          <div class="dropdown d-inline-block btn-dropdown">
            <button
              type="button"
              class="btn btn-success dropdown-toggle ml-0 mr-2"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              v-if="
                $can('create_pipelines') ||
                $can('update_pipelines') ||
                $can('move_multiple_deals') ||
                $can('delete_pipelines') ||
                $can('update_deals') ||
                $can('import_deal') ||
                $can('create_lost_reasons') ||
                $can('view_lost_reasons')
              "
            >
              {{ $t("actions") }}
            </button>
            <div class="dropdown-menu">
              <a
                class="dropdown-item d-flex align-items-center p-3"
                :href="urlGenerator(`pipelines/create`)"
                v-if="$can('create_pipelines')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'plus-square'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("add_pipeline") }}
              </a>
              <a
                class="dropdown-item d-flex align-items-center p-3"
                :href="urlGenerator(`pipelines/${localPipelineId}/edit`)"
                v-if="$can('update_pipelines')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'edit'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("edit_pipeline") }}
              </a>
              <a
                class="dropdown-item d-flex align-items-center p-3"
                @click.prevent="deletePipeline"
                href="#"
                v-if="$can('delete_pipelines')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'trash'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("delete_pipeline") }}
              </a>
              <a
                v-if="$can('import_deal')"
                class="dropdown-item d-flex align-items-center p-3"
                :href="route('deal.import')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'download'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("import_deals") }}
              </a>

              <a
                v-if="$can('move_multiple_deals')"
                class="dropdown-item d-flex align-items-center p-3"
                @click.prevent="moveAllDeal"
                href="#"
              >
                <app-icon
                  stroke-width="1"
                  name="arrow-right-circle"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("move_all_deals") }}
              </a>

              <a
                class="dropdown-item d-flex align-items-center p-3"
                href=""
                @click.prevent="openLostreasonModal"
                v-if="$can('create_lost_reasons')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'plus-square'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("add_lost_reason") }}
              </a>

              <a
                class="dropdown-item d-flex align-items-center p-3"
                :href="urlGenerator(`lost/reasons/list/view`)"
                v-if="$can('view_lost_reasons')"
              >
                <app-icon
                  stroke-width="1"
                  :name="'list'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("manage_lost_reasons") }}
              </a>
            </div>
          </div>
          <button
            v-if="$can('create_deals')"
            type="button"
            class="btn btn-primary btn-with-shadow"
            data-toggle="modal"
            @click="openModal()"
          >
            {{ $t("add_deal") }}
          </button>
        </div>
      </div>
    </div>
    <!--filter-->
    <div class="row">
      <div class="col-9 col-sm-8 col-md-9 col-lg-10 col-xl-10">
        <div
          class="
            filters-wrapper
            calendar-position-modified
            d-flex
            justify-content-start
            flex-wrap
          "
        >
            <deal-card-column-manager
                v-if="filterDataLoaded"
                :manage-option="cardColumns"
                :init-option="initColumns"
                @get-columns-options="getColumnsOptions"
            />
          <!--Open Filters Button For Mobile-->
          <button
            class="btn d-block d-sm-none btn-toggle-filters"
            type="button"
            @click.prevent="toggleFilters"
          >
            <app-icon :name="'filter'" />
            {{ $t("filters") }}
          </button>

          <span v-show="isFiltersOpen" class="mobile-filters-wrapper">
            <app-filter
              table-id="kanban-view"
              :filters="options.filters"
              @get-values="getFilterValues"
              v-if="filterDataLoaded"
            />
            <template v-if="dataLoaded">
              <div class="d-flex align-items-center ml-2 mb-2">
                <p class="text-muted mb-0">
                  {{ filterDealCount }} {{ $t("deals", "Deals") }}
                </p>
                <p class="text-muted mb-0 mx-2" v-if="visibleClearFilter">-</p>
                <a
                  href="#"
                  class="text-primary"
                  v-if="visibleClearFilter"
                  @click.prevent="clearAllFilter"
                >
                  {{ $t("clear_all_filters") }}</a
                >
              </div>
            </template>
            <button
              type="button"
              class="
                btn btn-primary btn-with-shadow
                d-sm-none
                btn-close-filter-wrapper
                d-flex
                justify-content-center
                align-items-center
              "
              @click="toggleFilters"
            >
              {{ $t("close") }}
            </button>
          </span>
        </div>
      </div>
      <div class="col-3 col-sm-4 col-md-3 col-lg-2 col-xl-2">
        <div class="mr-0 single-filter single-search-wrapper">
          <div
            class="
              form-group form-group-with-search
              d-flex
              align-items-center
              justify-content-end
            "
          >
            <app-search @input="getSearchValue" />
          </div>
        </div>
      </div>
    </div>
    <div
      class="
        d-flex
        justify-content-center
        note note-warning
        shadow
        animate__animated animate__fadeIn
        p-1
        mb-2
      "
      v-if="computedHighlights.length > 0"
    >
      <span class="text-mute mr-2">
        {{ computedHighlights.length }}
        {{ $t("marked_deals") }}
      </span>
      <button
        class="btn btn-sm text-primary p-0"
        v-if="!markedPipeline.has(filterValues.pipeline)"
        @click.prevent="MarkedAllDealsOfPipeline(true)"
      >
        - {{ $t("mark_all_deals_in_this_pipeline") }}
      </button>
      <button
        class="btn btn-sm text-primary p-0"
        v-if="markedPipeline.has(filterValues.pipeline)"
        @click.prevent="MarkedAllDealsOfPipeline(false)"
      >
        - {{ $t("unmark_all_deals_in_this_pipeline") }}
      </button>
      <button
        class="btn btn-sm text-primary p-0 ml-2"
        v-if="computedHighlights.length > 0"
        @click.prevent="clearMarkedDeals"
      >
        - {{ $t("clear_over_all_marked") }}
      </button>
    </div>
    <!--kanban view-->
    <div class="card card-with-shadow border-0">
      <div class="card-body" style="padding: 0.5rem">
        <div
          class="kanban-wrapper custom-scrollbar overflow-auto pl-0"
          v-if="dataLoaded"
        >
          <div
            class="kanban-column position-relative"
            v-for="(stage, index) in stagesProperty"
            :key="index"
          >
            <template v-if="stagesData[stage.id]">
              <div class="py-3 stage-header rounded-top row">
                <div class="col-10">
                  <h6 class="stage-name text-truncate">
                    {{ stage.name }}
                  </h6>
                  <div
                    class="
                      text-muted
                      d-flex
                      flex-wrap
                      align-items-center
                      stage-information
                    "
                  >
                    <span>
                      {{
                        numberWithCurrencySymbol(
                          !isUndefined(collect(totalDealsValues).find(stage.id))
                            ? collect(totalDealsValues).find(stage.id).value
                            : ""
                        )
                      }}
                    </span>
                    <span
                      >{{ stagesData[stage.id].length }} {{ $t("deal") }}</span
                    >
                    <span>{{ stage.probability }}%</span>
                  </div>
                </div>
                <div class="col-2 collapse-btn">
                  <div @click="pipelineCollapse(index)" class="collapse-icon">
                    <app-icon
                      stroke-width="1"
                      name="chevrons-left"
                      class="size-16"
                    />
                  </div>
                </div>
                <div
                  v-if="
                    filterValues.highlights.length > 0 &&
                    stagesData[stage.id].length
                  "
                  :title="
                    markedStage.has(stage.id) ? $t('clear_all') : $t('mark_all')
                  "
                  class="select-icon mt-2 col-12"
                  @click.prevent="
                    markAllDeal(stage.id, !markedStage.has(stage.id))
                  "
                  :class="{ selected: markedStage.has(stage.id) }"
                >
                  <a class="text-primary" href="#">{{
                    markedStage.has(stage.id) ? $t("clear_all") : $t("mark_all")
                  }}</a>
                </div>
              </div>
              <draggable
                :options="{ disabled: $can('update_deals') ? false : true }"
                :data-stage="stage.id"
                @end="dragEnd"
                :move="checkMove"
                @change="posChanged"
                ghostClass="pipex-sortable-ghost"
                chosenClass="pipex-sortable-chosen"
                dragClass="pipex-sortable-drag"
                forceFallback="true"
                animation="150"
                easing="cubic-bezier(1, 0, 0, 1)"
                class="kanban-draggable-column"
                :list="stagesData[stage.id]"
                group="deals"
              >
                <div
                  class="
                    card
                    deal-card
                    card-with-shadow
                    border-0
                    draggable-item
                  "
                  :data-id="element.id"
                  v-for="(element, ix) in stagesData[stage.id]"
                  :key="ix"
                  :class="[
                    ix == stagesData[stage.id].length - 1 ? '' : 'mb-2',
                    ix == 0 ? 'mt-2' : '',
                    computedHighlights.indexOf(element.id) > -1
                      ? 'highlights'
                      : '',
                    stage.name == $t('close_deals') || !$can('update_deals') ? 'cursor-default' : '',
                  ]"
                >
                  <div class="card-body font-size-90">
                    <div class="row py-2">
                      <div class="col-10">
                        <div class="media d-flex align-items-start mb-3">
                          <a
                            href=""
                            :class="{
                              disabled: $can('update_deals') ? false : true,
                            }"
                            @click.prevent="selectDeal(element.id, stage.id)"
                          >
                              <app-avatar
                                  class="mr-2 profile-img"
                                  :title="
                                element.contextable
                                  ? element.contextable.name
                                  : ''
                              "
                                  :img="
                                element.contextable
                                  ? element.contextable.profile_picture
                                    ? urlGenerator(
                                        element.contextable.profile_picture.path
                                      )
                                    : element.contextable.profile_picture
                                  : ''
                              "
                                  :avatar-class="
                                'avatars-w-30 ' + element.title.substring(0, 1)
                              "
                                  :alt-text="$t('not_found')"
                              />
                          </a>

                          <a
                            href=""
                            @click.prevent="openDealDetailsModal(element)"
                          >
                            <div class="media-body">
                              <span class="text-muted">
                                  {{ currentPipeline }}
                              </span>
                              <span
                                >#{{ element.id }} <br />
                                <span>
                                  {{ element.title }}
                                </span>
                              </span>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-2">
                        <div
                          class="dropdown options-dropdown d-inline-block"
                          v-if="
                            $can('create_activities') ||
                            $can('update_deals') ||
                            $can('delete_deals')
                          "
                        >
                          <button
                            type="button"
                            class="btn-option btn"
                            data-toggle="dropdown"
                            :title="$t('actions')"
                          >
                            <app-icon name="more-vertical" />
                          </button>
                          <div
                            class="dropdown-menu dropdown-menu-right py-2 mt-1"
                          >
                              <a
                                  v-if="$can('create_activities')"
                                  class="dropdown-item px-4 py-2 font-size-90"
                                  @click.prevent="openActivityModal(element)"
                                  href="#"
                              >
                                  {{ $t("add_activity") }}
                              </a>
                              <a
                                  v-if="$can('update_deals')"
                                  class="dropdown-item px-4 py-2 font-size-90"
                                  @click.prevent="openTagModal(element)"
                                  href="#"
                              >
                                  {{ $t("manage_tag") }}
                              </a>
                              <a v-if="element.status.name == 'status_open'"
                                 class="dropdown-item px-4 py-2 font-size-90"
                                 @click.prevent="dealWon(element)"
                                 href="#"
                              >
                                  {{ $t("won_the_deal") }}
                              </a>
                              <a v-if="element.status.name == 'status_open'"
                                  class="dropdown-item px-4 py-2 font-size-90"
                                  @click.prevent="dealLost(element)"
                                  href="#"
                              >
                                  {{ $t("make_it_lost") }}
                              </a>
                              <a
                                  v-if="$can('update_deals')"
                                  class="dropdown-item px-4 py-2 font-size-90"
                                  @click.prevent="selectDeal(element.id, stage.id)"
                                  href="#"
                              >
                                  {{
                                      computedHighlights.indexOf(element.id) > -1
                                          ? $t("unmark_deal")
                                          : $t("mark_deal")
                                  }}
                              </a>
                              <a
                                  v-if="$can('update_deals')"
                                  class="dropdown-item px-4 py-2 font-size-90"
                                  @click.prevent="openDealModal(element.id)"
                                  href="#"
                              >
                                  {{ $t("edit") }}
                              </a>
                              <a
                                  v-if="$can('delete_deals')"
                                  class="dropdown-item px-4 py-2 font-size-90"
                                  @click.prevent="deleteDeal(element.id)"
                                  href="#"
                              >
                                  {{ $t("delete") }}
                              </a>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div
                      v-if="element.contextable && cardDataManager.lead"
                      class="
                        text-muted
                        d-flex-inline
                        justify-content-between
                        py-2
                        px-1
                      "
                      :key="
                        element.contextable_type ===
                        'App\\Models\\CRM\\Organization\\Organization'
                          ? 'briefcase'
                          : 'user'
                      "
                    >
                        <template>
                            <app-icon
                                :name="
                          element.contextable_type ===
                          'App\\Models\\CRM\\Organization\\Organization'
                            ? 'briefcase'
                            : 'user'
                        "
                                class="size-16 mb-1 mr-1"
                            />
                            <a
                                class="mr-1"
                                :href="
                          urlGenerator(
                            `${
                              element.contextable_type ==
                              'App\\Models\\CRM\\Organization\\Organization'
                                ? 'organizations'
                                : 'persons'
                            }/${element.contextable.id}/edit`
                          )
                        "
                            >
                                {{ element.contextable.name }}
                            </a>
                        </template>

                      <template
                        v-if="
                          element.contextable_type ===
                          'App\\Models\\CRM\\Organization\\Organization'
                        "
                      >
                        <!-- <b class="mr-1 ml-1">|</b> -->
                        <template v-if="element['contact_person'][0]">
                          <app-icon name="user" class="size-16 mb-1" />
                          <a
                            :href="
                              urlGenerator(
                                `persons/${element['contact_person'][0]['id']}/edit`
                              )
                            "
                          >
                            {{ element["contact_person"][0]["name"] }}
                          </a>
                        </template>
                        <template v-else>
                          <app-icon name="user" class="size-16 mb-1" />
                          {{ $t("no_contact") }}
                        </template>
                      </template>
                    </div>
                    <div
                      v-else
                      class="
                        text-muted
                        d-flex-inline
                        justify-content-between
                        py-2
                        px-1
                      "
                    >
                      <p class="text-muted font-size-90 mb-2">
                        {{ cardDataManager.lead ? $t("no_lead_added") : '' }}
                      </p>
                    </div>
                    <div
                      class="media d-flex align-items-start mb-1 p-1"
                      v-if="element.owner"
                    >
                        <template v-if="cardDataManager.owner">
                            <app-avatar
                                class="mr-2"
                                :title="element.owner.full_name"
                                avatar-class="avatars-w-20"
                                :img="
                          element.owner.profile_picture
                            ? urlGenerator(element.owner.profile_picture.path)
                            : element.owner.profile_picture
                        "
                                :alt-text="$t('not_found')"
                            />
                            <div
                                class="media-body"
                                data-toggle="tooltip"
                                title="Owner"
                            >
                                {{ element.owner.full_name }}
                            </div>
                        </template>
                      <span class="font-size-90 deal-value">
                          {{ cardDataManager.value ? numberWithCurrencySymbol(element.value) : '' }}
                      </span>
                    </div>
                    <template>
                      <hr
                        class="mt-0"
                        v-if="
                          (element.tags.length && cardDataManager.tags) ||
                          (element.done_activity_count && cardDataManager.todoActivity) ||
                          (element.to_do_activity_count && cardDataManager.doneActivity) ||
                          (element.proposals_count && cardDataManager.proposal) ||
                          (element.discussions.length && cardDataManager.discussion)
                        "
                      />
                      <div class="row">
                        <div
                          v-if="element.tags.length > 0 && cardDataManager.tags"
                          class="
                            col-12
                            d-flex
                            tags-background
                            flex-wrap
                            justify-content-start
                          "
                        >
                          <span
                            class="
                              badge badge-sm badge-pill
                              text-capitalize
                              mr-1
                              mb-1
                              badge-text-truncate
                            "
                            v-for="(tag, index) in element.tags"
                            :style="{
                              'background-color': tag.color_code,
                            }"
                            :key="index"
                            >{{ tag.name }}
                          </span>
                        </div>

                        <div class="col-12 d-flex">
                          <template>
                            <span
                              class="badge badge-pill deal-activity py-1 mr-2"
                              data-toggle="tooltip"
                              title="Total Todo Activity"
                              v-if="element.to_do_activity_count > 0 && cardDataManager.todoActivity"
                            >
                                <app-icon name="activity" class="size-16" />
                                <span class="badge badge-round ml-2 activity">
                                    {{ element.to_do_activity_count }}
                                </span>
                            </span>
                            <span
                              class="badge badge-pill deal-activity py-1 mr-2"
                              data-toggle="tooltip"
                              title="Total Done Activity"
                              v-if="element.done_activity_count > 0 && cardDataManager.doneActivity"
                            >
                                <app-icon name="activity" class="size-16" />
                                <span class="badge badge-round activity">
                                    {{ element.done_activity_count }}
                                </span>
                            </span>
                          </template>
                          <span
                            class="badge badge-pill deal-activity py-1 mr-2"
                            data-toggle="tooltip"
                            title="Total proposal"
                            v-if="element.proposals_count > 0 && cardDataManager.proposal"
                          >
                              <app-icon name="hexagon" class="size-16" />
                              <span class="badge badge-round ml-2 deal-activity">
                                  {{ element.proposals_count }}
                              </span>
                          </span>

                          <span
                            class="badge badge-pill deal-activity py-1 mr-2"
                            data-toggle="tooltip"
                            title="comments"
                            v-if="element.discussions.length > 0 && cardDataManager.discussion"
                          >
                              <app-icon name="message-square" class="size-16" />
                              <span class="badge badge-round ml-2 deal-activity">
                                  {{ element.discussions.length }}
                              </span>
                          </span>
                        </div>
                      </div>
                    </template>
                    <hr v-if="cardDataManager.createdDate" class="mt-0" />
                    <div class="col-12 p-0 mt-1" v-if="cardDataManager.createdDate">
                      <em class="float-right font-size-90"
                        >{{ $t("created_at") }} :
                        <b>
                          {{ createdInfoShowAsHumanize(element.created_at) }}</b
                        >
                      </em>
                    </div>
                  </div>
                </div>
              </draggable>
              <div
                v-if="$can('create_deals')"
                class="draggable-action-wrapper position-absolute w-100"
              >
                <button
                  class="btn btn-stage-action shadow"
                  slot="footer"
                  data-toggle="modal"
                  @click="openModal(stage.id)"
                >
                  <app-icon name="plus" class="mr-1" />
                  {{ $t("add") }}
                </button>
              </div>
            </template>
            <template v-else>
              <div class="p-3 mb-2 stage-header rounded-top">
                <h6 class="text-truncate">{{ stage.name }}</h6>
              </div>
              <draggable
                :data-stage="stage.id"
                @end="dragEnd"
                :move="checkMove"
                @change="posChanged"
                class="kanban-draggable-column"
                :list="stagesData[stage.id]"
                group="deals"
              >
                <div
                  class="card card-with-shadow mb-2 border-0 draggable-item"
                  :data-id="element.id"
                  v-for="(element, ix) in stagesData[stage.id]"
                  :key="ix"
                  :class="[
                    computedHighlights.indexOf(element.id) > -1
                      ? 'highlights'
                      : '',
                  ]"
                >
                  <a :href="urlGenerator(`deal/${element.id}/details`)">
                    <div class="card-body font-size-90">
                      <div class="media d-flex align-items-start mb-3">
                        <app-avatar
                          :title="element.title"
                          class="mr-2"
                          :avatar-class="
                            'avatars-w-20 ' + element.title.substring(0, 1)
                          "
                          :img="''"
                          :alt-text="$t('not_found')"
                        />
                        <div class="media-body">
                          {{ element.title }}
                        </div>
                      </div>
                      <div
                        class="
                          text-muted
                          d-flex
                          align-items-center
                          justify-content-between
                        "
                      >
                        <div
                          class="media d-flex align-items-center"
                          v-if="element.owner"
                        >
                          <app-avatar
                            class="mr-2"
                            :title="element.owner.full_name"
                            avatar-class="avatars-w-20"
                            :img="
                              element.owner.profile_picture
                                ? element.owner.profile_picture.path
                                : element.owner.profile_picture
                            "
                            :alt-text="$t('not_found')"
                          />
                          <div class="media-body">
                            {{ element.owner.full_name }}
                          </div>
                        </div>
                        <span class="font-size-90 deal-value">{{
                          numberWithCurrencySymbol(element.value)
                        }}</span>
                      </div>
                    </div>
                  </a>
                </div>
              </draggable>
            </template>
          </div>

            <div class="kanban-column position-relative won-lost-bg-color">
                <template v-if="stagesData[0]">
                    <div class="py-3 stage-header rounded-top row">
                        <div class="col-10">
                            <h6 class="stage-name text-truncate">
                                {{ $t('won_or_lost_deals') }}
                            </h6>
                            <div
                                class="
                      text-muted
                      d-flex
                      flex-wrap
                      align-items-center
                      stage-information
                    "
                            >
                    <span>
                      {{
                            numberWithCurrencySymbol(
                                !isUndefined(collect(totalDealsValues).find(0))
                                    ? collect(totalDealsValues).find(0).value
                                    : ""
                            )
                        }}
                    </span>
                                <span
                                >{{ stagesData[0].length }} {{ $t("deal") }}</span
                                >
                                <span>{{ 100 }}%</span>
                            </div>
                        </div>
                        <div class="col-2 collapse-btn">
                            <div @click="pipelineCollapse(Object.keys(stagesData).length - 1)" class="collapse-icon">
                                <app-icon
                                    stroke-width="1"
                                    name="chevrons-left"
                                    class="size-16"
                                />
                            </div>
                        </div>
                        <div
                            v-if="
                    filterValues.highlights.length > 0 &&
                    stagesData[0].length
                  "
                            :title="
                    markedStage.has(0) ? $t('clear_all') : $t('mark_all')
                  "
                            class="select-icon mt-2 col-12"
                            @click.prevent="
                    markAllDeal(0, !markedStage.has(0))
                  "
                            :class="{ selected: markedStage.has(0) }"
                        >
                            <a class="text-primary" href="#">{{
                                    markedStage.has(0) ? $t("clear_all") : $t("mark_all")
                                }}</a>
                        </div>
                    </div>
                    <draggable
                        :options="{ disabled: true}"
                        :data-stage="0"
                        @end="dragEnd"
                        :move="checkMove"
                        @change="posChanged"
                        ghostClass="pipex-sortable-ghost"
                        chosenClass="pipex-sortable-chosen"
                        dragClass="pipex-sortable-drag"
                        forceFallback="true"
                        animation="150"
                        easing="cubic-bezier(1, 0, 0, 1)"
                        class="kanban-draggable-column"
                        :list="stagesData[0]"
                        group="deals"
                    >
                        <div
                            class="
                    card
                    deal-card
                    card-with-shadow
                    border-0
                    draggable-item
                  "
                            :data-id="element.id"
                            v-for="(element, ix) in stagesData[0]"
                            :key="ix"
                            :class="[
                    ix == stagesData[0].length - 1 ? '' : 'mb-2',
                    ix == 0 ? 'mt-2' : '',
                    computedHighlights.indexOf(element.id) > -1
                      ? 'highlights'
                      : '',
                    'cursor-default',
                  ]"
                        >
                            <div class="card-body font-size-90">
                                <div class="row py-2">
                                    <div class="col-10">
                                        <div class="media d-flex align-items-start mb-3">
                                            <a
                                                href=""
                                                :class="{
                                                          disabled: true,
                                                        }"
                                                @click.prevent="selectDeal(element.id, 0)"
                                            >
                                                <app-avatar
                                                    class="mr-2 profile-img"
                                                    :title="
                                                        element.contextable
                                                          ? element.contextable.name
                                                          : ''
                                                      "
                                                    :img="
                                                        element.contextable
                                                          ? element.contextable.profile_picture
                                                            ? urlGenerator(
                                                                element.contextable.profile_picture.path
                                                              )
                                                            : element.contextable.profile_picture
                                                          : ''
                                                      "
                                                    :avatar-class="
                                                        'avatars-w-30 ' + element.title.substring(0, 1)
                                                      "
                                                    :alt-text="$t('not_found')"
                                                />
                                            </a>

                                            <div>
                                                <a
                                                    href=""
                                                    @click.prevent="openDealDetailsModal(element)"
                                                >
                                                    <div class="media-body mb-2">
                                                      <span class="text-muted">
                                                          {{ currentPipeline}}
                                                      </span>
                                                        <span>
                                                            #{{ element.id }} <br />
                                                            <span>
                                                              {{ element.title }}
                                                            </span>
                                                          </span>
                                                    </div>
                                                </a>
                                                <span :class="`badge badge-pill badge-${element.status.class}`">
                                                {{ element.status.translated_name }}
                                            </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-2">
                                        <div
                                            class="dropdown options-dropdown d-inline-block"
                                        >
                                            <button
                                                type="button"
                                                class="btn-option btn"
                                                data-toggle="dropdown"
                                                :title="$t('actions')"
                                            >
                                                <app-icon name="more-vertical" />
                                            </button>

                                            <div
                                                class="dropdown-menu dropdown-menu-right py-2 mt-1"
                                            >
                                                <a v-if="element.status.name == 'status_won' || element.status.name == 'status_lost'"
                                                   class="dropdown-item px-4 py-2 font-size-90"
                                                   @click.prevent="dealReopen(element)"
                                                   href="#"
                                                >
                                                    {{ $t("reopen") }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="element.contextable && cardDataManager.lead"
                                    class="
                                        text-muted
                                        d-flex-inline
                                        justify-content-between
                                        py-2
                                        px-1
                                      "
                                    :key="
                                        element.contextable_type ===
                                        'App\\Models\\CRM\\Organization\\Organization'
                                          ? 'briefcase'
                                          : 'user'
                                      "
                                >
                                    <template>
                                        <app-icon
                                            :name="
                                          element.contextable_type ===
                                          'App\\Models\\CRM\\Organization\\Organization'
                                            ? 'briefcase'
                                            : 'user'
                                        "
                                            class="size-16 mb-1 mr-1"
                                        />
                                        <a
                                            class="mr-1"
                                            :href="
                                          urlGenerator(
                                            `${
                                              element.contextable_type ==
                                              'App\\Models\\CRM\\Organization\\Organization'
                                                ? 'organizations'
                                                : 'persons'
                                            }/${element.contextable.id}/edit`
                                          )
                                        "
                                        >
                                            {{ element.contextable.name }}
                                        </a>
                                    </template>
                                    <template
                                        v-if="
                                          element.contextable_type ===
                                          'App\\Models\\CRM\\Organization\\Organization'
                                        "
                                    >
                                        <!-- <b class="mr-1 ml-1">|</b> -->
                                        <template v-if="element['contact_person'][0]">
                                            <app-icon name="user" class="size-16 mb-1" />
                                            <a
                                                :href="
                                                  urlGenerator(
                                                    `persons/${element['contact_person'][0]['id']}/edit`
                                                  )
                                                "
                                            >
                                                {{ element["contact_person"][0]["name"] }}
                                            </a>
                                        </template>
                                        <template v-else>
                                            <app-icon name="user" class="size-16 mb-1" />
                                            {{ $t("no_contact") }}
                                        </template>
                                    </template>
                                </div>
                                <div
                                    v-else
                                    class="
                                    text-muted
                                    d-flex-inline
                                    justify-content-between
                                    py-2
                                    px-1
                                  "
                                >
                                    <p class="text-muted font-size-90 mb-2">
                                        {{ cardDataManager.lead ? $t("no_lead_added") : '' }}
                                    </p>
                                </div>
                                <div
                                    class="media d-flex align-items-start mb-1 p-1"
                                    v-if="element.owner"
                                >
                                    <template v-if="cardDataManager.owner">
                                        <app-avatar
                                            class="mr-2"
                                            :title="element.owner.full_name"
                                            avatar-class="avatars-w-20"
                                            :img="
                                              element.owner.profile_picture
                                                ? urlGenerator(element.owner.profile_picture.path)
                                                : element.owner.profile_picture
                                            "
                                            :alt-text="$t('not_found')"
                                        />
                                        <div
                                            class="media-body"
                                            data-toggle="tooltip"
                                            title="Owner"
                                        >
                                            {{ element.owner.full_name }}
                                        </div>
                                    </template>
                                    <span class="font-size-90 deal-value">
                                        {{ cardDataManager.value ? numberWithCurrencySymbol(element.value) : '' }}
                                    </span>
                                </div>
                                <template>
                                    <hr
                                        class="mt-0"
                                        v-if="
                                          (element.tags.length && cardDataManager.tags) ||
                                          (element.done_activity_count && cardDataManager.todoActivity) ||
                                          (element.to_do_activity_count && cardDataManager.doneActivity) ||
                                          (element.proposals_count && cardDataManager.proposal) ||
                                          (element.discussions.length && cardDataManager.discussion)
                                        "
                                    />
                                    <div class="row">
                                        <div
                                            v-if="element.tags.length > 0 && cardDataManager.tags"
                                            class="
                                                col-12
                                                d-flex
                                                tags-background
                                                flex-wrap
                                                justify-content-start
                                              "
                                        >
                                          <span
                                              class="
                                              badge badge-sm badge-pill
                                              text-capitalize
                                              mr-1
                                              mb-1
                                              badge-text-truncate
                                            "
                                              v-for="(tag, index) in element.tags"
                                              :style="{
                                              'background-color': tag.color_code,
                                            }"
                                              :key="index"
                                          >{{ tag.name }}
                                          </span>
                                        </div>

                                        <div class="col-12 d-flex">
                                            <template>
                                                <span
                                                    class="badge badge-pill deal-activity py-1 mr-2"
                                                    data-toggle="tooltip"
                                                    title="Total Todo Activity"
                                                    v-if="element.to_do_activity_count > 0 && cardDataManager.todoActivity"
                                                >
                                                  <app-icon name="activity" class="size-16" />
                                                  <span class="badge badge-round ml-2 activity">{{
                                                          element.to_do_activity_count
                                                      }}</span>
                                                </span>
                                                <span
                                                    class="badge badge-pill deal-activity py-1 mr-2"
                                                    data-toggle="tooltip"
                                                    title="Total Done Activity"
                                                    v-if="element.done_activity_count > 0 && cardDataManager.doneActivity"
                                                >
                                              <app-icon name="activity" class="size-16" />
                                              <span class="badge badge-round activity">{{
                                                      element.done_activity_count
                                                  }}</span>
                                            </span>
                                            </template>
                                            <span
                                                class="badge badge-pill deal-activity py-1 mr-2"
                                                data-toggle="tooltip"
                                                title="Total proposal"
                                                v-if="element.proposals_count > 0 && cardDataManager.proposal"
                                            >
                                                <app-icon name="hexagon" class="size-16" />
                                                <span
                                                    class="badge badge-round ml-2 deal-activity"
                                                >{{ element.proposals_count }}</span
                                                >
                                              </span>

                                            <span
                                                class="badge badge-pill deal-activity py-1 mr-2"
                                                data-toggle="tooltip"
                                                title="comments"
                                                v-if="element.discussions.length > 0 && cardDataManager.discussion"
                                            >
                                            <app-icon name="message-square" class="size-16" />
                                            <span
                                                class="badge badge-round ml-2 deal-activity"
                                            >{{ element.discussions.length }}</span
                                            >
                                          </span>
                                        </div>
                                    </div>
                                </template>
                                <hr v-if="cardDataManager.createdDate" class="mt-0" />
                                <div class="col-12 p-0 mt-1" v-if="cardDataManager.createdDate">
                                    <em class="float-right font-size-90"
                                    >{{ $t("created_at") }} :
                                        <b>
                                            {{ createdInfoShowAsHumanize(element.created_at) }}</b
                                        >
                                    </em>
                                </div>
                            </div>
                        </div>
                    </draggable>
                </template>
                <template v-else>
                    <div class="p-3 mb-2 stage-header rounded-top">
                        <h6 class="text-truncate">{{ $t('close_deals') }}</h6>
                    </div>
                    <draggable
                        :data-stage="0"
                        @end="dragEnd"
                        :move="checkMove"
                        @change="posChanged"
                        class="kanban-draggable-column"
                        :list="stagesData[0]"
                        group="deals"
                    >
                        <div
                            class="card card-with-shadow mb-2 border-0 draggable-item"
                            :data-id="element.id"
                            v-for="(element, ix) in stagesData[0]"
                            :key="ix"
                            :class="[
                    computedHighlights.indexOf(element.id) > -1
                      ? 'highlights'
                      : '',
                  ]"
                        >
                            <a :href="urlGenerator(`deal/${element.id}/details`)">
                                <div class="card-body font-size-90">
                                    <div class="media d-flex align-items-start mb-3">
                                        <app-avatar
                                            :title="element.title"
                                            class="mr-2"
                                            :avatar-class="
                            'avatars-w-20 ' + element.title.substring(0, 1)
                          "
                                            :img="''"
                                            :alt-text="$t('not_found')"
                                        />
                                        <div class="media-body">
                                            {{ element.title }}
                                        </div>
                                    </div>
                                    <div
                                        class="
                          text-muted
                          d-flex
                          align-items-center
                          justify-content-between
                        "
                                    >
                                        <div
                                            class="media d-flex align-items-center"
                                            v-if="element.owner"
                                        >
                                            <app-avatar
                                                class="mr-2"
                                                :title="element.owner.full_name"
                                                avatar-class="avatars-w-20"
                                                :img="
                              element.owner.profile_picture
                                ? element.owner.profile_picture.path
                                : element.owner.profile_picture
                            "
                                                :alt-text="$t('not_found')"
                                            />
                                            <div class="media-body">
                                                {{ element.owner.full_name }}
                                            </div>
                                        </div>
                                        <span class="font-size-90 deal-value">{{
                                                numberWithCurrencySymbol(element.value)
                                            }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </draggable>
                </template>
            </div>

        </div>
        <div class="card border-0 min-height-400" v-else>
          <app-overlay-loader />
        </div>
      </div>
    </div>
    <!-- Activity Modal-->
    <component
      :is="'app-activity-modal'"
      v-if="isActivityModalActive"
      :table-id="tableId"
      :previousData="true"
      :selectData="activityData"
      @close-modal="closeActivityModal()"
      @save="refreshState()"
    />
    <!-- End of Activity Modal -->
    <!--Deal Add Edit Modal-->
    <app-deal-modal
      v-if="isDealModalActive"
      :table-id="tableId"
      :selected-url="dealUrl"
      :selected-stage-index="selectedStageId"
      :pipeline-id="localPipelineId"
      @close-modal="closeModal()"
      @saved="refreshState()"
    />
    <pipeline-delete-modal
      v-if="isDeletePipelineModal"
      :modalId="'pipeline-delete-modal'"
      :pipelineId="localPipelineId"
      :deletePipelineUrl="`pipelines/${localPipelineId}`"
      @close-modal="closePipelineDeleteModal"
    />

    <app-confirmation-modal
      v-if="dealDeleteModal"
      modal-id="deal-delete-modal"
      @confirmed="confirmed"
      @cancelled="cancelled"
    />
    <app-lost-reason-modal
      v-if="isModalActive"
      :table-id="tableId"
      :selected-url="selectedUrlReason"
      @close-modal="closeLostReasonModal"
    />

      <app-confirmation-modal
          v-if="isReopenModalActive"
          modal-id="deal-reopen-modal"
          :message="$t('are_you_sure_want_to_reopen_this_deal')"
          @confirmed="confirmedReopen"
          @cancelled="cancelledReopenModal"
      />
    <app-move-all-deal-modal
      v-if="isMoveDealModalActive"
      :table-id="tableId"
      :pipeline-name="currentPipeline"
      :pipelines="pipelines"
      :stages="stages"
      :deals="stagesData"
      :pipeline-id="localPipelineId"
      @close-modal="closeMoveAllDeal"
      @refresh="refreshState"
    />

    <deal-tag-manage-modal
      v-if="isTagModalActive"
      :taggable-id="taggableId"
      :tag-list="tagList"
      :post-url="'deal/tags/'"
      @close-modal="closeTagModal"
      @changed-tag-list="refreshTagList"
    />

    <app-bulk-action
      v-if="filterValues.highlights.length"
      :highlight-ids="filterValues.highlights"
      :pipelines="pipelines"
      :pipeline-id="localPipelineId"
      :selected-deal="computedHighlights"
      @clear="clearMarkedDeals"
      @refresh="refreshState"
    />

    <deal-details-modal
      v-if="isDetailsViewActive"
      :selected-deal="selectedDeal"
      :pipelines="pipelines"
      :stages="stagesProperty"
      @deal-update="dealUpdated(filterValues)"
      @close-modal="closeDetailsViewModal"
    />

      <app-deal-won-confirm-modal
          v-if="isWonModal"
          modal-id="won-deal-confirm-modal"
          :dealWonData="dealWonData"
          @success="afterUpdated"
          @won-close-modal="wonCloseModal"
      />

      <app-deal-lost-confirm-modal
          v-if="isDealLostConfirmModal"
          modal-id="lost-deal-confirm-modal"
          :dealLostReasonData="dealLostData"
          @success="afterUpdated"
          @close-modal="closeDealLostModal"
      />
  </div>
</template>

<script>
import draggable from "vuedraggable";
import queryString from "query-string";
import { FormMixin } from "@core/mixins/form/FormMixin";
import {
  getCurrencySymbol,
  numberWithCurrencySymbol,
  urlGenerator,
} from "@app/Helpers/helpers";
import { collect } from "@app/Helpers/Collection";
import {
  contactType,
  dealStatus,
  owner,
  tag,
} from "@app/Mixins/Global/FilterMixins";
import { getCustomFileds } from "@app/Mixins/Global/CustomFieldMixin";
import PipelineDeleteModal from "./Pipeline/PipelineDeleteModal";
import PipelineColumnManager from "../../DatatableMixins/PipelineColumnManager"

export default {
  name: "KanbanView",
  components: { PipelineDeleteModal, draggable },

  mixins: [FormMixin, getCustomFileds, dealStatus, tag, owner, contactType, PipelineColumnManager],
  props: ["pipeline", "selectedUrl", "highlights"],
  data() {
    return {
      numberWithCurrencySymbol,
      getCurrencySymbol,
      urlGenerator,
      collect,
      route,
      isDealModalActive: false,
      dealUrl: "",
      tableId: "deal-modal",
      isDeletePipelineModal: false,
      dealDeleteModal: false,
      dealDeleteId: "",
      isModalActive: false,
      isMoveDealModalActive: false,
      isTagModalActive: false,
      taggableId: null,
      tagList: [],
      selectedUrlReason: "",
      stagesProperty: [],
      stages: [],
      stagesData: [],
      activityData: {
        type_of_activity: 1,
        contextable_id: null,
      },
      formData: {},
      isActivityModalActive: false,
      isDetailsViewActive: false,
      selectedDeal: {},
      dealCardMoved: false,
      dataLoaded: false,
      filterDataLoaded: false,
      totalDealsValues: [],
      localPipelineId: null,
      selectedStageId: null,
      // for filter and search
      visibleClearFilter: false,
      isFiltersOpen: true,
      filterValues: {
        highlights: [],
      },
      searchValue: "",
      currentPipeline: "",
      currentDeal: {},
      pipelines: [],
      selectedDealId: null,
      isReloadButton: false,
        isWonModal: false,
        isDealLostConfirmModal: false,
        isReopenModalActive: false,
        dealWonData: {},
        dealLostData: {},
        dealReopenData: {},
      options: {
        filters: [
          {
            title: this.$t("owner"),
            type: "checkbox",
            key: "owner_is",
            option: [],
            permission: this.$can("manage_public_access") ? true : false,
          },
          {
            title: this.$t("lead_group"),
            type: "checkbox",
            key: "contact_type",
            option: [],
            permission: this.$can("view_types") ? true : false,
          },
          {
            title: "Created date",
            type: "range-picker",
            key: "created_date",
            option: ["today", "thisMonth", "last7Days", "thisYear"],
          },
          {
            title: this.$t('deals_with_proposals'),
            type: "radio",
            key: "deal_with_proposal",
            header: {
              title: "Want to filter your deal?",
              description:
                "You can filter your deal list which don't have any proposal",
            },
            option: [
              {
                id: 1,
                name: this.$t('with_proposals'),
              },
              {
                id: 2,
                name: this.$t('without_proposals'),
              },
            ],
            listValueField: "name",
          },
          {
            title: this.$t("tag"),
            type: "multi-select-filter",
            key: "tags",
            option: [],
          },
          {
            title: this.$t("deal_value"),
            type: "range-filter",
            key: "deal_value",
            minTitle: this.$t("minimum_value"),
            maxTitle: this.$t("maximum_value"),
            maxRange: 100,
            minRange: 0,
          },
        ],
      },
      markedStage: new Set(),
      markedPipeline: new Set(),
      currentStageId: Number,
    };
  },
  computed: {
    computedHighlights() {
      if (this.selectedDealId) {
        let dealId = this.selectedDealId;
        let idx = this.filterValues.highlights.indexOf(dealId);
        if (idx !== -1) {
          this.markedPipeline.delete(this.filterValues.pipeline);
          this.filterValues.highlights.splice(idx, 1);

          if (!this.checkStageAllMarked()) {
            this.markedStage.delete(this.currentStageId);
          }
        } else {
          if (typeof dealId !== "boolean") {
            this.filterValues.highlights.push(dealId);

            if (this.checkStageAllMarked()) {
              this.markedStage.add(this.currentStageId);
            }
          }
        }
        this.selectedDealId = null;
      }

      this.setUrlSearhParams();
      return this.filterValues.highlights;
    },
    filterDealCount() {
      let dealCount = 0;
      for (let key in this.stagesData) {
        dealCount += this.stagesData[key].length;
      }

      return dealCount;
    },
  },
  created() {
    this.filterValues.highlights = this.$props.highlights
      ? this.$props.highlights
          .trim()
          .split(",")
          .map((e) => Number(e))
      : [];
  },
  methods: {
    pipexEventHandeler() {
      this.$hub.$on("pipexDealChanged", (data) => {
        if (data.model.deal.pipeline_id == this.filterValues.pipeline) {
          this.$toastr.i(data.msg);
          this.isReloadButton = true;
        }
      });
    },
    openDealDetailsModal(deal) {
      this.selectedDeal = deal;
      this.selectedDeal.lead_type =
        deal.contextable_type == "App\\Models\\CRM\\Person\\Person" ? 1 : 2;
      this.isDetailsViewActive = true;
    },
    closeDetailsViewModal() {
      this.isDetailsViewActive = false;
      this.selectedDeal = {};
      $("#detailsViewModal").modal("hide");
    },
    dealUpdated(value) {
      // For visible Clear Filter
      this.checkClearFilterVisibility(value);

      // Data getting after filter
      value.highlights = this.filterValues.highlights;
      if (!value["pipeline"]) {
        value["pipeline"] = Number(this.localPipelineId);
      }
      this.filterValues = value;

      //pipeline dropdown navigation
      if (this.filterValues["pipeline"]) {
        this.localPipelineId = Number(value.pipeline);
        this.getData()
            .then(() => {
                this.setProps(this.stages);
            })
            .then(() => {
            setTimeout(() => {
              this.setUrlSearhParams();
            }, 200);
          });
      }
    },
    checkStageAllMarked() {
      return collect(this.stagesData[this.currentStageId])
        .pluck("id")
        .every(this.checkStageMarkingState);
    },
    moveAllDeal() {
      this.isMoveDealModalActive = true;
    },
    closeMoveAllDeal() {
      this.isMoveDealModalActive = false;
      $("#move-deal-modal").modal("hide");
    },
    checkStageMarkingState(el) {
      return this.filterValues.highlights.includes(el) ? true : false;
    },
    createdInfoShowAsHumanize(createdAt) {
      let duration = moment().diff(createdAt, "seconds");
      return duration < 60 * 60 * 24
        ? moment.duration(duration, "seconds").humanize()
        : moment(createdAt).format("DD MMM YY");
    },
    markAllDeal(stageId, markReq) {
      this.selectedDealId = true;
      if (markReq) {
        this.markedStage.add(stageId);
        this.stagesData[stageId].forEach((e) => {
          if (this.filterValues.highlights.indexOf(e.id) < 0) {
            this.filterValues.highlights.push(e.id);
          }
        });
      } else {
        this.markedStage.delete(stageId);
        this.stagesData[stageId].forEach((e) => {
          let idx = this.filterValues.highlights.indexOf(e.id);
          if (idx !== -1) {
            this.filterValues.highlights.splice(idx, 1);
          }
        });
      }
    },
    MarkedAllDealsOfPipeline(req) {
      req
        ? this.markedPipeline.add(this.filterValues.pipeline)
        : this.markedPipeline.delete(this.filterValues.pipeline);
      this.stages.forEach((el) => {
        if (el.pipeline_id == this.filterValues.pipeline) {
          this.markAllDeal(el.id, req);
        }
      });
    },
    clearMarkedDeals() {
      this.selectedDealId = true;
      this.filterValues.highlights = [];
      this.markedStage = new Set();
      this.markedPipeline = new Set();
      this.setUrlSearhParams();
    },
    pipelineCollapse(index) {
      let kanbans = document.getElementsByClassName("kanban-column");
      let headers = document.getElementsByClassName("stage-header");
      kanbans[index].classList.toggle("pipeline-collapse");
      headers[index].classList.toggle("row");
      // console.log(kanbans[index]);
    },
    openTagModal(deal) {
      this.isTagModalActive = true;
      this.taggableId = deal.id;
      this.tagList = deal.tags;
      $("#tag-modal").modal("show");
    },
    closeTagModal() {
      this.isTagModalActive = false;
      this.taggableId = null;
      this.tagList = [];
      $("#tag-modal").modal("hide");
    },
    refreshTagList() {
      this.refreshState();
    },
    selectDeal(dealId, stageId) {
      this.currentStageId = stageId;
      this.selectedDealId = dealId;
    },
    setUrlSearhParams() {
      let params = queryString.stringify(this.filterValues, {
        arrayFormat: "comma",
      });
      var newurl =
        window.location.protocol +
        "//" +
        window.location.host +
        window.location.pathname +
        "?" +
        params;
      window.history.pushState({ path: newurl }, "", newurl);
    },
      dealReopen(deal){
          this.dealReopenData = deal;
          this.isReopenModalActive = true;
      },
      confirmedReopen() {
        let formData = {};
          formData.title = this.dealReopenData.title;
          formData.pipeline_id = this.dealReopenData.pipeline_id;
          formData.stage_id = this.dealReopenData.stage_id;
          formData.id = this.dealReopenData.id;

          this.axiosGet(
              route(`statuses.index`, {_query: { name: "status_open", type: "deal"}}))
              .then((res) => {
                  formData.status_id = collect(res.data).first().id;
                  this.axiosPatch({
                      url: route('deals.update', {id: formData.id}),
                      data: formData,
                  }).then((response) => {
                      this.getFilterValues(this.filterValues);
                      this.$toastr.s(response.data.message);
                  });
              });
      },
      cancelledReopenModal() {
          this.isReopenModalActive = false;
          this.dealReopenData = {};
          $("#deal-reopen-modal").modal("hide");
      },
      dealWon(deal) {
          this.isWonModal = true;
          this.dealWonData = deal;
          $("#won-deal-confirm-modal").modal("show");
      },
      wonCloseModal() {
          this.isWonModal = false;
          this.dealWonData = {};
          $("#won-deal-confirm-modal").modal("hide");
      },
      dealLost(deal){
          this.isDealLostConfirmModal = true;
          this.dealLostData = deal;
          $("#lost-deal-confirm-modal").modal("show");
      },
      afterUpdated(){
          this.getFilterValues(this.filterValues);
          this.closeDealLostModal();
          this.wonCloseModal();
      },
      closeDealLostModal() {
          this.isDealLostConfirmModal = false;
          this.dealLostData = {};
          $("#lost-deal-confirm-modal").modal("hide");
      },
    openActivityModal(deal) {
      this.activityData.contextable_id = deal.id;
      this.isActivityModalActive = true;
    },
    closeActivityModal(v) {
      this.isActivityModalActive = false;
      $("#activity-modal").modal("hide");
    },
    add() {
      if (this.newTask) {
        this.arrBackLog.push({ name: this.newTask });
        this.newTask = "";
      }
    },
    openDealModal(id) {
      this.dealUrl = `deals/${id}`;
      this.isDealModalActive = true;
    },
    openModal(stageId) {
      this.isDealModalActive = true;
      this.selectedStageId = stageId;
      setTimeout(function () {
        $("#deal-modal").modal("show");
      });
    },
    refreshState() {
      this.getFilterValues(this.filterValues);
      this.closeModal();
      this.isReloadButton = false;
    },
    closeModal() {
      this.isDealModalActive = false;
      this.dealUrl = undefined;
      $("#deal-modal").modal("hide");
    },
    dragEnd(evt) {
      if (this.dealCardMoved) {
        this.formData.stage_id = evt.to.dataset.stage;
        this.currentDeal = this.stagesData[this.formData.stage_id].find(
          (el) => el.id == this.formData.id
        );
        this.formData.title = this.currentDeal.title;
        this.axiosPatch({
          url: route("deals.update", { id: this.formData.id }),
          data: this.formData,
        })
          .then((res) => {
            this.dealCardMoved = false;
          })
          .catch((er) => console.log(er));
        setTimeout(() => {
          this.setTotalDealsValues();
        }, 100);
      }
    },
    posChanged(evt) {
      if (evt.removed) {
        this.formData.id = evt.removed.element.id;
        this.dealCardMoved = true;
        // console.log(evt.removed.element.stage_id, "removed");
      }
    },
    checkMove(evt) {
      //   console.log(evt.draggedContext.element.id, "dragged id");
    },
    setData(data) {
      this.stagesData = data;
    },
    setProps(data) {
      this.setStagesProperty(data).then(() => {
        this.stagesProperty = collect(this.stagesProperty)
          .sortBy("priority")
          .get();
      });
      this.seperateOpenCloseDeals();
        this.setTotalDealsValues();
    },
      seperateOpenCloseDeals(){
          let openDeals = [];
          let closeDeals = [];
          this.stagesProperty.map((stage)=>{
              openDeals = [];
              if (this.stagesData[stage.id]){
                  this.stagesData[stage.id].forEach(function (deal, index){
                      deal.status.name == 'status_open' ? openDeals.push(deal) : closeDeals.push(deal);;
                  })
              }
              this.stagesData[stage.id] = openDeals;
          })
          this.stagesData[0] = closeDeals;
      },
    async setStagesProperty(data) {
      this.stagesProperty = [];
      let pipelineIds = new Set();
      data.forEach((el) => {
        if (Number(el.pipeline_id) == this.localPipelineId) {
          this.stagesProperty.push(el);
          if (this.stagesData[el.id] == undefined) this.stagesData[el.id] = [];

          this.currentPipeline = el.pipeline.name;
        }
        // collect all available pipeline
        if (
          el.pipeline != null &&
          !pipelineIds.has(el.pipeline.id) &&
          !this.filterDataLoaded
        ) {
          pipelineIds.add(el.pipeline.id);
          this.pipelines.push({
            id: el.pipeline.id,
            name: el.pipeline.name,
          });
        }
      });
    },
    setTotalDealsValues() {
      let totalDealsValues = [];
      let totalDealsValue = 0;
      for (let key in this.stagesData) {
        if (this.stagesData[key].length > 0) {
          this.stagesData[key].forEach((element, i) => {
            totalDealsValue = Number(totalDealsValue) + Number(element.value);
            if (i + 1 == this.stagesData[key].length) {
              let totalValue = {
                id: Number(key),
                value: Number(totalDealsValue),
              };
              totalDealsValues.push(totalValue);
              totalDealsValue = 0;
            }
          });
        } else {
          totalDealsValue = 0;
          let totalValue = {
            id: Number(key),
            value: Number(totalDealsValue),
          };
          totalDealsValues.push(totalValue);
        }
      }
      this.totalDealsValues = totalDealsValues;
    },
    getStages() {
      return this.axiosGet(route("stages.index", { _query: { all: true } }))
        .then((res) => {
          this.setProps(res.data);
          this.stages = res.data;
        })
        .catch((er) => console.log(er));
    },
    getPipeline() {
      if (this.localPipelineId == "") {
        return this.axiosGet(
          route("pipelines.index", { _query: { all: true } })
        ).then((res) => {
          if (!res.data[0]) {
            this.redirect(route("pipelines.create"));
          } else {
            this.localPipelineId = Number(res.data[0].id);
          }
        });
      }
      return Promise.resolve();
    },
    getData() {
      let pipelineId = this.localPipelineId;
      this.stagesData = [];
      let reqData = {};
      reqData.params = { ...this.filterValues };
      reqData.params.date = reqData.params.created_date;
      delete reqData.params.created_date;

      return this.axiosGet(
        route("deal.pipeline_view", { pipeline_id: pipelineId }),
        reqData
      )
        .then((res) => {
          this.dataLoaded = true;
          this.setData(res.data);
          // this.setTotalDealsValues();
        })
        .catch((er) => {
          if (er.response?.status == 404) {
            this.redirect(route("pipelines.create"));
          }
        });
    },
    redirect(url = "/") {
      window.location = urlGenerator(url);
    },
    // for filter and search

    getFilterValues(value) {
      // For visible Clear Filter
      this.checkClearFilterVisibility(value);

      // Data getting after filter
      value.highlights = this.filterValues.highlights;
      if (!value["pipeline"]) {
        value["pipeline"] = Number(this.localPipelineId);
      }
      this.filterValues = value;

      //pipeline dropdown navigation
      if (this.filterValues["pipeline"]) {
        this.localPipelineId = Number(value.pipeline);
        this.dataLoaded = false;
        this.getData()
            .then(() => {
                this.setProps(this.stages);
            })
          .then(() => {
            setTimeout(() => {
              this.dataLoaded = true;
              this.setUrlSearhParams();
            }, 200);
          });
      }
    },
    toggleFilters() {
      this.isFiltersOpen = !this.isFiltersOpen;
    },
    checkClearFilterVisibility(value) {
      let initialFilterKeys = this.options.filters
          .filter((item) => item.key !== ("pipeline" || "highlights"))
          .map((i) => i.key),
        filteredKeysWithValue = initialFilterKeys.filter((item) => {
          if (value[item] === null) return false;
          if (
            typeof value[item] === "object" &&
            Object.keys(value[item]).length === 0
          )
            return false;
          return value[item];
        });
      this.visibleClearFilter = filteredKeysWithValue.length > 0;
    },
    clearAllFilter() {
      this.$hub.$emit("clearAllFilter-kanban-view");
      this.getFilterValues({ pipeline: Number(this.localPipelineId) });
    },

    getSearchValue(value) {
      this.searchValue = value;
      this.filterValues.search = this.searchValue;
        this.getData()
            .then(() => {
                this.setProps(this.stages);
            });
    },
    isFiltersActive() {
      this.isFiltersOpen = window.innerWidth > 575;
    },
    deleteDeal(id) {
      this.dealDeleteId = id;
      this.dealDeleteModal = true;
    },
    confirmed() {
      let url = route("deals.destroy", { id: this.dealDeleteId });
      this.axiosDelete(url)
        .then((response) => {
          this.$toastr.s(response.data.message);
          this.getFilterValues(this.filterValues);
        })
        .catch(({ error }) => {
          this.cancelled();
        })
        .finally(() => {});
    },
    pipelineDeleteSuccess() {
      this.$hub.$on("pipeline-delete-success", (value = true) => {
        if (value) {
          window.location.replace(route("deals_pipeline.page"));
        }
      });
    },
    closePipelineDeleteModal() {
      this.isDeletePipelineModal = false;
      $("#pipeline-delete-modal").modal("hide");
    },
    cancelled() {
      this.dealDeleteModal = false;
      $("#deal-delete-modal").modal("hide");
    },
    deletePipeline() {
      this.isDeletePipelineModal = true;
    },
    openLostreasonModal() {
      this.isModalActive = true;
      $("#lost-reason-modal").modal("show");
    },
    closeLostReasonModal() {
      this.isModalActive = false;
      this.selectedUrlReason = "";
      $("#lost-reason-modal").modal("hide");
    },
    getDealValueRange() {
      this.axiosGet(route("deal.value")).then((response) => {
        let dealFilter = this.options.filters.find(
          (item) => item.key === "deal_value"
        );
        dealFilter.maxRange = Number(response.data.max_deal_value);
        dealFilter.minRange =
          Number(response.data.min_deal_value) <
          Number(response.data.max_deal_value)
            ? Number(response.data.min_deal_value)
            : 0;
      });
    },
  },
  mounted() {
    this.pipexEventHandeler();
    this.pipelineDeleteSuccess();
    setTimeout(() => {
      // this.$store.dispatch("getOwner");
      if (this.$can("view_persons")) {
        this.$store.dispatch("getPerson");
      }
      if (this.$can("view_organizations")) {
        this.$store.dispatch("getOrganization");
      }
      this.$store.dispatch("getDeal");
      // this.$store.dispatch("getAllTags");
      this.getDealValueRange();
    }, 1000);
    this.localPipelineId = this.$props.pipeline ? this.$props.pipeline : "";

    //if props.pipelineId exist pipeline navigation filter would be added
    this.options.filters.unshift({
      title: "Select a pipeline",
      type: "dropdown-menu-filter",
      key: "pipeline",
      initValue: null,
      option: [],
      listValueField: "name",
    });

    this.getPipeline()
      .then(() => this.getStages())
      .then(() => {
        this.options.filters[0].initValue = Number(this.localPipelineId);
        this.options.filters[0].option = this.pipelines;
        this.getFilterValues({ pipeline: Number(this.localPipelineId) });
        this.filterDataLoaded = true;
      });

    // for filter and search
    this.isFiltersActive();
    window.onresize = () => {
      this.isFiltersActive();
      $(".kanban-view .kanban-draggable-column").css({
        height: window.innerHeight - 290 + "px",
      });
    };
  },
  watch: {
    // dynamic height for kanban view.
    stagesProperty: function () {
      $(".main-panel").css({
        minHeight: window.innerHeight + "px",
      });
      $(".kanban-view").css({
        paddingBottom: 0,
      });
      $(".kanban-view .kanban-draggable-column").css({
        height: window.innerHeight - 290 + "px",
      });
    },
  },
};
</script>
<style>
.pipex-sortable-ghost {
  opacity: 0 !important;
}
.refresh-btn {
  bottom: 20px;
  right: 20px;
  z-index: 455555;
  border-radius: 50%;
  padding: 0em;
  padding: 1em !important;
}
.refresh-btn .btn-text {
  display: none;
}
/* .refresh-btn:hover {
  width: 120px;
}
.refresh-btn:hover .btn-text {
  display: block;
} */
.pipex-sortable-chosen {
  /* opacity:0 !important; */
}

.pipex-sortable-drag {
  opacity: 1 !important;
}

.avatars-w-24 .no-img {
  font-size: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--avatar-no-image-font);
  background-color: var(--avatar-no-image-bg);
}

.avatars-w-24 .no-img,
.avatars-w-24 img {
  height: 24px !important;
  width: 24px !important;
}

.T .no-img {
  background: olive;
}

.A .no-img {
  background: #9581f0;
}

.B .no-img {
  background: rgb(41, 207, 199);
}

.C .no-img {
  background: rgb(207, 84, 166);
}

.D .no-img {
  background: rgb(234, 236, 72);
}

.I .no-img {
  background: rgb(235, 83, 90);
}

.M .no-img {
  background-color: #9581f0 !important;
}

.R .no-img {
  background: rgb(41, 207, 199);
}

.Q .no-img {
  background: rgb(207, 84, 166);
}

.U .no-img {
  background: rgb(238, 160, 59);
}

.G .no-img {
  background: rgb(62, 160, 206);
}

.P .no-img {
  background: rgb(223, 66, 189);
}

.S .no-img {
  background: rgb(95, 209, 72);
}

.L .no-img {
  background: rgb(234, 236, 72);
}

.O .no-img {
  background: rgb(235, 83, 90);
}
</style>
<style>
.deal-card:hover .profile-img .rounded-circle {
  box-shadow: inset 2px 0px 0px var(--default-card-bg),
    inset -2px 0px 0px var(--default-card-bg),
    inset 0px 2px 0px var(--default-card-bg),
    inset 0px -2px 0px var(--default-card-bg) !important;

  border: 2px solid #4466f2 !important;
}
</style>
<style scoped lang="scss">
.highlights {
  border: 1px solid #434b6b !important;
}

.badge.activity {
  cursor: pointer;
}

.tags-background {
  background: #fbfbfb;
  padding: 0.5rem;
}

.kanban-wrapper .kanban-column {
  width: 320px;
  min-width: 320px;
  // background: #f9f9f9;
  background: #e8e8e8;
}

.kanban-wrapper .won-lost-bg-color{
    background-color: #f2ebe3f2 !important;
}

.kanban-wrapper .kanban-column .kanban-draggable-column {
  padding-bottom: 3.2rem !important;
}

html[theme="light"] .draggable-item:hover {
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.25);
}

html[theme="dark"] .draggable-item:hover {
  box-shadow: 0px 0px 5px black, inset 1px 1px 0px #3e3e6d,
    inset -1px -1px 0px #3e3e6d;
}

h5,
h6 {
  font-weight: 600;
}

hr {
  margin: 0.5rem -1rem 0.1rem;
}

a {
  font-size: 0.85rem;
  color: #25384a;
}

.badge {
  font-weight: 500;
  font-size: 0.75rem;
  padding: 0.65em 0.95em;
}

.deal-value {
  font-size: 90% !important;
  color: black;
}

.deal-activity {
  background: var(--default-card-bg);
  color: var(--default-font-color);
  cursor: pointer;

  .badge {
    background: #fbfbfb;
    color: var(--default-font-color);
  }
}

[theme="dark"] {
  .kanban-wrapper .kanban-column {
    background: #{darken(#2b303b, 7%)};
  }

  a {
    color: #{lighten(#4466f2, 20%)};
  }

  .deal-value {
    color: #6cabb9;
  }

  .tags-background {
    background: #2b303b;
  }

  .deal-activity {
    .badge {
      background: #2b303b;
    }
  }
    .won-lost-bg-color {
        background-color: rgba(255, 204, 23, 0.09) !important;
    }
}

.selected-btn {
  font-size: 95%;
  cursor: pointer;
  border-radius: 20px;
  color: #8a8a8a;
  padding: 0.5rem 1.5rem;
  background: var(--btn-filter-bg);
  box-shadow: var(--default-box-shadow);
  border: 1px solid var(--btn-filter-bg) !important;
}

.pipeline-collapse .stage-header {
  width: 560px;
  left: 0px;
  display: inline-flex;
  align-items: flex-start;
  flex-direction: row-reverse;
  transform-origin: calc(1em + 0.5rem) center;
  transform: rotate(90deg);
  padding-left: 0.5rem !important;
  padding-bottom: 1.5rem !important;
  border-bottom: 0px;
}

.pipeline-collapse .stage-header > div {
  display: inline-flex;
  align-items: baseline;
  padding-left: 0px;
}

.pipeline-collapse .stage-header > div.col-10 {
  left: -50px;
}

.pipeline-collapse .stage-information {
  margin-left: 1rem !important;
  flex: 0 0 60%;
}

.pipeline-collapse .stage-header > div > div.collapse-icon > svg {
  transform: rotate(90deg);
}

.pipeline-collapse .stage-header > div > div.select-icon {
  display: none;
}

div.selected.select-icon > svg {
  color: #46c35f !important;
}

.media {
  overflow: hidden;
  position: relative;
}

.profile-img:before {
  content: "click to marked";
  position: absolute;
  left: 36px;
  width: 200px;
  transition: 300ms;
  padding: 0.15rem;
  top: -50px;
  color: #4466f2;
  z-index: 455555;
  background: var(--default-card-bg);
}

.profile-img:hover:before {
  top: 0px;
}

.kanban-wrapper .pipeline-collapse.kanban-column {
  width: 60px;
  min-width: 60px;
  transition-duration: 300ms;
  //opacity: 0.7;
}

.pipeline-collapse .draggable-item {
  display: none;
}

.pipeline-collapse .kanban-draggable-column {
  margin-bottom: 18px;
}

.collapse-btn {
  cursor: pointer;
}

.kanban-wrapper .stage-header {
  margin-left: 0rem;
}

.stage-name {
  opacity: 0.7;
}
</style>
