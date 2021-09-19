<template>
    <div class="card border-0 card-with-shadow mb-primary">
        <div
            class="card-header bg-transparent p-primary d-flex justify-content-between align-items-center"
        >
            <h5 v-show="isCustomFieldEditable" class="card-title text-muted mb-0">
                {{ $t("custom_fields") }}
            </h5>
            <h5 v-show="!isCustomFieldEditable" class="card-title text-muted mb-0">
                {{ $t("edit_custom_fields") }}
            </h5>
            <div>
                <a
                    v-show="!isCustomFieldEditable"
                    class="text-muted"
                    href="#"
                    @click.prevent="showCustomFieldHide"
                >
                    <app-icon name="x-square" stroke-width="1"/>
                </a>
                <a
                    v-show="!isCustomFieldEditable"
                    class="text-muted"
                    href="#"
                    @click.prevent="showCustomFieldSave"
                >
                    <app-icon name="check-square" stroke-width="1"/>
                </a>

                <a
                    v-show="checkIsEditable"
                    class="text-muted"
                    href="#"
                    @click.prevent="showCustomFieldEditable"
                >
                    <app-icon name="edit" stroke-width="1"/>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div v-show="isCustomFieldEditable">

                <template v-if="customFields.length">
                    <div v-for="(field, index) in customFields" :key="index"
                         class="form-group row" v-if="loadData">
                        <label class="mb-0 text-muted col-4 d-flex align-items-center">
                            {{ field.name }}
                        </label>
                        <div class="col-8">
                            <template v-if="field.custom_field_type.name === 'number'">
                                {{ numberFormatter(customFieldData[field.name]) }}
                            </template>
                            <template v-else-if="field.custom_field_type.name === 'date'">
                                {{ customFieldData[field.name] == "-" ? customFieldData[field.name] : formatDateToLocal(customFieldData[field.name], false, null) }}
                            </template>
                            <template v-else>
                                {{ customFieldData[field.name] }}
                            </template>
                        </div>
                    </div>
                    <app-overlay-loader v-else/>
                </template>

                <template v-else>
                    <span class="text-muted">{{ $t("no_custom_field_added") }}</span>
                </template>

            </div>

            <div v-show="!isCustomFieldEditable">
                <template v-if="customFields.length">
                    <div v-for="(field, index) in customFields" :key="index"
                         class="form-group row">
                        <label class="col-4 mb-0 d-flex align-items-center">
                            {{ field.name }}
                        </label>
                        <div class="col-8">
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
            </div>
        </div>
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
import {getAllCustomFields, detailsPageCustomFields} from "@app/Mixins/Global/CustomFieldMixin";
import { formatDateToLocal, numberFormatter } from "@app/Helpers/helpers";

export default {
    name: "CustomFiledManage",
    props: ['componentType', 'formData', 'updateUrl', 'statusCheck'],
    mixins: [FormMixin, getAllCustomFields, detailsPageCustomFields],
    data(){
        return{
            formatDateToLocal,
            numberFormatter,
            loadData: false,
            isCustomFieldEditable: true,
            editedData: {}
        }
    },
    computed:{
        checkIsEditable(){
            if (this.componentType == 'deal'){
                return this.isCustomFieldEditable && this.statusCheck;
            }
            return this.isCustomFieldEditable;
        }
    },
    created() {
        this.getAllCustomFields(this.componentType);
        this.detailsPageCustomField(this.componentType);
    },
    methods:{
        showCustomFieldHide() {
            this.isCustomFieldEditable = true;
        },

        showCustomFieldEditable() {
            this.isCustomFieldEditable = false;
        },

        beforeSubmit(){
            this.loadData = false;
        },
        showCustomFieldSave(){
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

            this.editedData.customs = customData;
            if (this.componentType == "person" || this.componentType == "organization"){
                this.editedData.name = this.formData.name;
                this.editedData.contact_type_id = this.formData.contact_type_id;
                this.editedData.owner_id = this.formData.owner_id;
            }else {
                this.editedData.title = this.formData.title;
            }
            this.submitFromFixin('patch', this.updateUrl, this.editedData);
        },

        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.isCustomFieldEditable = true
            this.$emit('update-request');
        },
    }
}
</script>

