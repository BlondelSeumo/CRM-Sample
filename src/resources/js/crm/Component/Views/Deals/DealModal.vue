<template>
  <app-modal
    modal-alignment="top"
    modal-id="deal-modal"
    modal-size="default"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h5 v-if="selectedUrl" class="modal-title">{{ $t("edit_deal") }}</h5>
      <h5 v-else class="modal-title">{{ $t("add_deal") }}</h5>
      <button
        aria-label="Close"
        class="close outline-none"
        @click.prevent="closeModal"
        type="button"
      >
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <form
          v-if="dataLoaded"
          ref="form"
          :data-url="selectedUrl ? selectedUrl : route('deals.store')"
      >
          <div class="form-group row">
              <label class="mb-0 col-sm-3 d-flex align-items-center">
                  {{ $t("title") }}
              </label>
              <div class="col-sm-9">
                  <app-input
                      v-model="formData.title"
                      :placeholder="$t('enter_deal_title')"
                      type="text"
                      :error-message="$errorMessage(errors, 'title')"
                  />
              </div>
          </div>

          <div class="form-group row">
              <label class="mb-0 col-sm-3 d-flex align-items-center">
                  {{ $t("description") }}
              </label>
              <div class="col-sm-9">
                  <app-input
                      type="textarea"
                      v-model="formData.description"
                      :placeholder="$t('enter_description')"
                  />
              </div>
          </div>

          <template v-if="componentType =='deal'">
              <div class="form-group row">
                  <label class="mb-0 col-sm-3 d-flex align-items-center">
                      {{ $t("lead_type") }}</label>
                  <div class="col-sm-9">
                      <app-input
                          v-model="formData.lead_type"
                          :list="[{id:1, value: 'Person'}, {id:2, value: 'Organization'}]"
                          type="radio"
                          @change="changeLeadType"
                          :error-message="$errorMessage(errors, 'lead_type')"/>
                  </div>
              </div>
          </template>

          <template v-if="formData.lead_type == 1 && componentType == 'deal'">
              <div class="form-group row">
                  <label class="mb-0 col-sm-3 d-flex align-items-center">
                      {{ $t("person") }}</label>
                  <div class="col-sm-9">
                      <app-input
                          v-model="formData.contextable_id"
                          :list="personList"
                          :placeholder="$t('choose_a_contact_person')"
                          list-value-field="name"
                          type="search-select"
                          :error-message="$errorMessage(errors, 'contextable_id')"
                      />
                  </div>
          </div>
        </template>

        <template v-else>
          <template v-if="componentType != 'person'">
          <template v-if="componentType != 'org'">
          <div class="form-group row">
              <label class="mb-0 col-sm-3 d-flex align-items-center">
                  {{ $t("organization") }}</label>
              <div class="col-sm-9">
                <app-input
                  v-model="formData.contextable_id"
                  :list="organizationList"
                  :placeholder="$t('choose_an_organization')"
                  list-value-field="name"
                  type="search-select"
                  :error-message="$errorMessage(errors, 'contextable_id')"
                />
              </div>
          </div>
          </template>
          <div class="form-group row">
              <label class="mb-0 col-sm-3 d-flex align-items-center">
                  {{ $t("contact_person") }}
              </label>
              <div class="col-sm-9">
                <app-input
                  v-model="formData.person_id"
                  :list="personListAsOrg"
                  :placeholder="$t('choose_a_contact_person')"
                  list-value-field="name"
                  type="search-select"
                />
                  <small class="text-danger col-sm-9 pl-0 mb-0"
                         v-if="formData.contextable_id && personListAsOrg.length < 1">
                      {{ $t('organization_has_no_contact_person') }}
                  </small>
              </div>

          </div>
          </template>
        </template>

        <div class="form-group row">
            <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                $t("deal_value")
              }}</label>
            <div class="col-sm-9">
              <app-input
                v-model="formData.value"
                :placeholder="$t('enter_deal_value')"
                type="number"
              />
            </div>
        </div>
        <div class="form-group row">
            <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                $t("pipeline")
              }}</label>
            <div class="col-sm-9">
              <app-input
                v-model="formData.pipeline_id"
                :list="pipelineList"
                :required="true"
                type="select"
                :error-message="$errorMessage(errors, 'pipeline_id')"
              />
          </div>
        </div>
        <div class="form-group row">
            <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                $t("stages")
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
                :error-message="$errorMessage(errors, 'stage_id')"
              />
            </div>
        </div>
        <div class="form-group row">
            <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                $t("expecting_closing_date")
              }}</label>
            <div class="col-sm-9">
              <app-input
                v-model="formData.expired_at"
                :placeholder="$t('choose_a_date')"
                :popover-position="'top-start'"
                type="date"
                :error-message="$errorMessage(errors, 'expired_at')"
              />
            </div>
        </div>
        <div class="form-group row"  v-if="$can('manage_public_access')">
            <label class="mb-0 col-sm-3 d-flex align-items-center">{{
                $t("owner")
              }}</label>
            <div class="col-sm-9">
              <app-input
                v-model="formData.owner_id"
                :list="ownerList"
                list-value-field="full_name"
                type="select"
              />
            </div>
        </div>
        <template v-if="customFields.length && customFieldDataLoaded">
        <div v-for="(field, index) in customFields" :key="index"
             class="form-group row">
            <div class="col-md-3">
              <label>{{ field.name }}</label>
            </div>
            <div class="col-md-9">
              <template v-if="field.custom_field_type.name === 'text'">
                <app-input
                  :id="field.name"
                  v-model="customFieldValue[field.name]"
                  :list="generateInputList(field)"
                  type="text"
                />
              </template>
              <template v-if="field.custom_field_type.name === 'textarea'">
                <app-input
                  :id="field.name"
                  v-model="customFieldValue[field.name]"
                  :list="generateInputList(field)"
                  type="textarea"
                />
              </template>
              <template v-if="field.custom_field_type.name === 'radio'">
                <app-input
                  v-model="customFieldValue[field.name]"
                  :list="generateInputList(field)"
                  :radio-checkbox-name="field.name"
                  type="radio"
                />
              </template>
              <template v-if="field.custom_field_type.name === 'checkbox'">
                <app-input
                  v-model="customFieldValue[field.name]"
                  :list="generateInputList(field)"
                  :radio-checkbox-name="field.name"
                  type="checkbox"
                />
              </template>
              <template v-if="field.custom_field_type.name === 'select'">
                <app-input
                  v-model="customFieldValue[field.name]"
                  :list="generateInputList(field)"
                  type="select"
                />
              </template>
              <template v-if="field.custom_field_type.name === 'number'">
                <app-input
                  v-model="customFieldValue[field.name]"
                  type="number"
                />
              </template>
              <template v-if="field.custom_field_type.name === 'date'">
                <app-input v-model="customFieldValue[field.name]" type="date"/>
              </template>
            </div>
        </div>
        </template>
      </form>
      <app-overlay-loader v-else/>
    </template>
    <template slot="footer">
      <button
        class="btn btn-secondary mr-2"
        data-dismiss="modal"
        type="button"
        @click.prevent="closeModal"
      >
        {{ $t("cancel") }}
      </button>
      <button class="btn btn-primary" type="button" @click.prevent="submit">
        <span v-if="loading" class="w-100">
          <app-submit-button-loader></app-submit-button-loader>
        </span>
        <template v-else>{{ $t("save") }}</template>
      </button>
    </template>
  </app-modal>
