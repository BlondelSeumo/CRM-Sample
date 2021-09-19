<template>
  <div class="content-wrapper">
    <form ref="form" :data-url="selectedUrl">
      <div class="row">
        <div class="col-12 col-sm-6">
          <app-breadcrumb
            :page-title="$t('edit_pipeline')"
            :directory="[$t('deals'), $t('edit_pipeline')]"
            :icon="'clipboard'"
            :button="{ label: $t('back') }"
          />
        </div>
        <div class="col-sm-12 col-md-6">
          <div class="text-sm-right mb-primary mb-sm-0 mb-md-0">
              <button type="button" class="btn btn-primary" @click.prevent="saveData">
                <span class="w-100" v-if="loading">
                    <app-submit-button-loader></app-submit-button-loader>
                </span>
                  <template v-else>{{ $t("save") }}</template>
              </button>
            <button
              type="button"
              onclick="window.history.back()"
              class="btn btn-secondary btn-with-shadow"
            >
              {{ $t("cancel") }}
            </button>
          </div>
        </div>
      </div>
      <div class="card card-with-shadow border-0">
        <div class="card-body" style="padding: 2rem 1.5rem">
          <div class="form-group mb-primary px-2">
            <div class="row">
              <label class="m-0 d-flex align-items-center col col-sm-2">
                {{ $t("name") }}
              </label>
              <div class="col col-sm-6">
                <app-input
                  type="text"
                  :placeholder="$t('type_pipeline_name')"
                  :required="true"
                  v-model="formData.name"
                  @keydown.enter.prevent=""
                />
                <small class="text-danger" v-if="errors.name">{{ errors.name[0] }}</small>
              </div>
            </div>
          </div>
          <div class="d-flex custom-scrollbar overflow-auto">
            <draggable
              class="kanban-wrapper pr-0"
              :class="{ 'pl-0': stageList.length === 0 }"
              :list="stageList"
              group="deals"
            >
              <div
                class="kanban-column rounded"
                v-for="(stage, index) in stageList"
                :key="index"
                style="cursor: move"
              >
                <div class="p-3 mb-2 stage-header">
                  <h6 class="text-truncate" v-if="stage.name !== ''">{{ stage.name }}</h6>
                  <h6 class="text-truncate" v-else>{{ $t("untitled_stage") }}</h6>
                  <div
                    class="text-muted d-flex flex-wrap align-items-center stage-information"
                  >
                    <span v-if="stage.probability !== ''">{{ stage.probability }}</span>
                    <span v-else>0%</span>
                  </div>
                </div>
                <div class="card border-0 m-2">
                  <div class="card-body p-3">
                    <div class="form-group">
                      <label>
                        {{ $t("name") }}
                      </label>
                      <app-input
                        :id="'stage-name-' + stage.id"
                        type="text"
                        :placeholder="$t('type_pipeline_name')"
                        v-model="stage.name"
                        @keydown.enter.prevent=""
                      />
                        <small v-if="errors['stage.' + index + '.name']" class="text-danger">
                            {{ $t(errors['stage.' + index + '.name'][0]) }}
                        </small>
                    </div>
                    <div class="form-group">
                      <label>
                        {{ $t("probability") }}
                      </label>
                      <app-input
                        :id="'probability-' + stage.id"
                        type="number"
                        :placeholder="$t('type_probability')"
                        v-model="stage.probability"
                        @keydown.enter.prevent=""
                      />
                    </div>

                    <button
                      v-if="stage.deals_count > 0 && stage.id"
                      class="btn btn-stage-action mt-3 shadow"
                      @click.prevent="deleteStage(stage.id)"
                    >
                      <app-icon name="trash" class="mr-1" />
                      {{ $t("delete_stage") }}
                    </button>

                    <button
                      v-else
                      class="btn btn-stage-action mt-3 shadow"
                      @click.prevent="domDeleteStage(index, stage.id)"
                    >
                      <app-icon name="trash" class="mr-1" />
                      {{ $t("delete_stage") }}
                    </button>
                  </div>
                </div>
              </div>
            </draggable>
            <div class="add-new-stage">
              <div
                class="text-center p-primary rounded h-100 d-flex align-items-center flex-column justify-content-center wrapper"
              >
                <h6 class="text-muted">{{ $t("add_new_stage") }}</h6>
                <p class="text-muted font-size-90">
                  {{ $t("add_new_stage_description") }}
                </p>
                <button class="btn btn-primary" @click.prevent="addNewStage">
                  {{ $t("add_stage") }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>

    <pipe-line-stage-delete-modal
      v-if="isModalActive"
      modal-id="delete-pipeline-stage"
      :option="stageListForModal"
      :deleteStageUrl="deleteStageUrl"
      @close-modal="closeModal"
    />
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin";
import draggable from "vuedraggable";
import PipeLineStageDeleteModal from "./PipeLineStageDeleteModal";
import stage from "../../../../Store/modules/Stage/stage";
import { urlGenerator } from "@app/Helpers/helpers";
export default {
  name: "EditPipeline",
  components: { draggable, PipeLineStageDeleteModal },
  mixins: [FormMixin],
    props: ['selectedUrl'],
  data() {
    return {
      formData: {},
      stageList: [],
      errors: [],
      rowData: null,
      isModalActive: false,
      deleteStageUrl: "",
      loading: false,
    };
  },
  computed: {
    stageListForModal() {
      let currentStages = this.stageList.filter((v) => v.id != this.rowData);
      return currentStages.filter((v) => v.id);
    },
  },
  methods: {
    addNewStage() {
      this.stageList.push({
        name: "",
        probability: 100,
      });
    },
    beforeSubmit() {
      this.loading = true;
    },
    saveData() {
      let arr = this.stageList.map((stage) => stage.name);
      if (new Set(arr).size == arr.length) {
        const pipeline = {
          ...this.formData,
          stage: this.stageList.map((data, index) => {
            return {
              id: data.id,
              name: data.name,
              probability: data.probability,
              priority: index,
            };
          }),
        };
        this.save(pipeline);
      } else {
        this.loading = false;
        this.$toastr.i(this.$t("distinct", [(attribute) => "Name"]), "Duplicate");
      }
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
      // this.$toastr.e(response.data.message);
    },
    afterSuccess(response) {
        this.loading = false;
      this.$toastr.s(response.data.message);
      window.location.replace(
          route("deals_pipeline.page", {pipeline: response.data.data.id}, true)
      );
    },
    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
      this.stageList = response.data.stage;
    },
    deleteStage(id) {
      this.isModalActive = true;
      this.deleteStageUrl = route('stages.destroy', {id: id});
      this.rowData = id;
    },

    domDeleteStage(index, stageId) {
      this.stageList.splice(index, 1);
      if (stageId) {
        this.axiosDelete(route('stages.destroy', {id: stageId})).then((response) => {
          this.$toastr.s(response.data.message);
        });
      }
    },
    closeModal() {
      this.isModalActive = false;
      this.rowData = null;
      $("#delete-pipeline-stage").modal("hide");
    },
  },
};
</script>
