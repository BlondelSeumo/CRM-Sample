<template>
    <div class="content-wrapper reports">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :directory="[$t('reports'), $t('proposal'), breadcrumb]"
                    :icon="'bar-chart'"
                    :page-title="breadcrumb"
                />
            </div>
        </div>
        <!--filter-->
        <div v-if="isActivePrimaryFilters" class="row">
            <div class="col-6 col-sm-8 col-md-9 col-lg-10 col-xl-10">
                <div class="filters-wrapper d-flex justify-content-start flex-wrap">
                    <!--Open Filters Button For Mobile-->
                    <button class="btn d-block d-sm-none btn-toggle-filters"
                            type="button"
                            @click.prevent="toggleFilters">
                        <app-icon :name="'filter'"/>
                        {{ $t("filters") }}
                    </button>

                    <span v-if="isFiltersOpen" class="mobile-filters-wrapper">
                        <app-filter :filters="filters" :table-id="tableId" @get-values="getFilterValues"/>

                        <button class="btn btn-primary btn-with-shadow d-sm-none btn-close-filter-wrapper d-flex justify-content-center align-items-center"
                                type="button"
                                @click="toggleFilters">
                            {{ $t("close") }}
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <!--Horizontal bar chart-->
        <div v-if="dataLoaded" class="row">
            <div class="col-12 mb-primary">
                <div class="card card-with-shadow border-0 h-100">
                    <div class="card-body pt-5">
                        <app-chart
                            :data-sets="dataSetReportsChart"
                            :height="380"
                            :labels="labelsReportsChart"
                            type="horizontal-line-chart"/>

                        <hr class="my-5 reports-table-divider"/>

                        <app-table
                            :id="tableId"
                            :filtered-data="filterValues"
                            :options="options"
                            :search="searchValue"
                            @action="triggerActions"/>
                    </div>
                </div>
            </div>
            <proposal-details v-if="detailsViewModal"
                              :id="detailsViewId"
                              :filterValues="filterValues"
                              :fullName="fullName"
                              :modal-id="'details-view-modal'"
                              :proposalSentStatusId="proposalSentStatusId"
                              :table-id="'details-view-modal'"
                              @close-modal="closedDetailsViewModal"/>
        </div>
        <app-overlay-loader v-else/>

    </div>
</template>

<script>
import CoreLibrary from "../../../../../core/helpers/CoreLibrary";
import {formatDateToLocal, urlGenerator} from "../../../../Helpers/helpers";
import {pipeline, status} from "../../../../Mixins/Global/ReportMixin";
import {api} from "@app/Helpers/api";

