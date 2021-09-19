<template>
    <app-modal class="reason-modal-size" modal-id="lost-reason-modal" modal-size="default" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ selectedUrl ? $t('edit') : $t('add') }} {{$t('lost_reason_lowercase')}}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <form ref="form" :data-url="selectedUrl ? selectedUrl : route('lost-reasons.store')">
                <div class="form-group">
                    <div class="row">
                        <div class="mb-0 col-sm-3 d-flex align-items-center">
                            <label>{{ $t('name') }}</label>
                        </div>
                        <div class="col-sm-9">
                            <app-input type="text"
                                       :placeholder="$t('enter_name')"
                                       name="lost_reason"
                                       :required="true"
                                       v-model="formData.lost_reason"
                                       :error-message="$errorMessage(errors, 'lost_reason')"
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
            <button type="button" class="btn btn-primary" @click.prevent="submitData">
                        <span class="w-100">
                            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
                        </span>
                <template v-if="!loading">{{ $t('save') }}</template>
            </button>
        </template>

    </app-modal>
</template>

<script>

import {FormSubmitMixin} from "@app/Mixins/Global/FormSubmitMixin";

export default {
    name: "LostReasonModal",
    mixins: [FormSubmitMixin],

    data() {
        return {
            route,
            formData: {},
        }
    },

    methods: {
        submitData() {
            this.save(this.formData);
        },
        afterSuccessFromGetEditData(response){
          this.formData = response.data;
        },
    }
}
</script>

<style>
@media (min-width: 576px) {
  .reason-modal-size .modal-dialog .modal-content {
    min-height: 285px !important;
  }
}
</style>
