<template>
  <div class="content-wrapper calendar-position-modified">
    <div class="row">
      <div class="col-sm-12 col-md-6">
        <app-breadcrumb
          :page-title="$t('pipelines')"
          :directory="[$t('deals'), $t('pipelines')]"
          :icon="'clipboard'"
        />
      </div>
      <div class="col-sm-12 col-md-6">
        <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
          <button
            v-if="$can('create_pipelines')"
            type="button"
            class="btn btn-primary btn-with-shadow"
            @click.prevent="addPipeline"
          >
            {{ $t("add_pipeline") }}
          </button>
        </div>
      </div>
    </div>

    <app-table :id="'pipelines-table'" :options="options" @action="getAction" />

    <pipeline-delete-modal
      v-if="isDeleteModal"
      :modalId="tableId"
      :pipelineId="pipeline"
      :deletePipelineUrl="deleteUrl"
      @close-modal="closeModal"
    />
  </div>
</template>

<script>
import CoreLibrary from "@core/helpers/CoreLibrary.js";
import {
  formatDateToLocal,
  numberWithCurrencySymbol,
  urlGenerator,
} from "@app/Helpers/helpers";
import { owner, contactType } from "@app/Mixins/Global/FilterMixins";
import PipelineDeleteModal from "./PipelineDeleteModal";

export default {
  name: "Pipelines",
  extends: CoreLibrary,
  mixins: [owner, contactType],
  components: { PipelineDeleteModal },
  data() {
    return {
      urlGenerator,
      pipeline: "",
      isDeleteModal: false,
      deleteUrl: "",
      tableId: "pipelines-table",
      options: {
        name: this.$t("pipeline_table"),
        url: route('pipelines.index'),
        showHeader: true,
        columns: [
            {
                title: this.$t("name"),
                type: "component",
                key: "name",
                sortAble: true,
                isVisible: true,
                componentName: "pipeline-column-name",
            },

          {
            title: this.$t("total_deal_value"),
            type: "custom-html",
            key: "total_deal_value",
            modifier: (value) => {
              return `<p class="mb-0 d-flex align-items-center text-nowrap">${numberWithCurrencySymbol(
                value
              )}</p>`;
            },
          },
          {
            title: this.$t("no_of_deals"),
            type: "text",
            key: "deals_count",
          },
          {
            title: this.$t("no_of_stage"),
            type: "text",
            key: "stage_count",
          },
          {
            title: this.$t("created_date"),
            type: "custom-html",
            key: "created_at",
            isVisible: true,
            modifier: (date) => formatDateToLocal(date),
          }
        ],
        filters: [
          {
            title: this.$t("owner"),
            type: "checkbox",
            key: "owner_is",
            option: [],
            permission: this.$can('manage_public_access') ? true : false
          },
          {
            title: this.$t("created_date"),
            type: "range-picker",
            key: "date",
            option: ["today", "thisMonth", "last7Days", "thisYear"],
          },
        ],
        showSearch: true,
        showFilter: true,
        paginationType: "pagination",
        responsive: true,
        rowLimit: 10,
        showAction: true,
        orderBy: "desc",
        actionType: "default",
        enableCookie: false,
        showCount: true,
        showClearFilter: true,
        showManageColumn: !this.$can('manage_public_access') && this.$can('client_access') ? false : true,
        actions: [
          {
            title: this.$t("edit"),
            icon: "edit",
            type: "page",
            modifier: () => this.$can("update_pipelines"),
          },
          {
            title: this.$t("delete"),
            icon: "trash",
            type: "modal",
            component: "pipeline-delete-modal",
            modalId: "pipeline-delete-modal",
            url: "",
            modifier: () => this.$can("delete_pipelines"),
          },
        ],
      },
    };
  },
  methods: {
    getAction(row, action, active) {
      if (action.title == this.$t("edit")) {
        window.location.replace(route('pipelines.edit', {id: row.id}));
      } else if (action.title == this.$t("delete")) {
        this.pipeline = row.id;
        this.deleteUrl = route('pipelines.destroy', {id: row.id});
        this.isDeleteModal = true;
      }
    },

    addPipeline() {
      window.location.replace(route('pipelines.create'));
    },
    closeModal() {
      this.isDeleteModal = false;
      $("#delete-pipeline").modal("hide");
    },
  },
    created(){
        if (this.$can("update_pipelines") || this.$can("delete_pipelines")){
            this.options.columns = [...this.options.columns, {
                title: 'Action',
                type: 'action',
                key: 'invoice',
                isVisible: true
            },]
        }

    }
};
</script>
