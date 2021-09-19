<template>
    <app-modal modal-alignment="top" modal-id="send-invitation-modal" modal-size="default" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('send_invitation') }}</h5>
            <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <app-overlay-loader v-if="dataLoaded"/>
            <div class="form-group row" v-else>
                <template v-if="personEmail.length">
                    <div class="mb-0 col-sm-3 d-flex align-items-center">
                        <label>{{ $t('email') }}</label>
                    </div>
                    <div class="col-sm-9">
                        <app-input type="select"
                                   v-model="formData.email"
                                   :placeholder="$t('choice_an_email')"
                                   :list="personEmail"
                                   :required="true"/>
                    </div>
                </template>
                <template v-else>
                    <div class="col-sm-12">
                        <app-note :notes="$t('this_lead_has_no_email')"
                                  :show-title="false" padding-class="p-2"
                                  title="'send'"/>
                    </div>
                </template>
            </div>
        </template>
        <template slot="footer">
            <button class="btn btn-secondary mr-2" data-dismiss="modal" type="button" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
            <button class="btn btn-primary" :disabled="!personEmail.length ? true : false" type="button"
                    @click.prevent="submitData">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
                <template v-if="!loading">{{ $t('send') }}</template>
            </button>
        </template>
    </app-modal>

</template>

<script>
import {FormSubmitMixin} from "../../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "SendInvitationModal",
    mixins: [FormSubmitMixin],
    data() {
        return {
            formData: {},
            personEmailList: {},
            personEmail: [],
            dataLoaded: false,
        }
    },
    methods: {
        afterError(response) {
            this.loading = false;
            this.$toastr.e(response.data.errors ? response.data.errors?.email[0] : response.data.message);
        },
        submitData() {
            this.submitFromFixin('post', route('person.invite_lead', {id: this.personEmailList.id}), this.formData);
        },
        beforeGetEditData() {
            this.dataLoaded = true;
        },
        afterSuccessFromGetEditData({data}) {
            this.personEmailList = data
            this.personEmail = data.email.map(item => {
                return {
                    id: item.value,
                    value: item.value
                }
            })
            this.dataLoaded = false;
        }
    }
}
</script>

