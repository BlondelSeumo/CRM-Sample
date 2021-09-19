<template>
  <div class="content-wrapper calendar-position-modified">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb
          :page-title="currentPipeline"
          :directory="[$t('deals'), $t('all_deals'), currentPipeline]"
          :icon="'clipboard'"
        />
      </div>
      <div class="col-sm-12 col-md-6">
        <div class="float-md-right mb-primary mb-sm-0 mb-md-0">
          <div class="dropdown d-inline-block btn-dropdown"
          v-if="
                $can('import_deal') ||
                $can('deal_reports') ||
                $can('create_lost_reasons') ||
                $can('view_lost_reasons')">
            <button
              type="button"
              class="btn btn-success dropdown-toggle ml-0 mr-2"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              {{ $t("actions") }}
            </button>
            <div class="dropdown-menu">
              <a v-if="$can('import_deal')"
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

                <a  v-if="$can('deal_reports')"
                    class="dropdown-item d-flex align-items-center p-3"
                    :href="route('deal.export')"
                >
                    <app-icon
                        stroke-width="1"
                        :name="'download'"
                        width="16"
                        height="16"
                        class="mr-3"
                    />
                    {{ $t("export_deal") }}
                </a>

              <a
                v-if="$can('create_lost_reasons')"
                class="dropdown-item d-flex align-items-center p-3"
                href=""
                @click.prevent="openLostreasonModal"
              >
                <app-icon
                  stroke-width="1"
                  :name="'plus-square'"
                  width="16"
                  height="16"
                  class="mr-3"
                />
                {{ $t("add_lost_reasons") }}
              </a>

              <a
                v-if="$can('view_lost_reasons')"
                class="dropdown-item d-flex align-items-center p-3"
                :href="route('lost_reasons.lists')"
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
            type="button"
            v-if="$can('create_deals')"
            class="btn btn-primary btn-with-shadow"
            data-toggle="modal"
            @click="openModal()"
          >
            {{ $t("add_deal") }}
          </button>
        </div>
      </div>
    </div>

    <app-table
      :id="tableId"
      :card-view="true"
      :options="options"
      @getFilteredValues="filteredValues"
      @action="getAction"
    />

    <app-deal-modal
      v-if="isDealModalActive"
      :table-id="tableId"
      :selected-url="selectedUrl"
      :pipeline-id="pipelineId"
      @close-modal="closeModal"
    />

    <app-confirmation-modal
      v-if="confirmationModalActive"
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

      <deal-details-modal
          v-if="isDetailsViewActive"
          :selected-deal="selectedDeal"
          :pipelines="pipelineList"
          :stages="stageList"
          @deal-update="dealUpdated"
          @close-modal="closeDetailsViewModal"
      />
  </div>
</template>

<script>
import CoreLibrary from "@core/helpers/CoreLibrary.js";
import { dealStatus, tag, owner, contactType, pipeline } from "@app/Mixins/Global/FilterMixins";
import { numberWithCurrencySymbol, urlGenerator } from "@app/Helpers/helpers";
import { getCustomFileds } from "@app/Mixins/Global/CustomFieldMixin";
import moment from 'moment';

