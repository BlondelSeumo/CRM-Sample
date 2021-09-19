<template>
    <div class="content-wrapper" style="min-height: 200px">
        <div class="pb-4">
            <app-tab type="horizontal" :tabs="horizontalTabs"/>
        </div>
        <div>
            <app-tab :tabs="tabs" :icon="icon"/>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TestTab",
        data() {
            return {
                icon: 'user',
                tabs: [
                    {
                        "headerButton": {
                            "label": 'Add New'
                        },
                        "name": "delivery",
                        "title": "delivery setting",
                        "component": "wizard-delivery",
                        "props": "Example string",
                        "permission": true
                    },
                    {
                        "name": "details",
                        "title": "details setting",
                        "component": "wizard-details",
                        "props": ['A', 'B', 'C'],
                        "permission": false,
                        "headerButton": {
                            "label": 'Add New',
                            "class": 'btn btn-success'
                        }
                    },
                    {
                        "headerButton": {
                            "label": "Add new template"
                        },
                        "name": "template",
                        "title": "template setting",
                        "component": "wizard-template",
                        "props": [
                            {id: 1, value: 'Cricket', disabled: true},
                            {id: 2, value: 'Football'},
                            {id: 3, value: 'Badminton'},
                            {id: 4, value: 'Option 4', disabled: true},
                            {id: 5, value: 'Option 5'},
                            {id: 6, value: 'Option 6'},
                        ],
                        "permission": ""
                    },
                    {
                        "name": "setup",
                        "title": "setup setting",
                        "component": "wizard-setup",
                        "props": "admin/something",
                        "permission": null
                    },
                    {
                        "name": "audience",
                        "title": "audience setting",
                        "component": "wizard-audience",
                        "props": "/www.google.com",
                    }
                ],
                horizontalTabs: [
                    {
                        "name": "delivery",
                        "icon": "activity",
                        "component": "wizard-delivery",
                        "props": "Example string",
                        "permission": true
                    },
                    {
                        "name": "details",
                        "icon": "phone-call",
                        "component": "wizard-details",
                        "props": ['A', 'B', 'C'],
                        "permission": false
                    },
                    {
                        "name": "template",
                        "icon": "mail",
                        "component": "wizard-template",
                        "props": [
                            {id: 1, value: 'Cricket', disabled: true},
                            {id: 2, value: 'Football'},
                            {id: 3, value: 'Badminton'},
                            {id: 4, value: 'Option 4', disabled: true},
                            {id: 5, value: 'Option 5'},
                            {id: 6, value: 'Option 6'},
                        ],
                        "permission": false
                    },
                    {
                        "name": "setup",
                        "icon": "paperclip",
                        "component": "wizard-setup",
                        "props": "admin/something",
                        "permission": null
                    },
                    {
                        "name": "audience",
                        "icon": "file-text",
                        "component": "wizard-audience",
                        "props": "/www.google.com",
                    }
                ]
            }
        }
    }
</script>
