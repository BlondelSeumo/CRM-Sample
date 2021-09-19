<template>
  <app-modal
      modal-id="modalId"
      modal-size="default"
      modal-alignment="top"
      @close-modal="closeModal">
    <template slot="header">
      <h5 class="modal-title">{{ $t('delete_stage') }}</h5>
      <button type="button" class="close outline-none"
              data-dismiss="modal" aria-label="Close" @click.prevent="closeModal">
                <span>
                    <app-icon name="x"></app-icon>
                </span>
      </button>
    </template>
    <template slot="body">
      <form ref="form">
        <div class="form-group">
          <div class="row">
            <div class="mb-2 col-sm-12">
              <h5 style="color: #ff0000;">{{ $t('opps!') }}</h5>
            </div>
            <div class="col-sm-12">
              <h5 v-if="stageList.length > 0">{{ $t('what_about_the_deals_of_this_stage') }}?</h5>
              <h5 v-else>{{ $t('deals_on_this_stage_will_be_lost_forever') }}</h5>
            </div>
          </div>
        </div>

        <div class="form-group" v-if="stageList.length > 0">
          <div class="row">
            <div class="col-sm-12">
              <app-input
                  type="radio"
                  :list="[{id:1, value: 'Want to move these deals to another stage?'}]"
                  v-model="formData.delete_option"
              />
            </div>
          </div>
        </div>

        <template v-if="stageList.length > 0">
        <div class="form-group" v-if="formData.delete_option == 1">
          <div class="row">
            <div class="col-sm-12">
              <app-input
                  type="select"
                  list-value-field='name'
                  :list="stageList"
                  v-model="formData.stage_id"/>
            </div>
          </div>
        </div>
        </template>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-12">
              <app-input
                  type="radio"
                  :list="[{id:0, value: 'Or delete anyway'}]"
                  v-model="formData.delete_option"
              />
            </div>
          </div>
        </div>

      </form>
    </template>
    <template slot="footer">
      <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
        {{ $t('cancel') }}
      </button>
      <button type="button" class="btn btn-danger" data-dismiss="modal" @click.prevent="deleteStage">
        {{ $t('delete') }}
      </button>
    </template>

  </app-modal>
</template>
<script>

import {FormMixin} from "@core/mixins/form/FormMixin";

export default {
  name: "PipelineStageDeleteModal",
  mixins: [FormMixin],
  props: {
    option: {
      type: Array,
      required: true
    },
    deleteStageUrl: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      formData: {},
      url: '',
      currentStage: {}
    }
  },
  computed: {
    stageList() {
      const stageInfo = this.option;
      const check = stageInfo.find((item) => {
        return item.id == ''
      });
      if (check == undefined) {
        stageInfo.unshift({
          id: "",
          disabled: true,
          name: this.$t("choose_another_stage"),
        });
      }
      return stageInfo;
    }
  },
  methods: {
    deleteStage(value) {
      if (this.formData.delete_option == 1) {
        this.url = this.deleteStageUrl + '?move_to=' + this.formData.stage_id;
        this.axiosDelete(this.url)
            .then(response => {
              this.$toastr.s(response.data.message);
             setTimeout(() => {
               location.reload();
             }, 1000)
            }).catch(({error}) => {
          this.closeModal(value);
        }).finally(() => {
          this.closeModal(value);
        });
      } else if (this.formData.delete_option == 0) {
        this.url = this.deleteStageUrl + '?delete_anyway';
        this.axiosDelete(this.url)
            .then(response => {
              this.$toastr.s(response.data.message);
              setTimeout(() => {
                location.reload();
              }, 1000)
            }).catch(({error}) => {
          this.closeModal(value);
        }).finally(() => {
          this.closeModal(value);
        });
      } else {
        this.closeModal(value);
      }
    },
    closeModal(value) {
      this.$emit('close-modal', value);
    },
  },



}
</script>
