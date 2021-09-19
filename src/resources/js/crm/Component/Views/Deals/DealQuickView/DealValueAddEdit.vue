<template>
    <div class="border-bottom mb-4 pb-4">
        <div class="d-flex align-items-center justify-content-between">
            <p class="mb-2 font-weight-bold">Deal Value</p>
            <div>
                <a
                    v-show="!isEditDealValue"
                    class="text-muted"
                    href="#"
                    @click.prevent="closeValueEDit"
                >
                    <app-icon name="x-square" class='size-20' stroke-width="1" />
                </a>
                <a
                    v-show="!isEditDealValue"
                    class="text-muted"
                    href="#"
                    @click.prevent="updateDealValue"
                >
                    <app-icon name="check-square" class='size-20' stroke-width="1" />
                </a>
            </div>
            <a
                v-show="isEditDealValue && clientAccess && dealStatus"
                class="text-muted"
                href="#"
                @click.prevent="editDealValue"
            >
                <app-icon name="edit" class='size-20' stroke-width="1" />
            </a>
        </div>
        <p class="mb-0 font-size-90 text-muted" v-show="isEditDealValue">
            <app-icon class="text-primary size-15" name="dollar-sign" />
            {{ formData.value }}
        </p>
        <div class="col-sm-12 p-0" v-show="!isEditDealValue">
            <app-input type="text"
                       :placeholder="$t('enter_deal_value')"
                       :required="true"
                       v-model="formData.value"/>
        </div>
    </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
export default {
    name: "DealValueAddEdit",
    props:['formData', 'stages', 'clientAccess', 'dealStatus'],
    mixins: [FormMixin],
    data(){
        return{
            errors: [],
            isEditDealValue: true,
        }
    },
    methods:{
        editDealValue(){
            this.isEditDealValue = false;
        },
        closeValueEDit(){
            this.isEditDealValue = true;
        },
        updateDealValue(){
            let dealData = {};
            dealData.title = this.formData.title;
            dealData.value = this.formData.value;
            this.submitFromFixin('patch', route('deals.update', {id: this.formData.id}), dealData)
        },
        afterSuccess(response) {
            this.isEditDealValue = true;
            this.$toastr.s(response.data.message);
            this.$emit("update-request");
        },
    }
}
</script>
