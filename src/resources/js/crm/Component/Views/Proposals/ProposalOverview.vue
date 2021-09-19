<template>
  <div class="content-wrapper calendar-position-modified">
    <!-- Overview -->
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb
          :page-title="$t('proposal_list')"
          :directory="[$t('proposals'), $t('list')]"
          :icon="'hexagon'"
        />
      </div>
      <div class="col-sm-12 col-md-6">
        <div class="float-md-right d-flex">
          <span class="btn-filter proposal-view-toggle mr-2 d-flex">
            <span
              class="btn btn-sm"
              @click="viewController = true"
              :class="{ active: viewController }"
            >
              <app-icon name="columns" />
            </span>
            <span
              class="btn btn-sm"
              @click="viewController = false"
              :class="{ active: !viewController }"
            >
              <app-icon name="trello" />
            </span>
          </span>
          <button
            v-if="$can('create_proposals')"
            type="button"
            class="btn btn-primary btn-with-shadow"
            data-toggle="modal"
            @click="sendProposal()"
          >
            {{ $t("new_proposal") }}
          </button>
        </div>
      </div>
    </div>
    <!-- filter -->
    <!-- List view -->
    <app-proposals-list-view v-if="viewController" />
    <!-- Kanban view -->
    <app-proposal-kanban v-else />
  </div>
</template>

<script>
export default {
  name: "ProposalOverview",
  data() {
    return {
      viewController: true,
    };
  },
  methods: {
      sendProposal(){
          window.location.replace(route('proposals.create'));
      }
  },
};
</script>

<style>
.proposal-view-toggle .btn-sm {
  cursor: pointer;
}
.proposal-view-toggle .active {
  background: #e6e6e6;
  color: rgb(93, 42, 235) !important;
}
</style>
