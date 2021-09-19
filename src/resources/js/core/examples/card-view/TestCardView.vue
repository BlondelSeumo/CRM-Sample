<template>
    <div class="content-wrapper">
        <app-card-view
            id="test-card-view"
            :properties="properties"
            @action="getActions"
            @selectedTemplateCard="getCard"
        />
        <!--Normal Modal -->
        <app-modal :modal-id="'example'" v-if="addEditModal" modal-size="default" @close-modal="closeAddEditModal">
            <template slot="header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close outline-none" aria-label="Close" data-dismiss="modal">
               <span>
                   <app-icon :name="'x'"></app-icon>
               </span>
                </button>
            </template>

            <template slot="body">
                <p v-html="selectedCard['custom_content']?selectedCard['custom_content']:selectedCard['default_content']"></p>
            </template>

            <template slot="footer">
                <button type="button" class="btn btn-secondary mr-4" data-dismiss="modal" @click="cancel">Close
                </button>
                <button type="button" class="btn btn-primary" @click="save">Save changes</button>
            </template>
        </app-modal>
        <!--Delete Confirmation Modal -->
        <app-confirmation-modal
            v-if="showDeleteModal"
            :modal-id="'delete-example'"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
export default {
    name: "TestCardView",
    data() {
        return {
            selectedCard: {},
            addEditModal: false,
            showDeleteModal: false,
            properties: {
                filters: [
                    {
                        "title": "date",
                        "type": "range-picker",
                        "key": "date",
                        "option": ["today", "thisMonth", "last7Days", "nextYear"]
                    },
                    {
                        "title": "sending rate",
                        "type": "range-filter",
                        "key": "sending rate"
                    },
                    {
                        "title": "status",
                        "type": "checkbox",
                        "key": "status",
                        "option": [
                            {
                                id: 1,
                                name: "active"
                            },
                            {
                                id: 2,
                                name: "invited"
                            },
                            {
                                id: 3,
                                name: "subscribed"
                            },
                            {
                                id: 4,
                                name: "multiple word",
                                disabled: true
                            },
                            {
                                id: 5,
                                name: "Option 1",
                                disabled: true
                            },
                            {
                                id: 6,
                                name: "Option 2"
                            }

                        ],
                        listValueField: 'name'
                    },
                    {
                        "title": "list with segment",
                        "type": "radio",
                        "key": "list-with-segment",
                        "header": {
                            "title": "Want to filter your list?",
                            "description": "You can filter your data table which are created based on segment"
                        },
                        "option": [
                            {
                                id: 1,
                                name: "with segment"
                            },
                            {
                                id: 2,
                                name: "without segment"
                            }
                        ],
                        listValueField: 'name'
                    },
                    {
                        "title": "search & select",
                        "type": "drop-down-filter",
                        "key": "search select",
                        "option": [
                            {id: 1, name: 'Cricket'},
                            {id: 2, name: 'Football'},
                            {id: 3, name: 'Badminton'},
                            {id: 4, name: 'Option 4', disabled: true},
                            {id: 5, name: 'Option 5'},
                            {id: 6, name: 'Option 6'},
                        ],
                        listValueField: 'name'
                    },
                ],
                showClearFilter: true,
                showFilter: true,
                url: "test-cards",
                customContentKey: 'custom_content',
                defaultContentKey: 'default_content',
                subjectKey: 'subject',
                showAction: true,
                showSearch: true,
                previewType: "html",
                previewImageKey: {
                    relation: 'thumbnail',
                    key: 'path'
                },
                actions: [
                    {
                        title: "Preview",
                        icon: "eye",
                        type: "page",
                        pageUrl: "www.google.com",
                        modifier: function (row) {
                            return row.id > 22;
                        }
                    },
                    {
                        title: "Edit",
                        icon: "edit",
                        type: "modal",
                        modalId: "edit-modal",
                        modifier: function (row) {
                            return row.id > 22;
                        }
                    },
                    {
                        title: "Delete",
                        icon: "trash",
                        type: "modal",
                        modalId: "delete-modal",
                        modifier: function (row) {
                            return row.id > 22;
                        }
                    },
                    {
                        title: "Duplicate",
                        icon: "copy",
                        type: "page",
                        pageUrl: "#"
                    },
                    {
                        title: "Use Campaign",
                        icon: "zap",
                        type: "page",
                        pageUrl: "#"
                    },
                    {
                        title: "Download",
                        icon: "download",
                        type: "page",
                        pageUrl: "#"
                    }
                ],
                cardLimit: 10
            }
        }
    },
    methods: {
        getActions(card, actionObj, active) {
            console.log(card);
            console.log(actionObj);
            console.log(active);
            this.selectedCard = card;
            if (actionObj.type === 'modal') {
                if (actionObj.modalId === 'edit-modal')
                    this.addEditModal = true;
                else if (actionObj.modalId === 'delete-modal') {
                    this.showDeleteModal = true;
                }
            }
        },
        getCard(card) {
            console.log(card);
        },
        confirmed() {
            console.log('Clicked Confirmed');
            this.showDeleteModal = false;
        },
        cancelled() {
            console.log('Clicked Cancel');
            this.showDeleteModal = false;
        },
        closeAddEditModal() {
            this.addEditModal = false;
        },
        save() {
            console.log('Saved clicked');

            $('#example').modal('hide');
            this.addEditModal = false;
        },
        cancel() {
            this.addEditModal = false;
            console.log('cancel clicked');
        }
    }
}
</script>
