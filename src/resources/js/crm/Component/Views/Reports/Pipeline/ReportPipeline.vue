<template>
  <div class="content-wrapper reports">
    <template v-if="initialFilterDataLoad">
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <app-breadcrumb
            :directory="[$t('reports'), $t('pipeline')]"
            :icon="'bar-chart'"
            :page-title="breadcrumbTitle"
          />
        </div>
      </div>
      <!--filter-->
      <div class="row">
        <div class="col-6 col-sm-8 col-md-9 col-lg-10 col-xl-10">
          <div class="filters-wrapper d-flex justify-content-start flex-wrap">
            <!--Open Filters Button For Mobile-->
            <button
              class="btn d-block d-sm-none btn-toggle-filters"
              type="button"
              @click.prevent="toggleFilters"
            >
              <app-icon :name="'filter'" />
              {{ $t("filters") }}
            </button>

            <span v-if="isFiltersOpen" class="mobile-filters-wrapper">
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
            <!--                    char filter-->
            <div
              v-if="showChartFilter"
              class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center"
            >
              <div class="d-flex justify-content-start">
                <div class="d-flex align-items-center">
                  <ul class="nav tab-filter-menu justify-content-flex-end">
                    <li
                      v-for="(item, index) in reportsChartsFilter"
                      :key="index"
                      class="nav-item"
                    >
                      <a
                        :class="[
                          groupBy == item.id
                            ? 'active'
                            : index === 0 && groupBy === ''
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
                  class="filters-wrapper d-flex justify-content-start flex-wrap ml-3 pl-2"
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
                  <span v-if="showChartSubFilter" class="mobile-filters-wrapper">
                    <app-filter
                      :filters="secondaryFilters"
                      class="ml-primary"
                      table-id="chartFilter"
                      @get-values="getChartSubFilterValues"
                    />
                    <button
                      class="btn btn-primary btn-with-shadow d-sm-none btn-close-filter-wrapper d-flex justify-content-center align-items-center"
                      type="button"
                      @click="toggleFilters"
                    >
                      {{ $t("close") }}
                    </button>
                  </span>
                  <span class="mobile-filters-wrapper">
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
                  v-model="reportBy"
                  :list="countValueList"
                  list-value-field="name"
                  type="radio-buttons"
                  @input="getCountValueFilterValues"
                />
              </div>
            </div>
            <div class="card-body pt-5">
              <app-chart
                v-if="isActive"
                :data-sets="chartData.dataSet"
                :height="380"
                :labels="chartData.labels"
                type="horizontal-line-chart"
              />
              <hr class="my-5 reports-table-divider" />
              <app-table
                :id="tableId"
                :filtered-data="filterValues"
                :options="options"
                @action="triggerActions"
                class="remove-datatable-x-padding"
              />
            </div>
          </div>
        </div>
      </div>

      <!--modal for details view-->
      <reports-pipeline-details
        v-if="detailsViewModal"
        :filter-values="filterValues"
        :find-by-id="findById"
        :modal-id="'details-view-modal'"
        :pageHeader="detailsModalHeader"
        :table-id="tableId"
        @close-modal="closedDetailsViewModal"
      />
    </template>
  </div>
</template>

<script>
import CoreLibrary from "../../../../../core/helpers/CoreLibrary";
import { FormMixin } from "../../../../../core/mixins/form/FormMixin.js";
import { owner, pipeline, stages } from "../../../../Mixins/Global/ReportMixin";
import { api } from "@app/Helpers/api";
import { check } from "@app/Helpers/check";
import { numberFormatter, urlGenerator, reportDealsAvgAge } from "@app/Helpers/helpers";

