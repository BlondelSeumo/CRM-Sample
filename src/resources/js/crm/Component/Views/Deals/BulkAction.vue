<template>
  <div>
    <div class="bulk-floating-action-wrapper">
      <div class="actions">
        <!-- move -->
        <div
          class="dropdown d-inline-block btn-dropdown keep-inside-clicks-open"
          :title="$t('move')"
          data-toggle="tooltip"
        >
          <button
            aria-expanded="false"
            aria-haspopup="true"
            class="btn btn-light dropdown-toggle border-right"
            data-toggle="dropdown"
            type="button"
            id="MoveMultipleDeals"
          >
            <app-icon name="arrow-right-circle" />
          </button>
          <div
            class="dropdown-menu p-primary"
            style="width: 500px"
            aria-labelledby="MoveMultipleDeals"
          >
            <form ref="form" :data-url="route('multiple_deals.move')">
              <h5 class="mb-3">{{ $t("move_deal") }}</h5>
              <div class="form-group">
                <div class="form-row">
                  <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                    $t("pipeline")
                  }}</label>
                  <div class="col-sm-9">
                    <app-input
                      v-model="formData.pipeline_id"
                      :list="pipelines"
                      :placeholder="$t('choose_a_pipeline')"
                      list-value-field="name"
                      type="select"
                    />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="form-row">
                  <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                    $t("stage")
                  }}</label>
                  <div class="col-sm-9">
                    <component
                      :is="'app-step-input-selector'"
                      v-if="pipelineChange"
                      v-model="formData.stage_id"
                      :required="true"
                      :step-complete="stageIndexAsId"
                      :step-lists="stageListAsPipelineId"
                      @stepChanges="setStageId"
                    />
                  </div>
                </div>
              </div>
              <div class="float-right">
                <button
                  class="btn btn-primary"
                  data-dismiss="modal"
                  type="button"
                  @click.prevent="move"
                >
                  <span v-if="loading" class="w-100">
                    <app-submit-button-loader></app-submit-button-loader>
                  </span>
                  <template v-else>{{ $t("move") }}</template>
                </button>
              </div>
            </form>
          </div>
        </div>
        <!-- end of move -->
        <!-- tag -->
        <div
          class="dropdown d-inline-block btn-dropdown"
          :title="$t('manage_tag')"
          data-toggle="tooltip"
        >
          <bulk-action-tag-manager
            :tags="allSelectedTags"
            :list="allTags"
            list-value-field="name"
            :tag-preloader="tagPreloader"
            @storeTag="storeAndAttachTag"
            @attachTag="attachTag"
            @detachTag="detachTag"
          />
        </div>
        <!-- end of tag-->
        <!-- delete -->
        <div
          class="dropdown d-inline-block btn-dropdown"
          :title="$t('delete')"
          data-toggle="tooltip"
        >
          <button
            class="btn btn-light dropdown-toggle border-right"
            type="button"
            @click.prevent="deleteDeal"
          >
            <app-icon name="trash-2" />
          </button>
        </div>
        <!-- end of delete -->
      </div>
    </div>
    <app-confirmation-modal
      v-if="dealDeleteModal"
      modal-id="deal-delete-modal"
      @confirmed="deleteDeals"
      @cancelled="cancelled"
    />
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin";
import { collect } from "../../../Helpers/Collection";

