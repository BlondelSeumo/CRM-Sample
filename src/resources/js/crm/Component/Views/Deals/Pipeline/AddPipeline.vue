<template>
  <div class="content-wrapper">
    <form ref="form" :data-url="route('pipelines.store')">
      <div class="row">
        <div class="col-12 col-sm-6">
          <app-breadcrumb
            :page-title="$t('add_pipeline')"
            :directory="[$t('deals'), $t('add_pipeline')]"
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
          <!--                                    <div class="form-group mb-primary px-2">-->
          <!--                                        <div class="row">-->
          <!--                                            <label class="m-0 d-flex align-items-center col col-sm-2">-->
          <!--                                                {{ $t('deal_probability') }}-->
          <!--                                            </label>-->
          <!--                                            <div class="col col-sm-6">-->
          <!--                                                <div class="d-flex align-items-center">-->
          <!--                                                    <label class="custom-control border-switch mb-0 mr-3">-->
          <!--                                                        <input type="checkbox" class="border-switch-control-input">-->
          <!--                                                        <span class="border-switch-control-indicator"></span>-->
          <!--                                                    </label>-->
          <!--                                                    <span class="font-size-80 text-muted">-->
          <!--                                                        {{ $t('deal_probability_description') }}-->
          <!--                                                    </span>-->
          <!--                                                </div>-->
          <!--                                            </div>-->
          <!--                                        </div>-->
          <!--                                    </div>-->
          <div class="d-flex custom-scrollbar overflow-auto">
            <draggable class="kanban-wrapper pr-0" :list="defaultStageList" group="deals">
              <div
                class="kanban-column rounded"
                v-for="(stage, index) in defaultStageList"
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
                <div class="px-2 mb-2">
                  <div class="card card-with-shadow default-box-shadow border-0">
                    <div class="card-body p-3">
                      <div class="form-group">
                        <label>
                          {{ $t("name") }}
                        </label>
                        <app-input
                          type="text"
                          :placeholder="$t('type_pipeline_name')"
                          v-model="stage.name"
                          @keydown.enter.prevent=""
                          :id="'stage-name-' + stage.id"
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
                        class="btn btn-stage-action shadow mt-3"
                        @click.prevent="deleteStage(index)"
                      >
                        <app-icon name="trash" class="mr-1" />
                        {{ $t("delete_stage") }}
                      </button>
                    </div>
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
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin";
import draggable from "vuedraggable";
import { urlGenerator } from "@app/Helpers/helpers";

export default {
  name: "AddPipeline",
  components: { draggable },
  mixins: [FormMixin],
  data() {
    return {
        route,
      formData: {},
      errors: [],
      loading: false,
    };
  },
  computed: {
    defaultStageList() {
      return this.$store.getters.getDefaultStage;
    },
  },
  methods: {
    addNewStage() {
      this.defaultStageList.push({
        name: "",
        probability: 100,
      });
    },
    beforeSubmit() {
      this.loading = true;
    },
    saveData() {
      const pipeline = {
        ...this.formData,
        stage: this.defaultStageList.map((data, index) => {
          return {
            name: data.name,
            probability: data.probability,
            priority: index,
          };
        }),
      };
      this.save(pipeline);
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
    },

    afterSuccess(response) {
        this.loading = false;
      this.$toastr.s(response.data.message);
      window.location.replace(
          route("deals_pipeline.page", {pipeline: response.data.data.id}, true)
      );
    },
    deleteStage(index) {
      this.defaultStageList.splice(index, 1);
    },
  },
  mounted() {
    this.$store.dispatch("getDefaultStage");
  },
};
</script>
