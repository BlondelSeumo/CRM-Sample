<template>
    <div>
        <div style="min-height: 300px">
            I am a setting for testing dynamic tab and wizard - WizardSetup
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
        name: "WizardSetup",
        props: {
            props: {
                default: ''
            }
        },
        methods: {
            goBack() {
                this.$emit('back', true);
            },
            goNext() {
                this.$emit('next', true);
            }
        }
    }
</script>