export default {
    name: "ReportProposal",
    extends: CoreLibrary,
    mixins: [pipeline, status],
    data() {
        return {
            urlGenerator,
            //status filter type wise
            typeWiseStatues: [],
            statusType: "deal",

            isFiltersOpen: true,
            isActivePrimaryFilters: false,
            filterValues: {
                pipeline: 1,
            },
            searchValue: "",
            breadcrumb: this.$t("proposal_have_been_sent"),
            fullName: '',
            filters: [
                {
                    title: this.$t("pipeline"),
                    type: "dropdown-menu-filter",
                    key: "pipeline",
                    option: [],
                    listValueField: "value",
                },
                {
                    title: this.$t('proposal_have_been_sent'),
                    type: "dropdown-menu-filter",
                    key: "status_id",
                    option: [],
                    listValueField: "name",
                },
                {
                    title: this.$t("deal_status"),
                    type: "dropdown-menu-filter",
                    key: "deal_status_id",
                    header: {
                        title: this.$t("want_to_see_proposal_report_with"),
                        description: this.$t("you_can_filter_and_see_your_proposal_report_with_open_deal_won_deal_or_lost_deal"),
                    },
                    option: [],
                },
                {
                  title: this.$t("created_date"),
                  type: "range-picker",
                  key: "date",
                  option: ["today", "thisMonth", "last7Days", "thisYear"]
                },
            ],
            //chart
            showChartFilter: true,
            chartFilterValue: "by_owner",
            chartSubFilterValue: {},
            chartSubFilters: [],
            labelsReportsChart: [],
            dataSetReportsChart: [
                {
                    barPercentage: 1,
                    barThickness: 25,
                    borderWidth: 1,
                    borderColor: [],
                    backgroundColor: [],
                    data: [],

                },
            ],
            // datatable
            detailsViewModal: false,
            tableId: "report-proposal-table",
            options: {
                tableShadow: false,
                name: "Proposals",
                url: route('proposals.view-data-table'),
                datatableWrapper: false,
                showHeader: true,
                columns: [
                    {
                        title: this.$t("owner_name"),
                        type: "button",
                        key: "owner",
                        sortAble: true,
                        isVisible: true,
                        className: "btn btn-link text-primary pl-0 text-decoration-none",
                        modifier: (value, row) => {
                            return value.name
                        }
                    },
                    {
                        title: this.$t("proposal_sent"),
                        type: "text",
                        key: "counter",
                    },
                    {
                        title: this.$t("start_date"),
                        type: "object",
                        key: "started_date",
                        modifier: (date) => {
                            return formatDateToLocal(date);
                        },
                    },
                    {
                        title: this.$t("end_date"),
                        type: "object",
                        key: "end_date",
                        modifier: (date) => {
                            return formatDateToLocal(date);
                        },
                    },
                    {
                        title: this.$t("age_of_activity"),
                        type: "text",
                        key: "age_of_activity",
                    },
                ],
                tablePaddingClass: "px-0",
                showSearch: false,
                showFilter: false,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                orderBy: "desc",
            },

            //proposal
            proposalsByOwner: null,
            dataLoaded: false,
        };
    },
    created() {
        this.setInitialPipeline();
        let indexOfDealStatusFilter = this.filters.findIndex(element => element.key === 'deal_status_id');
        this.filters.find(({key, option}) => {
            if (key === 'deal_status_id') {
                this.filters[indexOfDealStatusFilter].option = this.typeWiseStatues;
                this.filters[indexOfDealStatusFilter].initValue = 11;
            }
        });

        this.getStatus('status_open', 'deal').then(res => {
            this.filterValues.deal_status_id = res;
            this.getStatusProposal().then(() => {
                this.isActivePrimaryFilters = true;
                this.getAllProposal().then(() => {
                    this.setChartsOptions();
                    this.$nextTick(() => {
                        this.dataLoaded = true;
                    });
                });
            })
        });

    },
    mounted() {
        // for filter and search
        this.isFiltersActive();
        window.onresize = () => {
            this.isFiltersActive();
        };
    },
    methods: {
        setInitialPipeline() {
            this.axiosGet(
                route("pipelines.index", {all: true}))
                .then((response) => {
                    // if (response.data.length == 0) {
                    //     this.redirect("/crm/pipelines/create");
                    // }
                    if (response.data.length){
                        this.filters.find(({key}) => key === 'pipeline').initValue = response.data[0].id;
                    }
                })
        },
        redirect(url = "/") {
            window.location = urlGenerator(url);
        },
        getStatusProposal() {

            return this.getStatus('status_sent', 'proposal').then(res => {
                this.proposalSentStatusId = res;
                this.filters.filter((element) => {
                    if (element.key === "status_id") {
                        element.initValue = this.proposalSentStatusId;
                        return element.option.push(
                            {
                                id: this.proposalSentStatusId,
                                name: this.$t("proposal_have_been_sent"),
                                icon: "send",
                            },
                        );
                    }
                });

                this.filterValues.status_id = this.proposalSentStatusId;

                this.getStatus('status_accepted', 'proposal').then(res => {
                    this.proposalAcceptedStatusId = res;
                    this.filters.filter((element) => {
                        if (element.key === "status_id") {
                            return element.option.push(
                                {
                                    id: this.proposalAcceptedStatusId,
                                    name: this.$t("proposal_have_been_accepted"),
                                    icon: "inbox",
                                },
                            );
                        }
                    });
                });
            });
        },
        getStatus(name, type) {
            return this.axiosGet(route("statuses.index", {
                _query: {
                    name: name,
                    type: type,
                },
            })).then(response => {
                return response.data[0].id;
            });
        },
        // set charts data and label
        setChartsOptions() {
            this.dataSetReportsChart[0].data = [];
            this.labelsReportsChart = [];

            if (this.proposalsByOwner.length > 0) {
                this.proposalsByOwner.map((el) => {
                    this.labelsReportsChart.push(el.owner);
                    if (el.owner === this.$t("average")) {
                        this.dataSetReportsChart[0].borderColor.push('#46cc97');
                        this.dataSetReportsChart[0].backgroundColor.push('#46cc97');

                    } else {
                        this.dataSetReportsChart[0].borderColor.push('#5a86f1');
                        this.dataSetReportsChart[0].backgroundColor.push('#5a86f1');
                    }

                    this.dataSetReportsChart[0].data.push(el.counter);
                });

                this.dataSetReportsChart[0].data.push(this.proposalsByOwner.length);
                this.dataSetReportsChart[0].data.push(0);
            }
        },
        // for filter and search
        getFilterValues(value) {
            value.status_id === this.proposalSentStatusId ? this.breadcrumb = this.$t("proposal_have_been_sent") : this.breadcrumb = this.$t("proposal_have_been_accepted");
            let temp = {};
            //merging two objects to get updated primary filters value
            temp = Object.assign(this.filterValues, value);

            value.date = value.created_date;
            delete value.created_date;
            this.filterValues = temp;
            this.dataLoaded = false;
            this.getAllProposal().then(() => {
                this.setChartsOptions();
                this.$nextTick(() => (this.dataLoaded = true));
            });
        },
        toggleFilters() {
            this.isFiltersOpen = !this.isFiltersOpen;
        },
        getSearchValue(value) {
            this.searchValue = value;
            setTimeout(() => {
                // Your working functions after Search
                this.$hub.$emit("reload-" + this.tableId);
            });
        },

        isFiltersActive() {
            this.isFiltersOpen = window.innerWidth > 575;
        },
        //chart tab filter
        getFilterValueFromChart(item) {
            this.chartFilterValue = item;
        },
        //Datatable
        triggerActions(row, action, active) {
            this.detailsViewId = row.owner.id;
            this.fullName = row.owner.name;
            this.detailsViewModal = true;
        },
        closedDetailsViewModal() {
            this.detailsViewModal = false;
        },
        getAllProposal() {
            // let reqData = {};
            // reqData.params = this.filterValues;
            return this.axiosGet(route('proposals.view-chart', {_query: { ...this.filterValues }}))
                .then(
                (res) => (this.proposalsByOwner = res.data)
            );
        },
    },
};
</script>
