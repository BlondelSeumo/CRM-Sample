<template>
  <div :id="modalId" aria-hidden="true" class="modal fade"
       role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body d-flex flex-column justify-content-center modal-alert">
          <div class="text-center">
            <app-icon :name="'check-circle'" class="text-success"/>
          </div>
          <h5 class="text-center font-weight-bold mt-4">{{ $t('congratulation') }}</h5>
          <p class="mb-primary text-center font-size-90 p-0">{{ $t('you_won_the_deal_successfully') }}</p>
          <div class="text-center">

            <a class="btn btn-success" href="#" @click.prevent="confirmDealWon">
                <span v-if="loading" class="w-100">
                   <app-submit-button-loader></app-submit-button-loader>
                </span>
                <template v-else>{{ $t('thank_you') }}</template>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
import {api} from "@app/Helpers/api";
import {collect} from "@app/Helpers/Collection";

export default {
  name: "DealWonConfirmModal",
  props: {
    modalId: {
      type: String,
      required: true
    },
    dealWonData: {
      type: Object,
      required: true,
    }
  },
  mixins: [FormMixin],
  data() {
    return {
      formData: {},
      api,
      collect,
      loading: false
    }
  },
  methods: {

    beforeSubmit() {
      this.loading = true;
    },
    confirmDealWon() {
      this.formData.title = this.dealWonData.title;
      this.formData.pipeline_id = this.dealWonData.pipeline_id;
      this.formData.stage_id = this.dealWonData.stage_id;

        this.axiosGet(
            route(`statuses.index`, {_query: { name: "status_won", type: "deal"}}))
        .then((res) => {
          this.formData.status_id = collect(res.data).first().id;
          this.submitFromFixin('put', route('deals.update', {id: this.dealWonData.id}), this.formData)
        });
    },
    afterSuccess(response) {
      this.$hub.$emit("tag-list");
      this.$emit("success");
      this.$toastr.s(response.data.message);
    },
    afterFinalResponse() {
      this.closed();
      this.loading = false;
    },
    closed() {
      this.$emit('won-close-modal', false);
    },
  },
  mounted() {
    $('#' + this.modalId).on('hidden.bs.modal', (e) => {
      this.closed();
    });

    $('#' + this.modalId).modal('show');
  },
}
</script>
