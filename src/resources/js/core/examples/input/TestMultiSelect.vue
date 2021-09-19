<template>
    <div class="content-wrapper" style="min-height: 100vh">

        <div class="form-group">
            <app-input type="multi-select"
                       v-model="multiSelect"
                       :list="multiSelectOptionList"
            />
        </div>

    </div>
</template>

<script>
    import {FormMixin} from "../../mixins/form/FormMixin.js";

    export default {
        name: "TestMultiSelect",
        mixins: [FormMixin],
        data() {
            return {
                multiSelect: [1, 2],
                multiSelectOptionList: [
                    {id: 1, value: 'Cricket'},
                    {id: 2, value: 'Football'},
                    {id: 3, value: 'Badminton'},
                    {id: 4, value: 'Option 4'},
                    {id: 5, value: 'Option 5'},
                    {id: 6, value: 'Option 6'},
                ]
            }
        }
    }
</script>
