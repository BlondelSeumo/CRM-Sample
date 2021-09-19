<template>
  <app-modal modal-alignment="top" modal-id="lost-deal-confirm-modal" modal-size="default" @close-modal="closeModal">
    <template slot="header">
      <h5 class="modal-title">{{ dealLostReasonData ? $t('deal_lost') : '' }}</h5>
      <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
      </button>
    </template>
    <template slot="body">
      <form ref="form">
        <div class="form-group">
          <div class="row">
            <div class="col-sm-3">
              <label>{{ $t('lost_reason') }}</label>
            </div>
            <div class="col-sm-9">
              <app-input v-model="formData.lost_reason_id"
                         :list="reasonsList"
                         :required="true"
                         list-value-field='lost_reason'
                         type="select"/>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <div class="col-sm-3">
              <label>{{ $t('comment') }}</label>
            </div>
            <div class="col-sm-9">
              <app-input v-model="formData.comment"
                         name="comment"
                         type="textarea"
              />
            </div>
          </div>
        </div>
      </form>
    </template>
    <template slot="footer">
      <button class="btn btn-secondary mr-2" data-dismiss="modal" type="button" @click.prevent="closeModal">
        {{ $t('cancel') }}
      </button>
      <button class="btn btn-primary" type="button" @click.prevent="submitData">
                                <span class="w-100">
                                    <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                                </span>
        <template v-if="!loading">{{ $t('confirm') }}</template>
      </button>
    </template>

  </app-modal>
</template>

<script>


import {FormMixin} from "@core/mixins/form/FormMixin";
import {api} from "@app/Helpers/api";
import {collect} from "@app/Helpers/Collection";

export default {
  name: "DealLoastConfirmModal",
  props: {
    dealLostReasonData: {
      type: Object,
      required: true
    }
  },
  mixins: [FormMixin],
  data() {
    return {
      loading: false,
      formData: {lost_reason_id: 1},
      reasonsList: [],
    }
  },
  methods: {
    lostReasonList() {
      this.axiosGet(route('lost-reasons.index')).then((response) => {
        this.reasonsList = response.data.data;
      })
    },
    beforeSubmit() {
      this.loading = true;
    },
    submitData() {
      let dealData = this.formData;
      dealData.title = this.dealLostReasonData.title;
      dealData.pipeline_id = this.dealLostReasonData.pipeline_id;
      dealData.stage_id = this.dealLostReasonData.stage_id;

        this.axiosGet(
            route(`statuses.index`, {_query: { name: "status_lost", type: "deal"}}))
        .then((res) => {
          dealData.status_id = collect(res.data).first().id;
          this.submitFromFixin('patch', route('deals.update', {id: this.dealLostReasonData.id}), dealData);
        });
    },
    afterError(response) {
      this.loading = false;
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit('tag-list');
      this.$emit('success');
      this.loading = false;
      this.closeModal();
    },
    closeModal(value) {
      this.$emit('close-modal', value);
    },
  },
  mounted() {
    this.lostReasonList();
  },
}
</script>
