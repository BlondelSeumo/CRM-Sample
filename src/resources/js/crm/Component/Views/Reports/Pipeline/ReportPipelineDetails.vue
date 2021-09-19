<template>
  <app-modal
    modal-id="details-view-modal"
    modal-size="fullscreen"
    modal-alignment="top"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h4>{{ pageHeader }}</h4>
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
      <app-table :id="'report-details-table'" :options="options" />
    </template>
    <template slot="footer">
      <button
        type="button"
        class="btn btn-secondary mr-2"
        data-dismiss="modal"
        @click.prevent="closeModal"
      >
        {{ $t("close") }}
      </button>
    </template>
  </app-modal>
</template>

<script>
import { numberWithCurrencySymbol, numberFormatter, dealAgeHumanize } from "../../../../Helpers/helpers";

export default {
  name: "ReportPipelineDetails",
  props: ["filterValues", "findById", "pageHeader"],
  data() {
    return {
      numberWithCurrencySymbol,
      numberFormatter,
        dealAgeHumanize,
      options: {
        tablePaddingClass: "px-0",
        tableShadow: false,
        name: "report-details-table",
        url: "",
        showHeader: true,
        columns: [
          {
            title: this.$t("deal_name"),
            type: "text",
            key: "title",
          },
          {
            title: this.$t("lead"),
            type: "component",
            componentName: "icon-with-text",
            key: "contextable",
          },
          {
            title: this.$t("contact_person"),
            type: "custom-html",
            key: "contact_person",
            modifier: (value, row) => {
              return value.length
                ? value[0].name
                : `<p class="m-0 font-size-90 text-secondary">` +
                    this.$t("deal_has_no_contact_person") +
                    `</p>`;
            },
          },
          this.checkFourthColumn(),
          this.checkFifthColumn(),
          {
            title: this.$t("deal_value"),
            type: "custom-html",
            key: "value",
            modifier: (value) => {
              return `<p class="text-nowrap">${numberWithCurrencySymbol(value)}</p>`;
            },
          },
          {
            title: this.$t("deal_age"),
            type: "custom-html",
            key: "avg_age_of_deal",
              modifier: (value, row) => {
                  return `<p class="text-nowrap">${dealAgeHumanize(row.created_at, row.updated_at, row.status)}</p>`;
              },
          },
        ],
        showSearch: false,
        showFilter: false,
        paginationType: "pagination",
        responsive: true,
        rowLimit: 10,
        showAction: true,
        orderBy: "desc",
        actionType: "dropdown",
      },
    };
  },
  created() {
    let owner = this.filterValues.owner ? "owner_is=" + this.filterValues.owner : "";
    this.options.url = route('pipeline-details', {_query:
            {
                owner: owner,
                groupBy: this.filterValues.groupBy,
                findById: this.findById,
                deal_strategy: this.filterValues.deal_strategy,
                status: null,
                pipeline: this.filterValues.pipeline + ""
        }
    });
  },
  methods: {
    closeModal(value) {
      this.$emit("close-modal", value);
    },
    checkFourthColumn() {
      if (this.filterValues.groupBy == "stage_id") {
        return {
          title: this.$t("owner"),
          type: "object",
          key: "owner",
          modifier: (value, row) => {
            return value.full_name;
          },
        };
      } else if (
        (this.filterValues.deal_strategy == 1 || this.filterValues.deal_strategy == 2) &&
        this.filterValues.groupBy == "owner_id"
      ) {
        return {
          title: this.$t("stage"),
          type: "object",
          key: "stage",
          modifier: (value, row) => {
            return value.name;
          },
        };
      } else if (
        this.filterValues.deal_strategy == 3 &&
        this.filterValues.groupBy == "owner_id"
      ) {
        return {
          title: this.$t("closing_stage"),
          type: "object",
          key: "stage",
          modifier: (value, row) => {
            return value.name;
          },
        };
      }
      return {};
    },
    checkFifthColumn() {
      if (
        this.filterValues.deal_strategy == 3 &&
        (this.filterValues.groupBy == "owner_id" ||
          this.filterValues.groupBy == "stage_id")
      ) {
        return {
          title: this.$t("status"),
          type: "custom-html",
          key: "status",
          modifier: (value) => {
            return `<span class="badge badge-pill badge-${value.class ?? "secondary"}">${
              value.translated_name
            }</span>`;
          },
        };
      }
      return {};
    },
  },
};
</script>

<style scoped></style>
