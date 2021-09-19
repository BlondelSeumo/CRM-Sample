<template>
    <app-modal modal-alignment="top" modal-id="move-deal-modal" modal-size="default" @close-modal="closeModal">
        <template slot="header">
            <h6 class="modal-title">{{ $t('move_all_deals') }}</h6>
            <button aria-label="Close" class="close outline-none"
                    data-dismiss="modal" type="button" @click.prevent="closeModal">
                <span>
                    <app-icon name="x"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <form ref="form" :data-url="route('multiple_deals.move')">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <h6>{{ $t('move_all_deals_from') }}
                                <span class="text-primary">{{ pipelineName }}</span>
                                {{ $t('pipeline_to_another') }}
                                <app-icon name="corner-right-down"></app-icon>
                            </h6>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="mb-0 col-sm-3 d-flex align-items-center">
                            {{ $t("target_pipeline") }}
                        </label>
                        <div class="col-sm-9">
                            <app-input
                                v-model="formData.pipeline_id"
                                :list="getAllPipelines"
                                :placeholder="$t('choose_a_pipeline')"
                                list-value-field="name"
                                type="select"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="mb-0 col-sm-3 d-flex align-items-center">
                            {{ $t("stage") }}
                        </label>
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

            </form>
        </template>
        <template slot="footer">
            <button class="btn btn-secondary mr-2" data-dismiss="modal" type="button"
                    @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
            <button class="btn btn-primary" type="button" @click.prevent="submit">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
                <template v-if="!loading">{{ $t("move") }}</template>
            </button>
        </template>

    </app-modal>
</template>

<script>
import {collect} from "../../../Helpers/Collection";
import {FormMixin} from "../../../../core/mixins/form/FormMixin";
import {FormSubmitMixin} from "../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "moveAllDealModal",
    mixins: [FormMixin, FormSubmitMixin],
    props: {
        pipelineName: {
            type: String
        },
        pipelines: {},
        pipelineId: {},
        stages: {},
        deals: {}
    },
    data() {
        return {
            route,
            formData: {
                deals: [],
                pipeline_id: null,
            },
            pipelineList: this.pipelines,
            pipelineChange: true,
            stageList: [],
            dataLoaded: false,
        }
    },
    computed: {
        getAllPipelines() {
            return this.pipelineList.filter(pipeline => pipeline.id != this.pipelineId)
        },
        stageListAsPipelineId() {
            this.loadStepInput();
            return this.stageList.filter((v) => v.pipeline_id == this.formData.pipeline_id);
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
        getStages() {
            return this.stageList = collect(this.stages)
                .where("pipeline_id", this.formData.pipeline_id)
                .sortBy("priority")
                .get();
        },
    },
    watch: {
        "getAllPipelines.length": {
            handler: function (length) {
                this.formData.pipeline_id = this.getAllPipelines.map(item => item.id)[0];
            },
            immediate: true,
        },
        "getStages.length": {
            handler: function (length) {
                this.formData.stage_id = this.stages.find(item => item.pipeline_id);
            },
            immediate: true,
        },
        "deals.length": {
            handler: function (length) {
                let dealsArray = Object.values(this.deals)
                    .filter(item => item.length > 0)
                    .reduce((total, deal) => total.concat(deal), [])
                    .map(i => i.id);
                this.formData.deals = dealsArray;

            },
            immediate: true,
        },
    },
    methods: {
        submit() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$emit('refresh');
            this.closeModal();
        },

        loadStepInput() {
            this.pipelineChange = false;
            this.$nextTick(() => {
                this.pipelineChange = true;
            });
        },

        setStageId(index) {
            if (this.stageListAsPipelineId.length) {
                this.formData.stage_id = this.stageListAsPipelineId[index].id;
            }
        }
    },

}
</script>

