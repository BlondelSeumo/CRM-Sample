<template>
    <div class="border-bottom mb-4 pb-4">
        <div class="d-flex align-items-center justify-content-between">
            <p class="mb-2 font-weight-bold">
                {{ $t("expected_closing_date") }}
            </p>
            <div>
                <a
                    v-show="!isEditExpireDate"
                    class="text-muted"
                    href="#"
                    @click.prevent="closeDateEDit"
                >
                    <app-icon name="x-square" class='size-20' stroke-width="1" />
                </a>
                <a
                    v-show="!isEditExpireDate"
                    class="text-muted"
                    href="#"
                    @click.prevent="updateExpireDate"
                >
                    <app-icon name="check-square" class='size-20' stroke-width="1" />
                </a>
            </div>
            <a
                v-show="isEditExpireDate && clientAccess && dealStatus"
                class="text-muted"
                href="#"
                @click.prevent="editExpireDate"
            >
                <app-icon name="edit" class='size-20' stroke-width="1" />
            </a>
        </div>
        <p class="mb-0 font-size-90 text-muted" v-show="isEditExpireDate">
            <app-icon class="text-primary size-15" name="calendar" />
            {{
                formData.expired_at
                    ? formatDateToLocal(formData.expired_at)
                    : $t("not_added_yet")
            }}
        </p>
        <div class="col-sm-12 p-0" v-show="!isEditExpireDate">
            <app-input type="date"
                       v-model="formData.expired_at"
                       :placeholder="$t('choose_a_date')"
                       :popover-position="'top-start'"
                       :error-message="$errorMessage(errors, 'expired_at')"
            />
        </div>
    </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
import { formatDateToLocal } from "@app/Helpers/helpers";
import moment from "moment";
export default {
    name: "ExpireDateAddEdit",
    props:['formData', 'stages', 'clientAccess', 'dealStatus'],
    mixins: [FormMixin],
    data(){
        return{
            errors: [],
            formatDateToLocal,
            isEditExpireDate: true,
        }
    },
    methods:{
        editExpireDate(){
            this.isEditExpireDate = false;
        },
        closeDateEDit(){
            this.isEditExpireDate = true;
        },

        updateExpireDate(){
            let dealData = {};
            dealData.title = this.formData.title;
            dealData.expired_at = this.formData.expired_at ? moment(this.formData.expired_at).format(
                "YYYY-MM-DD"
            ) : null;
            this.submitFromFixin('patch', route('deals.update', {id: this.formData.id}), dealData)
        },
        afterSuccess(response) {
            this.isEditExpireDate = true;
            this.$toastr.s(response.data.message);
            this.$emit("update-request");
        },
    }
}
</script>
