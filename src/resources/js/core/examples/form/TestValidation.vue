<template>
    <div class="content-wrapper">
        <div class="card card-with-shadow border-0">
            <div class="card-body">

                <form ref="form" data-url="test-component" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        <app-input  type="text"
                                    v-model="test"/>
                    </div>
                      <button class="btn btn-primary" type="submit" @click.prevent="submitData">submit</button>
                </form>

            </div>
        </div>
    </div>

</template>

<script>
    import { FormMixin } from "../../mixins/form/FormMixin.js";

    export default {
        name: "TestValidation",
        mixins: [FormMixin],
        data() {
            return {
               test: "",
            }
        },
        methods: {
            submitData(){
                this.fieldStatus.isSubmit = true;
                if(this.test.length > 6){

                    this.save(this.testFields); // method from mixin

                }else{
                    this.fieldStatus['test'] = {
                        isValid : false,
                        message : "Length should more then 6"
                    }
                }
            }
        }
    }
</script>