export default {
  name: "ReportPipeline",
  mixins: [pipeline, stages, owner, FormMixin],
  extends: CoreLibrary,
  data() {
    return {
      urlGenerator,
        reportDealsAvgAge,
      detailsModalHeader: "",
      findById: null,
      isActive: false,
      initialFilterDataLoad: false,
      isFiltersOpen: true,
      reportBy: 1,
      filterValues: {
        pipeline: 1,
        groupBy: "owner_id",
        countValue: 1,
      },
        allStages: [],
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
      filters: [
        {
          title: this.$t("pipeline"),
          type: "dropdown-menu-filter",
          key: "pipeline",
          initValue: null,
          option: [],
        },
        {
          title: this.$t("select_a_pipeline"),
          type: "dropdown-menu-filter",
          key: "deal_strategy",
          initValue: 1,
          option: [
            {
              id: 1,
              name: this.$t("how_many_new_deals_were_started"),
              icon: "clipboard",
            },
            {
              id: 2,
              name: this.$t("how_many_new_deals_are_processing"),
              icon: "framer",
            },
            {
              id: 3,
              name: this.$t("how_was_the_journey_of_closed_deals"),
              icon: "activity",
            },
          ],
          listValueField: "name",
        },
        {
          title: this.$t("created_date"),
          type: "range-picker",
          key: "date",
          option: ["today", "thisMonth", "last7Days", "thisYear"],
        },
      ],
      //chart
      showChartFilter: true,
      groupBy: "",
      chartSubFilterValue: {},
      showChartSubFilter: false,
      reportsChartsFilter: [
        { id: "owner_id", value: this.$t("by_owner") },
        { id: "stage_id", value: this.$t("by_stage") },
      ],
      secondaryFilters: [
        {
          title: this.$t("everyone"),
          type: "dropdown-menu-filter",
          key: "owner",
          option: [],
          listValueField: "value",
        },
      ],
      labelsReportsChart: [],
      dataSetReportsChart: [],
      // datatable
      detailsViewModal: false,
      tableId: "report-pipeline-table",
      options: {
        name: "pipeline-report",
        url: route('pipeline.view-data-table'),
        datatableWrapper: false,
        showHeader: true,
        columns: [],
        tablePaddingClass: "px-0",
        tableShadow: false,
        showSearch: false,
        showFilter: false,
        // paginationType: 'pagination',
        responsive: true,
        rowLimit: 10,
        orderBy: "desc",
      },
      getStatuses: [],
      chartData: {},
      tempStages: [],
      breadcrumbTitle: "",
    };
  },

  mounted() {
    // for filter and search
    this.isFiltersActive();
    window.onresize = () => {
      this.isFiltersActive();
    };
    this.setInitialTableCloumns();
      this.setInitialPipeline().then(()=>{
          setTimeout(() => {
              this.getStages();
          });
      });
  },
  methods: {
      getStages(){
          this.axiosGet( route('stages.index',{_query:{all:true}}))
              .then((response) => {
                  this.allStages = response.data;
                  this.getFilterValues({ pipeline: 1, countValue: 1 });
                  this.initialFilterDataLoad = true;
              })
              .catch(({ error }) => {

              })
              .finally(() => {

              });
      },
    async setInitialPipeline() {
        this.axiosGet(route('pipelines.index'))
        .then((response) => {
          if (response.data.length) {
            this.filters.find(({ key }) => key === "pipeline").initValue =
              response.data[0].id;
            this.filterValues.pipeline = response.data[0].id;
          }
        });
    },
    redirect(url = "/") {
      window.location = urlGenerator(url);
    },
    setInitialTableCloumns() {
      let tempStages = [];

        this.axiosGet(route('stages.index'))
        .then((res) => {
          tempStages = res.data.data.filter((element) => {
            if (element.pipeline_id === this.filterValues.pipeline) {
              return element;
            }
          });

          this.options.columns = [];

          this.options.columns.push(
            {
              title: this.$t("owner_name"),
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
              title: this.$t("total_no_of_deals"),
              type: "text",
              key: "total_no_of_deals",
            }
          );

          tempStages.forEach((stage, index) => {
            this.options.columns.push({
              title: stage.name,
              type: "object",
              key: "pipeline_stages",
              modifier: (value, row) => {
                return value[stage.name];
              },
            });
          });
        });
    },
    // for filter and search
    getFilterValues(filterValue) {
      let filterArray = this.filters[1].option.find((item) => {
        return item.id == filterValue.deal_strategy;
      });
      this.breadcrumbTitle = filterArray ? filterArray.name : "";
      //merging two objects to get updated primary filters value
      this.filterValues = Object.assign(this.filterValues, filterValue);

      this.setDynamicColumns();

      setTimeout(() => {
        // Your working functions after filter
        this.$hub.$emit("reload-" + this.tableId);
      });

      this.getChartData().then(() => {});
    },
    //chart tab filter
    getGroupByFilterOptions(item) {
      this.groupBy = item;
      this.filterValues.groupBy = item;
      if (item == "owner_id") delete this.filterValues.owner;

      this.showChartSubFilter = this.groupBy == "stage_id" ? true : false;
      setTimeout(() => {
        this.setFilterOption();
        this.setDynamicColumns();
        this.getChartData().then(() => {});
        this.$hub.$emit("reload-" + this.tableId);
      });
    },
    getChartSubFilterValues(value) {
      this.chartSubFilterValue = value;
      this.filterValues.owner = value.owner;
      this.setDynamicColumns();
      setTimeout(() => {
        this.getChartData().then(() => {});
        this.$hub.$emit("reload-" + this.tableId);
      });
    },
    getCountValueFilterValues() {
      this.filterValues.countValue = this.reportBy;
      this.setDynamicColumns();

      setTimeout(() => {
        this.getChartData().then(() => {});
        this.$hub.$emit("reload-" + this.tableId);
      });
    },
    toggleFilters() {
      this.isFiltersOpen = !this.isFiltersOpen;
    },

    isFiltersActive() {
      this.isFiltersOpen = window.innerWidth > 575;
    },
    pageHeader(row) {
      if (this.filterValues.deal_strategy == 1) {
        this.detailsModalHeader =
          this.$t("details_view_of_deals_started_by") + row.group_by.name;
      } else if (this.filterValues.deal_strategy == 2) {
        this.detailsModalHeader =
          this.$t("details_view_of_processing_started_by") + row.group_by.name;
      } else {
        this.detailsModalHeader =
          this.$t("details_view_of_deals_closed_by") + row.group_by.name;
      }

      if (this.filterValues.groupBy == "stage_id") {
        this.detailsModalHeader = this.detailsModalHeader + " stage";
      }
    },
    triggerActions(row, action, active) {
      this.findById = row.group_by.id;
      this.pageHeader(row);
      //Datatable
      if (action.key === "group_by") {
        this.detailsViewModal = true;
      }
    },
    closedDetailsViewModal() {
      this.detailsViewModal = false;
    },
    setFilterOption(value) {
      let indexOfStageFilter = this.secondaryFilters.findIndex(
        (element) => element.key === "owner"
      );

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
    },
    getChartData() {
      this.isActive = false;
      let reqData = {};
      reqData.params = this.filterValues;

      return this.axiosGet(route('pipeline.view-chart'), reqData)
        .then((response) => {
          this.chartData = response.data;
          this.chartData.dataSet[0].data.push(0);
        })
        .catch(({ response }) => {})
        .finally(() => {
          //this.mainPreloader = false;
          this.isActive = true;
        });
    },
    setDynamicColumns() {
      //Set datatable column titles and values as per change of filter

      this.filterValues.groupBy === "owner_id"
        ? this.setColumnForOwner()
        : this.setColumnForStage();
    },
    setColumnForOwner() {
      let tempStages = this.allStages.filter(item => item.pipeline_id == this.filterValues.pipeline)
          .map((stage)=>{
              return{
                  id: stage.id,
                  pipeline_id: stage.pipeline_id,
                  value: stage.name
              }
          });
      this.tempStages = tempStages;

      this.options.columns = [];

      this.options.columns.push({
        title: this.$t("owner_name"),
        type: "button",
        key: "group_by",
        sortAble: true,
        isVisible: true,
        className: "btn btn-link text-primary pl-0 text-decoration-none",
        modifier: (value, row) => {
          return value.name;
        },
      });

      if (this.filterValues.deal_strategy == 1) {
        this.options.columns.push({
          title: this.$t("total_no_of_deals"),
          type: "text",
          key: "total_no_of_deals",
        });
      }

      let dealToNextStage = 0;
      tempStages.forEach((stage, index) => {
        // if (this.filterValues.deal_strategy == 1) {
        //   this.options.columns.push({
        //     title: stage.value,
        //     type: "object",
        //     key: "pipeline_stages",
        //     modifier: (value, row) => {
        //       return value[stage.value];
        //     },
        //   });
        // }

        // if (this.filterValues.deal_strategy == 2) {
        //   if (index < tempStages.length - 1) {
        //     this.options.columns.push({
        //       title: stage.value + " > " + tempStages[index + 1].value,
        //       type: "object",
        //       key: "pipeline_stages",
        //       modifier: (value, row) => {
        //         console.log(row);
        //         let nextColkey = _.snakeCase(tempStages[index + 1].value);
        //         let DealCurrentCount = row.pipeline_stages[nextColkey];
        //         // console.log(dealInitCount);
        //         let nextData = value[_.snakeCase(stage.value)];
        //         let percentValue = (DealCurrentCount / nextData) * 100;
        //         return (
        //           nextData +
        //           "|" +
        //           DealCurrentCount +
        //           "(" +
        //           numberFormatter(percentValue) +
        //           "%)"
        //         );
        //       },
        //     });
        //   }
        // }

        // if (this.filterValues.deal_strategy == 3) {
          if (index > 0) {
              this.options.columns.push({
                  title: tempStages[index - 1].value + " > " + stage.value,
                  type: "object",
                  key: "pipeline_stages",
                  modifier: (value, row) => {
                      let prevColkey = tempStages[index - 1].value;
                      let DealCurrentCount = value[stage.value] ? value[stage.value] : 0;
                      let dealInitCount = index == 1 ? row.total_no_of_deals : dealToNextStage;
                      dealToNextStage =
                          index == 1
                              ? row.total_no_of_deals -
                              (row.pipeline_stages[prevColkey]
                                  ? row.pipeline_stages[prevColkey]
                                  : 0)
                              : dealToNextStage - DealCurrentCount;
                      dealToNextStage = check(dealToNextStage).isNumber() ? dealToNextStage : 0;
                      let percentValue =
                          dealInitCount > 0 ? (dealToNextStage / dealInitCount) * 100 : 0;
                      return (
                          // dealInitCount +
                          // ">|" +
                          // DealCurrentCount +
                          // "|" +
                          dealToNextStage + "(" + numberFormatter(percentValue) + "%)"

                          // + "|" +
                          // DealCurrentCount
                      );
                  },
              });
          }
      });

      if (this.filterValues.deal_strategy == 3) {
        this.options.columns.push(
          {
            title: this.$t("total_won"),
            type: "object",
            key: "total_deal_won",
            modifier: (value, row) => {
              let percentValue = (value / row.total_no_of_deals) * 100;
              return (
                value +
                // "|" +
                // row.total_no_of_deals +
                "(" +
                numberFormatter(percentValue) +
                "%)"
              );
            },
          },
          {
            title: this.$t("total_lost"),
            type: "object",
            key: "total_deal_lost",
            modifier: (value, row) => {
              let percentValue = (value / row.total_no_of_deals) * 100;
              return (
                value +
                // "|" +
                // row.total_no_of_deals +
                "(" +
                numberFormatter(percentValue) +
                "%)"
              );
            },
          }
        );
      }
    },
    setColumnForStage() {
      this.options.columns = [];

      this.options.columns.push(
        {
          title: this.$t("stage_name"),
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
          title: this.$t("deal_started"),
          type: "text",
          key: "deal_started",
        },
        {
          title: this.$t("total_deal_value"),
          type: "text",
          key: "total_deal_value",
        },
        {
          title: this.$t("avg_deal_value"),
          type: "custom-html",
          key: "avg_deal_value",
            modifier: (value) => {
                return `<p class="mb-0 d-flex align-items-center text-nowrap">${numberFormatter(
                    value
                )}</p>`;
            },
        },
        {
          title: this.$t("avg_age_of_deal"),
          type: "custom-html",
          key: "avg_deal_age",
            modifier: (value) => {
                return `<p class="mb-0 d-flex align-items-center text-nowrap">${reportDealsAvgAge(
                    value
                )}</p>`;
            },
        }
      );
    },
  },
};
</script>
