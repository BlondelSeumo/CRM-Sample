<template>
    <div>
        <button type="button" class="btn btn-primary d-none" data-toggle="modal" data-target="#gain-modal"
                id="modalTrigger">
            Launch demo modal
        </button>
        <!-- Modal -->
        <div class="modal fade" id="gain-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <pre>{{JSON.stringify(actionData, null, 4)}}</pre>
                        <div v-if="action.type == 'page'" class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <small class="ml-3">Redirecting in another page...</small>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="action.type !== 'page'">
                        <button type="button" class="btn btn-default mr-2" data-dismiss="modal" @click="closeModal">
                            cancel
                        </button>
                        <button type="button" class="btn btn-success" @click="save">save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TestModal",
        props: {
            action: null,
            uniqueKey: null,
            actionData: null
        },
        methods: {
            closeModal() {
                this.$emit('close-modal')
            },
            save() {
                //perform save
              console.log('save function execute');

              //after perform save,close the modal and sent parent component that msg
                $('#modalTrigger').click();
              this.closeModal();
            }
        },
        mounted() {
            $('#modalTrigger').click();
        }
    }
</script>
