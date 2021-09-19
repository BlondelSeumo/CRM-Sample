<template>
    <!-- use the modal component, pass in the prop -->
    <app-modal modal-id="exampleModal" modal-alignment="top" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>

        <template slot="body">
            <form ref="form" data-url="store" enctype="multipart/form-data">
                <div class="form-group">
                    <app-input type="text"
                                :required="true"
                                v-model="testFields.textValue"/>
                </div>

                <div class="form-group">
                    <app-input type="email"
                                v-model="testFields.email"/>
                </div>
            </form>
        </template>

        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-4" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="submitData">Save changes</button>
        </template>
    </app-modal>

</template>

<script>

    import {FormMixin} from "../../mixins/form/FormMixin.js";

    export default {
        name: "TestModal",
        mixins: [FormMixin],
        data(){
            return{
                testFields: {
                    textValue: '',
                    email: '',
                }
            }
        },
        mounted(){
            // console.log('exampleModal');
        },
        methods:{
            closeModal(value){
                // this.$emit('close-modal', value);
            },

            submitData() {

                this.save(this.testFields);
            },
        }
    }
</script>
