<template>
    <app-modal modal-id="user-modal" modal-size="default" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title" v-if="selectedUrl">{{ $t('edit_user') }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form ref="form" :class="{'loading-opacity': preloader}" :data-url="selectedUrl">
                <div class="form-group">
                    <label>{{ $t('first_name') }}</label>
                    <app-input
                        v-model="formData.first_name"
                        :required="true"/>
                </div>

                <div class="form-group">
                    <label>{{ $t('last_name') }}</label>
                    <app-input
                        v-model="formData.last_name"
                        :required="true"/>
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
import {FormSubmitMixin} from "../../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "UserModal",
    mixins: [FormSubmitMixin],
    data() {
        return {
            route,
            formData: {},
            preloader: false,
        }
    },
    methods: {
        submitData() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId)
            this.$hub.$emit('reload-role-modal');
            this.closeModal();
        },
        beforeGetEditData() {
            this.preloader = true;
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.preloader = false;
        }
    },


}
</script>

