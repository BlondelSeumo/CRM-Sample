<template>
    <div>
        <template v-for="stage in stages">
            <div
                class="border-bottom mb-4 pb-4"
                v-if="stage.id == formData.stage_id"
                :key="stage.id"
            >
                <div class="d-flex align-items-center justify-content-between">
                    <p class="mb-2 font-weight-bold">{{ $t('stage') }}</p>
                    <div>
                        <a
                            v-show="!isEditableData"
                            class="text-muted"
                            href="#"
                            @click.prevent="closeEdit"
                        >
                            <app-icon name="x-square" class='size-20' stroke-width="1" />
                        </a>
                        <a
                            v-show="!isEditableData"
                            class="text-muted"
                            href="#"
                            @click.prevent="updateStage"
                        >
                            <app-icon name="check-square" class='size-20' stroke-width="1" />
                        </a>
                    </div>
                    <a
                        v-show="isEditableData && clientAccess && dealStatus"
                        class="text-muted"
                        href="#"
                        @click.prevent="editStage"
                    >
                        <app-icon name="edit" class='size-20' stroke-width="1" />
                    </a>
                </div>
                <p class="mb-0 font-size-90 text-muted" v-show="isEditableData">
                    <app-icon class="text-primary size-15" name="trello" />
                    {{ stage.name }}
                </p>
                <div class="col-sm-12 p-0" v-show="!isEditableData">
                    <app-input type="select"
                               list-value-field='name'
                               :placeholder="$t('choose_a_stage')"
                               :list="stages"
                               :required="true"
                               v-model="formData.stage_id"/>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
export default {
name: "StageAddEdit",
    props:['formData', 'stages', 'clientAccess', 'dealStatus'],
    mixins: [FormMixin],
    data(){
        return{
            errors: [],
            isEditableData: true,
        }
    },
    methods:{
        editStage(){
            this.isEditableData = false;
        },
        closeEdit(){
            this.isEditableData = true;
        },
        updateStage(){
            let dealData = {};
            dealData.title = this.formData.title;
            dealData.stage_id = this.formData.stage_id;
            this.submitFromFixin('patch', route('deals.update', {id: this.formData.id}), dealData)
        },
        afterSuccess(response) {
            this.isEditableData = true;
            this.$toastr.s(response.data.message);
            this.$emit("update-request");
        },
    }
}
</script>
