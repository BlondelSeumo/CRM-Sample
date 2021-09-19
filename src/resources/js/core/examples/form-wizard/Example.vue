<template>
    <div class="content-wrapper p-4">
        <div class="card card-with-shadow border border-0 mb-primary">
            <div class="card-body">
                <app-form-wizard :tabs="tabs"
                                 @selectedComponentName="selectedComponent"
                                 @disabledTab="currentDisableTab"/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Example",
        data() {
            return {
                tabs: [
                    {
                        "name": "details",
                        "component": "wizard-details",
                        "props": "Example string"
                    },
                    {
                        "name": "delivery",
                        "component": "wizard-delivery",
                        "props": ['A', 'B', 'C']
                    },
                    {
                        "name": "template",
                        "component": "wizard-template",
                        "props": [
                            {id: 1, value: 'Cricket', disabled: true},
                            {id: 2, value: 'Football'},
                            {id: 3, value: 'Badminton'},
                            {id: 4, value: 'Option 4', disabled: true},
                            {id: 5, value: 'Option 5'},
                            {id: 6, value: 'Option 6'},
                        ]
                    },
                    {
                        "name": "setup",
                        "component": "wizard-setup",
                        "props": "admin/something"
                    },
                    {
                        "name": "audience",
                        "component": "wizard-audience",
                        "props": "/www.google.com"
                    }
                ]
            }
        },
        methods: {
            selectedComponent(value) {
                console.log(value);
            },
            currentDisableTab(disableTab) {
                console.log(disableTab);
            }
        }
    }
</script>