export default {
  name: "BulkAction",
  mixins: [FormMixin],
  props: {
    highlightIds: {
      type: Array,
      required: true,
    },
    pipelineId: {
      // type: Number,
      // required: true,
    },
    selectedDeal: {
      type: Array,
      required: true,
    },
    pipelines: {},
  },
  data() {
    return {
      route,
      pipelineChange: true,
      dataLoaded: false,
      loading: false,
      formData: {
        deals: this.selectedDeal,
        pipeline_id: null,
      },
      stageList: [],
      dealDeleteModal: false,
      tagPreloader: false,
      allSelectedTags: [],
    };
  },
  computed: {
    stageListAsPipelineId() {
      this.loadStepInput();
      return this.stageList.filter(
        (v) => v.pipeline_id == this.formData.pipeline_id
      );
    },
    stageIndexAsId() {
      let index = undefined;
      let stageListByPipeLine = this.stageList.filter(
        (v) => v.pipeline_id == this.formData.pipeline_id
      );
      for (let i = 0; i < stageListByPipeLine.length; i++) {
        if (stageListByPipeLine[i].id === this.formData.stage_id) {
          index = i;
        }
      }
      return index;
    },
    allTags() {
      return this.$store.getters.getAllTags.map((tag) => {
        return {
          id: tag.id,
          color: tag.color_code,
          name: tag.name,
        };
      });
    },
  },
  watch: {
    "pipelineId.length": {
      handler: function (length) {
        this.formData.pipeline_id = this.pipelineId;
      },
      immediate: true,
    },
    selectedDeal: {
      handler: function (deals) {
        this.axiosGet(
          route("deals.highlights", {
            _query: { selected_deals: deals.toString() },
          })
        ).then((res) => {
          this.allSelectedTags = [
            ...new Set(
              res.data
                .map((deal) => deal.tags)
                .reduce((allTags, tag) => allTags.concat(tag), [])
                .map((item) => item.id)
            ),
          ];
        });
      },
      immediate: true,
    },
  },
  methods: {
    cancelled() {
      this.dealDeleteModal = false;
      $("#deal-delete-modal").modal("hide");
    },
    clearMarked() {
      this.$emit("clear");
    },
    deleteDeal() {
      this.dealDeleteModal = true;
    },
    move() {
      //perform move action
      this.formData.pipeline_id = Number(this.formData.pipeline_id);
      this.save(this.formData);
      $("#MoveMultipleDeals").dropdown("toggle");
    },
    deleteDeals() {
      //perform delete action
      this.axiosPost({
        url: route("multiple_deals.delete"),
        data: {
          deals: this.selectedDeal,
        },
      }).then(() => {
        this.cancelled();
        this.clearMarked();
        this.refresh();
      });
    },
    refresh() {
      this.$emit("refresh");
    },
    afterSuccess(response) {
      this.refresh();
    },
    afterFinalResponse() {},
    loadStepInput() {
      this.pipelineChange = false;
      this.$nextTick(() => {
        this.pipelineChange = true;
      });
    },
    getStages() {
      this.dataLoaded = true;
      this.axiosGet(route("stages.index", { _query: { all: true } }))
        .then((response) => {
          this.stageList = collect(response.data)
            .where("pipeline_id", this.formData.pipeline_id)
            .sortBy("priority")
            .get();
          this.stageId = this.stageList.find(
            (v) => v.pipeline_id == this.formData.pipeline_id
          );
        })
        .catch(({ error }) => {})
        .finally(() => {
          this.dataLoaded = false;
        });
    },
    setStageId(index) {
      if (this.stageListAsPipelineId.length) {
        this.formData.stage_id = this.stageListAsPipelineId[index].id;
      }
    },
    storeAndAttachTag({ name, color }) {
      this.axiosPost({
        url: route("tags.index"),
        data: { name, color_code: color },
      })
        .then((response) => {
          this.$store.dispatch("getAllTags");
          this.attachTag(response.data.id);
        })
        .catch((error) => this.$toastr.e(error.response.data.errors.name[0]));
    },
    attachTag(tag_id) {
      this.axiosPost({
        url: route("multiple_deals.tag_attach"),
        data: {
          tag_id: tag_id,
          deals: this.selectedDeal,
        },
      }).then((response) => {
        this.allSelectedTags.push(tag_id);
        this.$toastr.s(response.data.message);
        this.refresh();
      });
    },
    detachTag(tag_id) {
      this.axiosPost({
        url: route("multiple_deals.tag_dettach"),
        data: {
          tag_id: tag_id,
          deals: this.selectedDeal,
        },
      }).then((response) => {
        this.$toastr.s(response.data.message);
        let index = this.allSelectedTags.indexOf(tag_id);
        this.allSelectedTags.splice(index, 1);
        this.refresh();
      });
    },
  },
  mounted() {
    this.$store.dispatch("getAllTags");
    this.getStages();
  },
};
</script>
