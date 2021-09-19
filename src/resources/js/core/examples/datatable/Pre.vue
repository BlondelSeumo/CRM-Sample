<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="'Test Table'" :icon="'settings'" :directory="'Datatable'"/>
        <app-table :id="'test-table'" :options="options"/>
    </div>
</template>

<script>

    export default {
        name: "Example",

        data() {
            return {
                options: {
                    name: 'TestTable',
                    url: 'admin/app/brands',
                    showHeader: true,
                    columns: [
                        {
                            title: 'Invoice',
                            type: 'link',
                            key: 'invoice',
                            url: 'test',
                            uniqueKey: 'invoice',
                            sortAble: true,
                            isActive: true
                        },
                        {
                            title: 'name',
                            type: 'text',
                            key: 'name'
                        },
                        {
                            title: 'age',
                            type: 'text',
                            key: 'age',
                            isActive: true
                        },
                        {
                            title: 'status',
                            type: 'status',
                            key: 'status',
                            isActive: true,
                            modifier: (value) => {
                                if (value === 'Active') return {className: "badge badge-pill badge-primary"};
                                return {className: "badge badge-pill badge-danger"};
                            }
                        },
                        {
                            title: 'status 2',
                            type: 'custom',
                            key: 'status2',
                            isActive: false,
                            modifier: (value) => {
                                if (value === 'Active') return {htmlValue: `<h1>${value}</h1>`};
                                return {htmlValue: `<h2>${value}</h2>`};
                            }
                        },
                        {
                            title: 'Action',
                            type: 'action',
                            key: 'invoice',
                            isActive: true
                        },


                    ],
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
                            "option": ["active", "subscribed", "multiple word", "invited", "adfsd", "adsfdsf"]
                        },
                        {
                            "title": "list with segment",
                            "type": "radio",
                            "key": "list-with-segment",
                            "header": {
                                "title": "Want to filter your list?",
                                "description": "You can filter your data table which are created based on segment"
                            },
                            "option": ["with segment", "without segment"]
                        },
                        {
                            "title": "search & select",
                            "type": "drop-down-filter",
                            "key": "search select",
                            "option": [{id: 1, value: "sdfds"}, {id: 2, value: "ewoiweu"}]
                        }
                    ],
                    paginationType: "pagination",
                    responsive: false,
                    rowLimit: 10,
                    showAction: true,
                    actions: [
                        {
                            title: 'Edit',
                            icon: 'edit',
                            type: 'modal',
                            component: 'test-modal',
                            url: '',
                            // className: 'btn-primary',
                        }, {
                            title: 'Delete',
                            icon: 'trash',
                            // className: 'btn-danger',
                            component: 'test-modal',
                            type: 'page',
                            url: '',
                        }
                    ],
                }
            }
        },
    }
</script>
