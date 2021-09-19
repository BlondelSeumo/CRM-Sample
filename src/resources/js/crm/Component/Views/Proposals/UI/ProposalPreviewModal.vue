<template>
    <app-modal
            :modal-id="modalId"
            modalSize="fullscreen"
    >
        <template slot="header">
            <h5 class="modal-title">
                <app-icon name="cpu" /> {{ title }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    @click="$emit('close')">
                <span aria-hidden="true" class="close-btn">&times;</span>
            </button>
        </template>
        <template slot="body">
          <div class="d-flex justify-content-center">
            <span class="v-html-wrapper" v-html="content"/>
          </div>
        </template>
    </app-modal>
</template>

<script>

    export default {
        name: "ProposalPreviewModal",
        props: {
            content: {
                type: String,
            },
            modalId: {
                type: String,
                require: true
            },
            title: {
                type: String,
                default() {
                    return this.title;
                }
            }
        },
        data() {
            return {

            }
        }
    }
</script>

<style scoped>

</style>
