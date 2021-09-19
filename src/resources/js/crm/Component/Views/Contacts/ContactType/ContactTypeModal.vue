<template>
    <app-modal modal-alignment="top" modal-id="contact-type-modal" modal-size="default" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ selectedUrl ? $t('edit') : $t('add') }} {{ $t('lead_group_lowercase') }}</h5>
            <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <form ref="form" :data-url="selectedUrl ? selectedUrl : route('types.store')">
                <div class="form-group row">
                    <div class="mb-0 col-sm-3 d-flex align-items-center">
                        <label>{{ $t('name') }}</label>
                    </div>
                    <div class="col-sm-9">
                        <app-input v-model="formData.name"
                                   :error-message="$errorMessage(errors, 'name')"
                                   :placeholder="$t('enter_name')"
                                   :required="true"
                                   name="lost_reason"
                                   type="text"/>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="mb-0 col-sm-3 d-flex align-items-center">
                        <label>{{ $t('class') }}</label>
                    </div>
                    <div class="col-sm-6">
                        <app-input v-model="formData.class"
                                   :list="classList"
                                   type="select"/>
                    </div>
                    <div class="mb-0 col-sm-3 d-flex align-items-center">
                        <button :class="'float-right mt-1 btn btn-'+formData.class" type="button">{{ formData.class }}
                        </button>
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
                <template v-if="!loading">{{ $t('save') }}</template>
            </button>
        </template>
    </app-modal>
</template>
<script>

import {FormSubmitMixin} from "../../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "ContactTypeModal",
    mixins: [FormSubmitMixin],
    data() {
        return {
            route,
            formData: {class: 'primary'},
            classList: [
                {id: 'primary', value: 'primary'},
                {id: 'success', value: 'success'},
                {id: 'secondary', value: 'secondary'},
                {id: 'danger', value: 'danger'},
                {id: 'purple', value: 'purple'},
                {id: 'warning', value: 'warning'},
                {id: 'info', value: 'info'},
                {id: 'light', value: 'light'},
                {id: 'dark', value: 'dark'},
                {id: 'link', value: 'link'},

            ]
        }
    },
    methods: {
        submitData() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit('reload-' + this.tableId);
            this.$store.dispatch('contentType')
            this.closeModal();
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
        },
    },
}
</script>

<style>
.btn-purple {
    color: #ffffff;
    background-color: #964ed8;
}

.btn.btn-purple:hover {
    color: #ffffff;
    background-color: #964EED;
}
</style>
