<template>
  <div class="card shadow deal-preview-card border-0 min-height-300 h-100">
    <div class="card-body position-relative h-100">
      <div
        v-if="showAction && visibleActions.length > 0"
        class="dropdown options-dropdown position-absolute"
      >
        <button
          type="button"
          class="btn-option btn d-flex align-items-center justify-content-center action-button-width"
          data-toggle="dropdown"
        >
          <app-icon class="size-16" name="more-horizontal" />
        </button>
        <div class="dropdown-menu dropdown-menu-right py-2 mt-1">
          <a
            class="dropdown-item px-4 py-2"
            href="#"
            v-for="(action, index) in visibleActions"
            :key="index"
            @click.prevent="callAction(action)"
          >
            {{ action.title }}
          </a>
        </div>
      </div>
      <div class="d-flex flex-column justify-content-between h-100">
        <div class="card-heading d-flex justify-content-start">
          <app-avatar
            avatar-class="avatars-w-20"
            title="John Doe"
            img="/images/profile.png"
          />
          <div class="ml-2">
            <a
              href=""
              @click.prevent="openDealDetailsModal"
              class="d-block mb-2"
            >
              {{ deal.title }}
            </a>
            <span
              :class="`badge badge-${
                deal.status.class ? deal.status.class : 'secondary'
              }
                         badge-sm rounded-pill font-weight-normal`"
            >
              {{ deal.status.translated_name }}
            </span>
          </div>
        </div>
        <div class="d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="d-flex align-items-center">
              <app-avatar
                avatar-class="avatars-w-20"
                :title="deal.owner.full_name"
                img=""
              />
              <p class="text-muted mb-0 ml-2">
                {{ deal.owner.full_name }}
              </p>
            </div>
            <p class="mb-0 text-primary text-nowrap">
              {{ numberWithCurrencySymbol(deal.value) }}
            </p>
          </div>
          <div class="d-flex align-items-center mt-3">
            <app-icon class="size-20 text-muted" name="briefcase" />
            <p class="mb-0 text-muted ml-2">
              {{
                deal.contextable_type ===
                `App\\Models\\CRM\\Organization\\Organization`
                  ? (deal.contextable ? deal.contextable.name : $t("not_added_yet"))
                  : $t("not_added_yet")
              }}
            </p>
          </div>
          <div class="d-flex align-items-center mt-3">
            <app-icon class="size-20 text-muted" name="calendar" />
            <p class="mb-0 text-muted ml-2">
              {{ formatDateTimeToLocal(deal.created_at) }}
            </p>
          </div>
          <hr class="mx-minus-primary my-3" />
          <div class="d-flex justify-content-start flex-wrap">
            <div class="d-flex align-items-center mr-2">
              <p class="text-muted mb-0">
                {{ $t("comment") }}
              </p>
              <span class="badge badge-deal-card font-weight-normal ml-2">
                {{ deal.discussions.length ? deal.discussions.length : 0 }}
              </span>
            </div>
            <div class="d-flex align-items-center">
              <p class="text-muted mb-0">
                {{ $t("proposal") }}
              </p>
              <span class="badge badge-deal-card font-weight-normal ml-2">
                {{ deal.proposals_count }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  numberWithCurrencySymbol,
  formatDateTimeToLocal,
} from "../../../../Helpers/helpers";

export default {
  name: "DealCard",
  data() {
    return {
      route,
      numberWithCurrencySymbol,
      formatDateTimeToLocal,
      showAction: true,
      isDetailsViewActive: false,
    };
  },
  props: {
    deal: {
      type: Object,
      required: true,
    },
    actions: {
      type: Array,
      required: true,
    },
    tableId: {
      required: true,
    },
    pipelines: {
      type: Array,
    },
    stages: {
      type: Array,
    },
  },
  computed: {
    visibleActions() {
      return this.actions.filter((action) => {
        if (typeof action.title === "undefined") return false;
        if (typeof action.modifier === "undefined") return true;
        return action.modifier(this.deal);
      });
    },
  },
  methods: {
    openDealDetailsModal() {
      this.callAction({ title: this.$t("quick_view") });
    },

    callAction(action) {
      this.$emit("action-" + this.tableId, this.deal, action, true);
    },
  },
};
</script>

<style lang="scss">
.action-button-width {
  width: auto !important;
  height: auto !important;
}

.deal-preview-card {
  .dropdown {
    &.options-dropdown {
      right: 2rem;
    }
  }

  .card-heading {
    width: calc(100% - 20px);
  }

  .badge-deal-card {
    background: #fbfbfb;
    color: var(--default-font-color);
  }
}

[theme="dark"] {
  .badge-deal-card {
    background: #2b303b;
  }

  .action-button-width {
    width: auto !important;
    height: auto !important;
  }
}
</style>
