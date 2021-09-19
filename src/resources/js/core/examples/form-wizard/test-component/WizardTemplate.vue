<template>
    <div>
        <div style="min-height: 300px">
            I am a setting for testing dynamic tab and wizard - WizardTemplate
            <app-modal v-if="showModal" modal-id="demo-modal" @close-modal="showModal=false">
                <div class="p-4">
                    This is test modal
                </div>
            </app-modal>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-12">
                    <button type="button" @click.prevent="goBack" class="btn btn-secondary mr-2">Back</button>
                    <button type="button" @click.prevent="goNext" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "WizardTemplate",
        props: {
            props: {
                default: ''
            },
            id: {
                type: String
            }
        },
        data(){
            return{
                showModal: false
            }
        },
        mounted() {
            this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
                console.log(component);
                this.showModal = true;
                console.log('Event Triggered');
            })
        },
        methods: {
            goBack() {
                this.$emit('back', true);
            },
            goNext() {
                this.$emit('next', true);
            }
        },
        beforeDestroy() {
            console.log('Before destroy');
            this.$hub.$off('headerButtonClicked-' + this.id);
        }
    }
</script>
