<template>
  <div>
    <app-overlay-loader v-if="mainPreloader" />

    <div v-else class="content-wrapper reports">
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <app-breadcrumb
            :directory="$t('reports')"
            :icon="'bar-chart'"
            :page-title="pageTitle"
          />
        </div>
      </div>
      <div v-if="isActivePrimaryFilters" class="row">
        <div class="col-6 col-sm-8 col-md-9 col-lg-10 col-xl-10">
          <div class="filters-wrapper d-flex justify-content-start flex-wrap">
            <!--Open Filters Button For Mobile-->
            <button
              class="btn d-block d-sm-none btn-toggle-filters"
              type="button"
              @click.prevent="toggleFilters"
            >
              co
              <app-icon :name="'filter'" />
              {{ $t("filters") }}
            </button>

            <span v-show="isFiltersOpen" class="mobile-filters-wrapper">
              <app-filter
                :filters="filters"
                :table-id="tableId"
                @get-values="getFilterValues"
              />

              <button
                class="btn btn-primary btn-with-shadow d-sm-none btn-close-filter-wrapper d-flex justify-content-center align-items-center"
                type="button"
                @click="toggleFilters"
              >
                {{ $t("close") }}
              </button>
            </span>
          </div>
        </div>
      </div>

      <!--Horizontal bar chart-->
      <div class="row">
        <div class="col-12 mb-primary">
          <div class="card card-with-shadow border-0 h-100">
            <!--char filter-->
            <div
              v-if="showChartFilter"
              class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center"
            >
              <div class="d-flex justify-content-start align-items-center">
                <div class="mr-3">
                  <ul class="nav tab-filter-menu justify-content-flex-end">
                    <li
                      v-for="(item, index) in reportsChartsFilter"
                      :key="index"
                      class="nav-item"
                    >
                      <a
                        :class="[
                          filterParameter.group_by == item.id
                            ? 'active'
                            : index === 0 && filterParameter.group_by === ''
                            ? 'active'
                            : '',
                        ]"
                        class="nav-link py-0"
                        href="#"
                        @click.prevent="getGroupByFilterOptions(item.id)"
                        >{{ item.value }}</a
                      >
                    </li>
                  </ul>
                </div>
                <div
                  class="filters-wrapper d-flex justify-content-start flex-wrap mt-2 ml-2"
                >
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
                      :filters="secondaryFilters"
                      class="ml-2"
                      table-id="stagechartFilter"
                      @get-values="getSecondaryFilterValues"
                    />

                    <button
                      class="btn btn-primary btn-with-shadow d-sm-none btn-close-filter-wrapper d-flex justify-content-center align-items-center"
                      type="button"
                      @click="toggleFilters"
                    >
                      {{ $t("close") }}
                    </button>
                  </span>
                </div>
              </div>

              <div class="d-flex justify-content-end align-items-center">
                <span class="mr-2">{{ $t("order_report_by") }}:</span>
                <app-input
                  v-model="filterParameter.countValue"
                  :list="countValueList"
                  list-value-field="name"
                  type="radio-buttons"
                  @input="getCountValueFilterValues"
                />
              </div>
            </div>
            <div v-if="isActive" class="card-body pt-5">
              <component
                :is="'app-chart'"
                :data-sets="dealReport.dataSet"
                :height="380"
                :labels="dealReport.labels"
                type="horizontal-line-chart"
              />
              <hr class="my-5 reports-table-divider" />
              <app-table
                v-if="isActivePrimaryFilters"
                :id="tableId"
                :filtered-data="filterParameter"
                :options="tableOptions"
                @action="triggerActions"
                class="remove-datatable-x-padding"
              />
            </div>
          </div>
        </div>
      </div>

      <!--modal for details view-->
      <report-deal-details
        v-if="detailsViewModal && isActivePrimaryFilters"
        :id="detailsViewId"
        :filterValue="this.filterParameter"
        :groupBy="filterParameter.group_by"
        :modal-id="'details-view-modal'"
        :pageHeader="detailsModalHeader"
        :statusLostId="statusLostId"
        :table-id="'details-view-modal'"
        @close-modal="closedDetailsViewModal"
      />
    </div>
  </div>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import ReportDealDetails from "./ReportDealDetails";
import { owner, pipeline, stages } from "../../../../Mixins/Global/ReportMixin";
import {
  numberFormatter,
  numberWithCurrencySymbol,
  urlGenerator,
    reportDealsAvgAge,
} from "@app/Helpers/helpers";
import { api } from "@app/Helpers/api";
import { collect } from "@app/Helpers/Collection";

