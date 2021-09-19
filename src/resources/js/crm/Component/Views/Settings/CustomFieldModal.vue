<template>
  <app-modal
    modal-alignment="top"
    modal-id="custom-field-modal"
    modal-size="default"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title">{{ selectedUrl ? $t('edit') : $t('add') }}</h5>
      <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>

    <template slot="body">
      <form ref="form" :data-url="selectedUrl ? selectedUrl : route('core.custom-fields.store')">
        <div class="form-group">
          <label>{{ $t('custom_field_type') }}</label>
          <app-input
            v-model="formData.custom_field_type_id"
            :list="customFieldTypeList"
            list-value-field="translated_name"
            type="select"
            @input="setCustomFieldType($event)"
          />
        </div>

        <div v-if="custom_field_type.choose" class="form-group">
          <label class="d-block">{{ $t('options') }}</label>
          <small class="text-muted">
            {{
              `${$t('use_comma_separated_values_as_options_of_the_field')}`
            }}
          </small>
          <app-input
            v-model="formData.meta"
            :placeholder="$t('custom_field_options')"
            type="textarea"
          />
        </div>

        <div class="form-group">
          <label>{{ $t('name') }}</label>
          <app-input
            v-model="formData.name"
            :placeholder="$t('enter_custom_field_name')"
            :required="true"
          />
          <span v-if="customErrorMessage" class="text-danger">{{ customErrorMessage }}</span>
          <span v-if="errors.name" class="text-danger">{{ errors.name[0] }}</span>
        </div>

        <div class="form-group">
          <label>{{ $t('context') }}</label>
          <app-input
            v-model="formData.context"
            :list="[{ id: 'person', value: $t('person') },
                        { id: 'organization', value: $t('organization') },
                        { id: 'deal', value: $t('deal') }]"
            type="select"
          />
        </div>

        <div class="form-group">
          <app-input
            v-model="formData.in_list"
            :list="[{ id: 1, value: $t('show_in_the_data_table') }]"
            type="checkbox"
          />
        </div>
      </form>
    </template>
    <template slot="footer">
      <button
        class="btn btn-secondary mr-2"
        data-dismiss="modal"
        type="button"
        @click.prevent="closeModal"
      >{{ $t('cancel') }}
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

import {FormMixin} from "../../../../core/mixins/form/FormMixin.js";
import {mapGetters} from "vuex";

export default {
  name: "CustomFieldModal",
  mixins: [FormMixin],
  props: {
    tableId: String,
  },
  data() {
    return {
        route,
      formData: {in_list: []},
      custom_field_type: {context: "", meta: ""},
      errors: [],
      loading: false,
      customErrorMessage: '',
    };
  },
  computed: {
    ...mapGetters(["customFieldTypeList"]),
  },
  methods: {
    beforeSubmit() {
      this.loading = true;
    },
    submitData() {
      if (this.formData.name.match(/^(select)/i)) {
        this.customErrorMessage = "Don't use select keyword";
      } else {
        this.formData.meta = this.sanitizeMetaData(this.formData.meta ? this.formData.meta : '');
        const customField = {
          ...this.formData,
          in_list: this.formData.in_list[0],
          type: this.collection(this.customFieldTypeList).find(
            this.formData.custom_field_type_id
          ).name,
        };
        if (customField.in_list == undefined) {
          customField.in_list = 0;
        }
        this.save(customField);
      }
    },
    afterSuccessFromGetEditData({data}) {
      this.formData = {
        ...data,
        in_list: data.in_list ? [parseInt(data.in_list)] : [],
      };
      this.custom_field_type.choose = ["select", "checkbox", "radio"].includes(
        data.custom_field_type.name
      );
    },

    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
    },

    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit("reload-" + this.tableId);
      this.closeModal();
    },

    afterFinalResponse() {
      this.loading = false;
    },
    sanitizeMetaData(value) {
      let meta = value.split(',').map((el) => {
        return el.trim();
      }).join();
      return meta;
    },
    setCustomFieldType(custom_field_type_id) {
      this.formData.meta = "";
      this.custom_field_type = this.collection(this.customFieldTypeList).find(
        custom_field_type_id
      );
      this.custom_field_type.choose = ["select", "checkbox", "radio"].includes(
        this.custom_field_type.name
      );
    },
    closeModal(value) {
      this.$emit("close-modal", value);
    },
  },

  mounted() {
    this.$store.dispatch("getCustomFieldType");
    this.formData.context = "person";
    this.formData.custom_field_type_id = 1;
  },
};
</script>


