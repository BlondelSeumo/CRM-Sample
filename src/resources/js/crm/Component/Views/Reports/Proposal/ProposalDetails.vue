<template>
  <app-modal
    modal-id="details-view-modal"
    modal-size="fullscreen"
    modal-alignment="top"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h4>{{ this.pageHeader }} {{ this.fullName }}</h4>
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
      <app-table
        :id="'report-proposal-details-table'"
        :options="options"
        class="remove-datatable-x-padding"
      />
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
import { numberWithCurrencySymbol, numberFormatter } from "../../../../Helpers/helpers";
import { formatDateToLocal } from "../../../../Helpers/helpers";
export default {
  name: "ReportDealDetails",
  props: ["id", "filterValues", "fullName", "proposalSentStatusId"],
  created() {
    this.filterValues.status_id === this.proposalSentStatusId
      ? (this.pageHeader = this.$t("details_view_of_sent_proposal_by"))
      : (this.pageHeader = this.$t("details_view_of_accepted_proposal_by"));
  },
  data() {
    return {
      numberWithCurrencySymbol,
      numberFormatter,
      formatDateToLocal,
      pageHeader: "",
      options: {
        tablePaddingClass: "px-0",
        tableShadow: false,
        name: "report-proposal-details-table",
        url:
            route('proposal-report-details', {_query: {
                    id: this.id,
                    status: this.filterValues.status_id,
                    deal_status_id: this.filterValues.deal_status_id,
                    pipeline: this.filterValues.pipeline
            }}),
        showHeader: true,
        columns: [
          {
            title: this.$t("proposal_name"),
            type: "text",
            key: "subject",
          },
          this.checkSecondColumn(),
          {
            title: this.$t("contact_person"),
            type: "custom-html",
            key: "deal",
            modifier: (value) => {
              return value.contact_person.length ? value.contact_person[0].name :
                `<p class="m-0 font-size-90 text-secondary">` +
                  this.$t("deal_has_no_contact_person") +
                  `</p>`;
            },
          },
          {
            title: this.$t("deal_name"),
            type: "object",
            key: "deal",
            modifier: (value) => {
              return value.title;
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
  methods: {
    closeModal(value) {
      this.$emit("close-modal", value);
    },
    checkSecondColumn() {
      if (this.filterValues.status_id === this.proposalSentStatusId) {
        return {
          title: this.$t("sent_at"),
          type: "object",
          key: "sent_at",
          modifier: (date) => {
            return formatDateToLocal(date);
          },
        };
      } else {
        return {
          title: this.$t("accepted_at"),
          type: "object",
          key: "accepted_at",
          modifier: (date) => {
            return formatDateToLocal(date);
          },
        };
      }
    },
  },
};
</script>