export default {
  name: "ReportDeal",
  mixins: [FormMixin, owner, pipeline, stages],
  components: { ReportDealDetails },
  data() {
    return {
      numberWithCurrencySymbol,
      numberFormatter,
      urlGenerator,
        reportDealsAvgAge,
      mainPreloader: false,
      dealReport: {},
      isActive: true,
      isActivePrimaryFilters: false,
      detailsViewId: null,
      pageTitle: this.$t("deal"),
      columnTitle: "owner",
      filterParameter: {
        status: "",
        pipeline: 1,
        deal_value: 1,
        stages: "by_stages",
        countValue: 2,
        group_by: "owner_id",
      },
      countValueList: [
        {
          id: 1,
          name: this.$t("count"),
        },
        {
          id: 2,
          name: this.$t("value"),
        },
      ],
      detailsModalHeader: "",
      searchValue: "",
      isFiltersOpen: true,
      filters: [
        {
          title: "Pipeline",
          type: "dropdown-menu-filter",
          key: "pipeline",
          header: {
            title: this.$t("want_to_see_your_deal_report_with"),
            description: this.$t("you_can_filter_and_see_your_deal_report_by_pipeline"),
          },
          option: [],
        },
        {
          title: this.$t("how_many_deals_were_won"),
          type: "dropdown-menu-filter",
          key: "status",
          option: [],
          listValueField: "name",
        },
        {
          title: "Created date",
          type: "range-picker",
          key: "date",
          option: ["today", "thisMonth", "last7Days", "thisYear"],
        },
      ],
      secondaryFilters: [
        //stage
        {
          title: this.$t("every_stage"),
          type: "dropdown-menu-filter",
          key: "stages",
          option: [],
          listValueField: "value",
        },
      ],
      showChartFilter: false,
      stageFilterValue: 0,
      countValueFiltersValues: {},
      reportsChartsFilter: [
        { id: "owner_id", value: this.$t("by_owner") },
        { id: "stage_id", value: this.$t("by_stage") },
        { id: "lost_reason_id", value: this.$t("by_reason") },
      ],
      detailsViewModal: false,
      tableId: "report-deal-table",
      tableOptions: {
        name: "deal-report",
        url: route('deal.deal-report'),
        datatableWrapper: false,
        showHeader: true,
        columns: [
          {
            title: "",
            type: "button",
            key: "group_by",
            sortAble: true,
            isVisible: true,
            className: "btn btn-link text-primary pl-0 text-decoration-none",
            modifier: (value, row) => {
              return value.name;
            },
          },
          {
            title: this.$t("total_deals_value"),
            type: "custom-html",
            key: "total_deals_value",
            modifier: (value) => {
              return `<p class="text-nowrap">${numberWithCurrencySymbol(value)}</p>`;
            },
          },
          {
            title: this.$t("avg_deals_value"),
            type: "custom-html",
            key: "average_deals_value",
            modifier: (value) => {
              return `<p class="text-nowrap">${numberWithCurrencySymbol(value)}</p>`;
            },
          },
          {
            title: this.$t("avg_age_of_deal"),
            type: "custom-html",
            key: "average_age_of_deal",
            modifier: (value) => {
              return `<p class="text-nowrap">${reportDealsAvgAge(value)}</p>`;
            },
          },
        ],
        tablePaddingClass: "px-0",
        showSearch: false,
        showFilter: false,
        responsive: true,
        tableShadow: false,
        rowLimit: 10,
        orderBy: "desc",
        statusWonId: "",
        statusLostId: "",
      },
    };
  },
  mounted() {
    this.isFiltersActive();
    this.secondaryFilters.title = this.$t(this.columnTitle);
    this.tableOptions.columns[0].title = this.$t("owner_name");

    window.onresize = () => {
      this.isFiltersActive();
    };
  },
  created() {
    this.getWinLostStatusIds();
    this.setInitialPipeline();
  },
  methods: {
    setInitialPipeline() {
        this.axiosGet(route('pipelines.index', {_query: {all: true}}))
        .then((response) => {
          // if (response.data.length == 0) {
          //   this.redirect("/crm/pipelines/create");
          // }
            if (response.data.length){
                this.filters.find(({key}) => key === 'pipeline').initValue = response.data[0].id;
            }
        });
    },
    redirect(url = "/") {
      window.location = urlGenerator(url);
    },
    getWinLostStatusIds() {
      //Status filter set up (primary filter)

        this.axiosGet(
            route(`statuses.index`, {_query: { name: "status_won", type: "deal"}}))
        .then((res) => {
          this.statusWonId = collect(res.data).first().id;
          this.filters.filter((element) => {
            if (element.key === "status") {
              return element.option.push({
                id: this.statusWonId,
                name: this.$t("how_many_deals_were_won"),
                icon: "award",
              });
            }
          });

          this.filterParameter.status = this.statusWonId;
          this.pageTitle = this.$t("how_many_deals_were_won");
          this.filterParameter?.status === this.statusWonId
            ? this.tableOptions.columns.splice(1, 0, {
                title: this.$t("deals_won"),
                type: "text",
                key: "deals_won",
              })
            : this.tableOptions.columns.splice(1, 0, {
                title: this.$t("deals_lost"),
                type: "text",
                key: "deals_won",
              });
          this.dealReportChart();
        });

        this.axiosGet(
            route(`statuses.index`, {_query: { name: "status_lost", type: "deal"}}))
        .then((res) => {
          this.statusLostId = collect(res.data).first().id;
          return this.filters.filter((element) => {
            if (element.key === "status") {
              element.option.push({
                id: this.statusLostId,
                name: this.$t("how_many_deals_were_lost"),
                icon: "frown",
              });
            }
          });
        });

      this.isActivePrimaryFilters = true;
    },
    dealReportChart() {
      this.isActive = false;
      let url = route('deal.deal-report-chart'),
        reqData = {};

      reqData.params = {
        ...this.filterParameter,
      };

      return this.axiosGet(url, reqData)
        .then((response) => {
          this.dealReport = response.data;
        })
        .catch(({ response }) => {})
        .finally(() => {
          this.mainPreloader = false;
          this.isActive = true;
        });
    },
    setFilterOption(value) {
      this.showChartFilter = false;
      let indexOfStageFilter = this.secondaryFilters.findIndex(
        (element) => element.key === "stages"
      );

      if (value === "owner_id") {
        //filter stages by pipeline
        let filteredStages = this.getFilteredStagesByPipeline();

        let isDuplicate = filteredStages.includes({
          id: 0,
          value: this.$t("every_stage"),
        });

        !isDuplicate
          ? filteredStages.unshift({ id: 0, value: this.$t("every_stage") })
          : false;

        this.secondaryFilters[indexOfStageFilter].option = filteredStages;
      } else {
        let check = false;

        for (let item in this.owners) {
          if (this.owners[item].id == 0) {
            check = true;
            break;
          } else check = false;
        }

        if (!check) this.owners.unshift({ id: 0, value: this.$t("everyone") });

        this.secondaryFilters[indexOfStageFilter].option = this.owners;
        this.secondaryFilters[indexOfStageFilter].title = this.$t("everyone");
      }

      this.showChartFilter = true;
    },
    toggleFilters() {
      this.isFiltersOpen = !this.isFiltersOpen;
    },
    isFiltersActive() {
      this.isFiltersOpen = window.innerWidth > 575;
    },
    //Primary filter
    getFilterValues(filterValue) {
      if (filterValue.status == this.statusLostId) {
        this.pageTitle = this.$t("how_many_deals_were_lost");
      } else {
        this.pageTitle = this.$t("how_many_deals_were_won");
      }
      let temp = {};
      //merging two objects to get updated primary filters value
      temp = Object.assign(this.filterParameter, filterValue);
      this.filterParameter = temp;

      this.dealReportChart().then(() => {
        this.isActive = true;
      });

      this.changeDealWonLostColumnTitle(filterValue);
      setTimeout(() => {
        this.showChartFilter = this.filterParameter.status === this.statusLostId;
        this.$hub.$emit("reload-" + this.tableId);
      });
    },
    //Secondary
    getSecondaryFilterValues(value) {
      this.isActive = false;
      this.filterParameter.stages = value.stages;
      this.filterParameter.countValue = value["count_value"];

      this.dealReportChart().then(() => {
        setTimeout(() => {
          this.isActive = true;
        });
      });
    },
    getGroupByFilterOptions(value) {
      this.setFilterOption(value);
      this.filterParameter.group_by = value;
      this.changeColumnTitle(value);
      this.dealReportChart().then(() => {});
    },
    getFilteredStagesByPipeline() {
      return this.stages.filter((element) => {
        if (element.pipeline_id === 1) {
          return element;
        }
      });
    },
    changeColumnTitle(value) {
      if (value === "stage_id") {
        this.tableOptions.columns[0].title = this.$t("stage_name");
      } else if (value === "lost_reason_id") {
        this.tableOptions.columns[0].title = this.$t("reason_name");
      } else {
        this.tableOptions.columns[0].title = this.$t("owner_name");
      }
    },
    changeDealWonLostColumnTitle(value) {
      value.status === this.statusWonId
        ? (this.tableOptions.columns[1].title = this.$t("deals_won"))
        : (this.tableOptions.columns[1].title = this.$t("deals_lost"));
    },
    triggerActions(row, action, active) {
      this.filterParameter.status === this.statusWonId
        ? (this.detailsModalHeader =
            this.$t("details_view_of_won_deals_by") + row.group_by.name)
        : (this.detailsModalHeader =
            this.$t("details_view_of_lost_deals_by") + row.group_by.name);
      this.detailsViewId = row.group_by.id;
      this.detailsViewModal = true;
    },
    closedDetailsViewModal() {
      this.detailsModalHeader = "";
      this.detailsViewId = null;
      this.detailsViewModal = false;
    },

    getCountValueFilterValues() {
      this.filterParameter.countValue = this.filterParameter.countValue;

      this.dealReportChart().then(() => {
        setTimeout(() => {
          this.isActive = true;
        });
      });
    },
  },
};
</script>