</template>
<script>

import {FormMixin} from "@core/mixins/form/FormMixin";
import {api} from "@app/Helpers/api";
import {collect} from "@app/Helpers/Collection";
import {mapGetters} from "vuex";
import {getAllCustomFields} from "@app/Mixins/Global/CustomFieldMixin";

export default {

  name: "DealModal",
  mixins: [FormMixin, getAllCustomFields],
  props: {
    preSelectedOption: Object,
    selectedUrl: String,
    tableId: String,
    pipelineId: Number,
    selectedStageIndex: Number,
    componentType: {
      type: String,
      default: 'deal'
    },
    selectedUrlId:{
      type:Number,
      default: null
    }
  },
  data() {
    return {
        route,
      formData: {
        owner_id: user.id,
        pipeline_id: this.pipelineId,
        lead_type: this.componentType == 'org' ? 2 : 1 ,
        contextable_id: this.selectedUrlId ?? null
      },
      addEditData: {},
      contactTypeList: [],
      pipelineList: [],
      stageList: [],
      stageId: {},
      errors: [],
      dataLoaded: false,
      loading: false,
      pipelineChange: true,
      customFieldValue:[],
    };
  },
  computed: {
    personListAsOrg() {
      return this.personList.filter((item) =>
        item.organizations.find(el =>
          el.id == this.formData.contextable_id
        )
      );
    },
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

    ...mapGetters({
      ownerList: "getOwner",
      organizationList: "getOrganization",
      personList: "getPerson",
      dealList: "getDeal",
    }),
  },
  created() {
    //preSelectedOption are set in here
    if (this.preSelectedOption) {
      if (this.preSelectedOption.organization_id) {
        this.formData.organization_id = this.preSelectedOption.organization_id;
      }
      if (this.preSelectedOption.person_id) {
        this.formData.person_id = this.preSelectedOption.person_id;
      }
    }
    //end of preSelectedOption set
    this.getPipeline();
    this.getStages();
      if (!this.selectedUrl){
          this.getAllCustomFields("deal");
      }
    this.$nextTick(() => {
      if (this.$props.selectedStageIndex != undefined) {
        this.formData.stage_id = this.$props.selectedStageIndex;
      }
    });
  },
  methods: {
    loadStepInput() {
      this.pipelineChange = false;
      this.$nextTick(() => {
        this.pipelineChange = true;
      });
    },
    beforeSubmit() {
      this.loading = true;
    },
    submit() {
      let customData = [];
      this.customFields.map((el) => {
          let item = {
              value:
                  el.custom_field_type.name == "checkbox"
                      ? el.meta.split(",").filter((e, i) => {
                          if (
                              this.customFieldValue[el.name].includes(String(i)) ||
                              this.customFieldValue[el.name].includes(i)
                          ) {
                              return e;
                          }
                      })
                      : (el.custom_field_type.name == "select" ||
                      el.custom_field_type.name == "radio")
                      ? el.meta.split(",").find((e, i) => {
                          return i == Number(this.customFieldValue[el.name]);
                      })
                      : this.customFieldValue[el.name],
              custom_field_id: el.id,
          };
          customData.push(item);
      });
      this.addEditData.person_id = this.formData.person_id;
      if (this.formData.lead_type == 1) {
        this.addEditData.person_id = this.formData.contextable_id;
      }
      this.addEditData.customs = customData;
      this.addEditData.title = this.formData.title;
      this.addEditData.description = this.formData.description;
      this.addEditData.lead_type = this.formData.lead_type;
      this.addEditData.contextable_id = this.formData.contextable_id;
      this.addEditData.value = this.formData.value;
      this.addEditData.pipeline_id = this.formData.pipeline_id;
      this.addEditData.stage_id = this.formData.stage_id;
      this.addEditData.owner_id = this.formData.owner_id;

      this.addEditData.expired_at = this.formData.expired_at ? moment(this.formData.expired_at).format(
        "YYYY-MM-DD HH:mm:ss"
      ) : null;

        this.axiosGet(
            route(`statuses.index`, {_query: { name: "status_open", type: "deal"}}))
            .then((response) => {
          this.addEditData.status_id =
              this.formData.status_id ?? collect(response.data).first().id;
          this.save(this.addEditData);
        });
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
    },
    afterSuccess(response) {
      this.$store.dispatch("getDeal");
      this.$toastr.s(response.data.message);
      this.$hub.$emit("reload-" + this.tableId);
      this.$emit("saved");
      this.$hub.$emit("deal-update-list");
      this.closeModal();
    },
    afterFinalResponse() {
      this.loading = false;
    },
    afterSuccessFromGetEditData(response) {
       this.formData = response.data;
        this.getAllCustomFields("deal");
      // this.formData.person_id = this.formData.contact_person[0].id;
        this.formData = {
            person_id: response.data.contact_person[0].id
        }
        this.formData = {
            ...this.formData,
            ...response.data
        };
      let expired_at = response.data.expired_at.split(" ");
      this.formData.expired_at = new Date(expired_at);
    },
    closeModal(value) {
      this.$emit("close-modal", value);
    },
    getPipeline() {
      this.axiosGet(route('pipelines.index'))
        .then((response) => {
          this.pipelineList = this.collection(response.data.data).shaper(
            "name"
          );
          if (!this.formData.pipeline_id) {
            this.formData.pipeline_id = response.data.data[0].id;
          }
        })
        .catch(({error}) => {
        });
    },
    getStages() {
      this.axiosGet(route('stages.index',{_query:{all:true}}))
        .then((response) => {
          this.stageList = collect(response.data).where('pipeline_id', this.formData.pipeline_id).sortBy('priority').get();
          this.stageId = this.stageList.find(
            (v) => v.pipeline_id == this.formData.pipeline_id
          );

          //this.dataLoaded = true;
        })
        .catch(({error}) => {
        });
    },
    setStageId(index) {
      if (this.stageListAsPipelineId.length) {
        this.formData.stage_id = this.stageListAsPipelineId[index].id;
      }
    },
    changeLeadType(){
      this.formData.contextable_id = null;
    }
  }
};
</script>