export default {
  name: "ListView",
  extends: CoreLibrary,
  mixins: [dealStatus, tag, owner, contactType, getCustomFileds, pipeline],
  data() {
    return {
      urlGenerator,
        route,
        isDetailsViewActive: false,
        selectedDeal: {},
        pipelineList: [],
      isDealModalActive: false,
      tableId: "deal-modal",
      confirmationModalActive: false,
      isModalActive: false,
      selectedUrlReason: "",
      deal: "",
      value: 0,
      selectedUrl: "",
      currentPipeline: "",
      pipelineId: 0,
      initialPipeline: null,
      commonColumn: [
        {
          title: this.$t("title"),
          type: "component",
          key: "title",
          sortAble: true,
          isVisible: true,
          componentName: "deal-title-column",
        },
        {
          title: this.$t("status"),
          type: "custom-html",
          key: "status",
          modifier: (value) => {
            return `<span class="badge badge-sm badge-pill badge-${value.class ?? "secondary"}">${
              value.translated_name
            }</span>`;
          },
        },
        {
          title: this.$t("deal_value"),
          type: "custom-html",
          key: "value",
          modifier: (value) => {
            return `<p class="mb-0 d-flex align-items-center text-nowrap">${numberWithCurrencySymbol(
              value
            )}</p>`;
          },
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
              ? (value[0].attach_login_user_id == window.user.id ? window.user.full_name : value[0].name)
              : `<p class="m-0 font-size-90 text-secondary">` +
                  this.$t("deal_has_no_contact_person") +
                  `</p>`;
          },
        },
        {
          title: this.$t("next_activity"),
          type: "custom-html",
          key: "next_activity",
          modifier: (value, row) => {
            return value
              ? moment(value.started_at).format('DD-MMM') +
              ' ('+value.activity_type.name+')'
               : '-';
          },
            isVisible: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
        },
        {
          title: this.$t("owner"),
          type: "custom-html",
          key: "owner",
          modifier: (value, row) => {
            return value
              ? value.full_name
              : `<p class="font-size-80 text-secondary">` +
                  this.$t("deal_has_no_owner") +
                  `</p>`;
          },
        },
        {
          title: this.$t("tags"),
          type: "component",
          key: "tags",
          isVisible: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
          componentName: "tags-type-column",
        },
        {
          title: "Action",
          type: "action",
          key: "invoice",
          isVisible: (this.$can("update_deals") || this.$can("delete_deals")) ? true : false,
        },
      ],
      options: {
        name: this.$t("deal_table"),
        url: route('deals.index'),
        showHeader: true,
        columns: [],
        filters: [
        {
            title: this.$t("select_a_pipeline"),
            type: "dropdown-menu-filter",
            key: "pipeline",
            initValue: 'all',
            option: [],
        },
          {
            title: this.$t("owner"),
            type: "checkbox",
            key: "owner_is",
            option: [],
            permission: this.$can('manage_public_access') ? true : false
          },
          {
            title: this.$t("lead_group"),
            type: "checkbox",
            key: "contact_type",
            option: [],
            permission: this.$can('view_types') ? true : false
          },
          {
            title: "Created date",
            type: "range-picker",
            key: "date",
            option: ["today", "thisMonth", "last7Days", "thisYear"],
          },
          {
            title: this.$t('deals_with_proposals'),
            type: "radio",
            key: "deal_with_proposal",
            header: {
              title: "Want to filter your deal?",
              description: "You can filter your deal list which don't have any proposal",
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
            title: this.$t("status"),
            type: "checkbox",
            key: "status",
            option: [],
          },
          {
            title: this.$t("tag"),
            type: "multi-select-filter",
            key: "tags",
            option: [],
              permission: (!this.$can('manage_public_access') && this.$can('client_access')) ? false : true,
          },
          {
            title: this.$t("deal_value"),
            type: "range-filter",
            key: "deal-value",
            minTitle: this.$t("minimum_value"),
            maxTitle: this.$t("maximum_value"),
            maxRange: 100,
            minRange: 0
          }
        ],
        showSearch: true,
        showFilter: true,
        paginationType: "pagination",
        responsive: true,
        rowLimit: 10,
        showAction: true,
        showManageColumn: !this.$can('manage_public_access') && this.$can('client_access') ? false : true,
        orderBy: "desc",
        actionType: "default",
        actions: [
          {
            title: this.$t("edit"),
            icon: "edit",
            type: "modal",
            component: "app-deal-modal",
            modalId: "deal-modal",
            url: "",
            modifier: () => this.$can("update_deals"),
          },
          {
            title: this.$t("delete"),
            icon: "trash",
            type: "modal",
            component: "app-confirmation-modal",
            modalId: "deal-delete-modal",
            url: "",
            modifier: () => this.$can("delete_deals"),
          },
        ],
        showCount: true,
        showClearFilter: true,
        cardViewComponent: 'app-deals-card-view',
      },
    };
  },
    computed:{
        stageList() {
            return this.$store.getters.getStages.filter((stage)=>{
                if (stage.pipeline_id == this.selectedDeal.pipeline_id){
                    return stage;
                }
            });
        },
    },
  methods: {
    filteredValues(value) {
      if (value["pipeline"]) {
        this.pipelineId = value['pipeline'] === 'all' ? this.initialPipeline : value.pipeline;
        this.currentPipeline = this.initialPipeline ? this.options.filters
          .find((el) => el.title === this.$t("select_a_pipeline"))
          .option.find((el) => el.id === value.pipeline).value : this.currentPipeline = this.$t('all_pipeline');;
      }
    },
      afterClearAllFilter(){
          this.$hub.$on('clearAllFilter-' + this.tableId, ()=> {
              this.currentPipeline = this.$t('all_pipeline');
          })
      },
    getDealValue() {
      this.axiosGet(route('deal.value')).then((response) => {
        let dealValueFilter = this.options.filters.find(item => item.key === 'deal-value');
        dealValueFilter.maxRange = response.data.max_deal_value;
        dealValueFilter.minRange =
            response.data.min_deal_value < response.data.max_deal_value ? response.data.min_deal_value : 0;
        /*this.options.filters.push({
          title: this.$t("deal_value"),
          type: "range-filter",
          key: "deal-value",
          minTitle: this.$t("minimum_value"),
          maxTitle: this.$t("maximum_value"),
          maxRange: response.data.max_deal_value,
          minRange:
            response.data.min_deal_value < response.data.max_deal_value
              ? response.data.min_deal_value
              : 0,
        });*/
      });
    },
      setPipelineFilterInit() {
        let pipeline = this.options.filters.find((item)=> item.title === this.$t('select_a_pipeline'))
          this.axiosGet(route('pipelines.index')).then((response) => {
              if (response.data.data.length){
                  this.pipelineList = response.data.data;
                  pipeline.option.unshift({
                      id: 'all',
                      value: this.$t('all_pipeline')
                  })
                  this.initialPipeline = response.data.data[0].id;
                  this.pipelineId = this.initialPipeline;
              }
              this.currentPipeline = this.$t('all_pipeline');
          });
    },
    getAction(rowData, actionObj, active) {

      if (actionObj.title == this.$t("edit")) {
          if (rowData.status.name == 'status_open'){
              this.selectedUrl = route('deals.show', {id: rowData.id});
              this.isDealModalActive = true;
          }else {
              this.$toastr.s(this.$t('please_reopen_deal_before_edit_deal'));
          }
      } else if (actionObj.title == this.$t("delete")) {
          this.deal = rowData.id;
          this.confirmationModalActive = true;
      }
      else if (actionObj.title == this.$t("quick_view")) {
          this.selectedDeal = rowData;
          this.selectedDeal.lead_type = rowData.contextable_type == "App\\Models\\CRM\\Organization\\Organization" ?
              2 : 1;
          this.isDetailsViewActive = true;
      }
    },
    confirmed() {
      let url = route('deals.destroy', {id: this.deal});
      this.axiosDelete(url)
        .then((response) => {
          this.$toastr.s(response.data.message);
            this.$hub.$emit("reload-" + this.tableId);
        })
        .catch(({ error }) => {
          //trigger after error
        })
        .finally(() => {
            this.cancelled();
        });
    },
    cancelled() {
        this.confirmationModalActive = false;
    },
    openModal() {
        this.isDealModalActive = true;
    },
    closeModal() {
      this.isDealModalActive = false;
        this.selectedUrl = "";
      $("#deal-modal").modal("hide");
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
      closeDetailsViewModal(){
          this.isDetailsViewActive = false;
          $("#detailsViewModal").modal("hide");
      },
      dealUpdated(){
          this.$hub.$emit("reload-" + this.tableId);
      },
  },
  mounted() {
    setTimeout(() => {
      // this.$store.dispatch("getOwner"),
        if (this.$can('view_persons')){
            this.$store.dispatch("getPerson");
        }
        if (this.$can('view_organizations')){
            this.$store.dispatch("getOrganization");
        }
      this.getDealValue();
    }, 1000);

      this.$store.dispatch("getStages");
    this.getCustomFiled("deal");
    this.afterClearAllFilter();
  },
  created() {
    // this.getDealValue();
    this.setPipelineFilterInit();
  },
};
</script>
