<template>
    <div class="content-wrapper">
        <button type="button" class="btn btn-primary" @click="showModal=true">
            Modal
        </button>
        <button type="button" class="btn btn-primary" @click="deleteModal=true">
            Delete Modal
        </button>
        <app-modal
            :modal-id="'example'"
            :modal-backdrop="true"
            v-if="showModal"
            modal-size="default"
            @close-modal="closeAddEditModal">
            <template slot="header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close outline-none" @click.prevent="closeAddEditModal">
               <span>
                   <app-icon :name="'x'"></app-icon>
               </span>
                </button>
            </template>

            <template slot="body">
                <div class="p-4">This is for testing modal component</div>
                <button class="btn btn-primary" type="button" @click="openSecondModal">Second Modal</button>
                <form action="">
                    <app-input type="text-editor" v-model="textEditor"/>
                </form>
            </template>

            <template slot="footer">
                <button type="button" class="btn btn-secondary mr-4" data-dismiss="modal" @click="closeAddEditModal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" @click="save">Save changes</button>
            </template>
        </app-modal>

        <!--Delete Confirmation Modal -->
        <app-confirmation-modal v-if="deleteModal" :modal-id="'delete-example'" @confirmed="confirmed"
                                @cancelled="cancelled"/>
        <!--second modal-->
        <app-modal :modal-id="'second-modal'" v-if="secondModal" modal-size="default"
                   @close-modal="closeSecondModal">
            <div class="p-primary">
                this is test second modal
            </div>
        </app-modal>
    </div>
</template>

<script>
import TestModal from "./TestModal";
import {FormMixin} from "../../mixins/form/FormMixin";

export default {
    name: "ExampleModal",
    mixins: [FormMixin],

    components: {TestModal},

    data() {
        return {
            showModal: false,
            textEditor: '',
            deleteModal: false,
            secondModal: false
        }
    },
    methods: {
        closeAddEditModal() {
            $('#example').modal('hide');
            this.showModal = false;
        },
        save() {
            console.log('Saved clicked');
            $('#example').modal('hide');
            this.showModal = false;
        },
        confirmed() {
            console.log('Clicked Confirmed');
            $('#delete-example').modal('hide');
            this.deleteModal = false;
        },
        cancelled() {
            console.log('Clicked Cancel');
            $('#delete-example').modal('hide');
            this.deleteModal = false;
        },
        openSecondModal() {
            this.secondModal = true;
            setTimeout(() => {
                $('#second-modal').css({
                    "fontSize": "30px",
                });
                $('#example').css({
                    "opacity": ".2"
                });
            })
        },
        closeSecondModal() {
            $('#second-modal').modal('hide');
            this.secondModal = false;
            $('#example').css({
                "opacity": "1"
            });
        }
    }
}
</script>
