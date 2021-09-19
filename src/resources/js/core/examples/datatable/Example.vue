<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="'Page Title'" :icon="'settings'"/>

        <div class="mb-primary">
            <button class="btn btn-primary" type="button" @click.prevent="testCol">Changed</button>
            <button class="btn btn-secondary" type="button" @click.prevent="testColRev">Changed reverse</button>
        </div>

        <app-table
            :id="'test-table-core'"
            :options="options"
            @action="getAction"
            @getRows="getSelectedRows"
            @getFilteredValues="filteredValues"
        />

        <test-modal v-if="isShowTestModal" @close-modal="closeModal"/>

        <app-confirmation-modal
            v-if="isShowDeleteModal"
            icon="check-circle"
            title="Hand's Up"
            sub-title="This content will be deleted permanently"
            message="Are you sure?"
            modal-class="success"
            modal-id="delete-modal"
            @confirmed="confirmed"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import TestModal from "../modal/TestModal";

export default {
    name: "Example",

    components: {TestModal},

    data() {
        return {
            isShowTestModal: false,
            isShowDeleteModal: false,
            defaultColumns: [
                {},
                {
                    title: 'Invoice',
                    type: 'link',
                    key: 'invoice',
                    isVisible: true,
                    // sortable: true,
                    modifier: (value, row) => {
                        return `test/${row.invoice}/test`;
                    }
                },
                {
                    title: 'Name',
                    type: 'custom-html',
                    key: 'name',
                    isVisible: true,
                    // sortable: true,
                    titleAlignment: 'right', // available right default (left)
                    modifier: (value) => {
                        return `<p class="text-right m-0">${value}</p>`
                    }
                },
                {
                    title: 'Something',
                    type: 'expandable-column',
                    key: 'expandable_data',
                    className: 'success', // Should be warning, success, primary, danger, dark, light, secondary
                    isVisible: true, // not required
                    componentName: 'expandable-view',
                    showTitle: 'Show', // not required. if not given, must send in modifier
                    hideTitle: 'Hide', // not required. if not given, must send in modifier
                    showIcon: 'eye', // not required
                    hideIcon: 'eye-off', // not required
                    modifier: (value, row) => {
                        let returnObj = {};
                        returnObj.prefixData = 12; // not required // default false
                        returnObj.title = 'expand'; // Button Title
                        returnObj.expandable = true; // Expandable and Component render (default true)
                        returnObj.button = true; // Clickable Button  (Default false and show badges)
                        returnObj.visible = true; // Column visibility (Default true)
                        if (!value) {
                            returnObj.expandable = false
                        }
                        return returnObj;
                    }
                },
                {
                    title: 'Age',
                    type: 'button',
                    key: 'age',
                    className: 'btn btn-success',
                    // icon: 'check',
                    label: 'click me',
                    isVisible: true,
                    modifier: (value, row) => {
                        if (value > 30) return false;
                        return value
                    }
                },
                {
                    title: 'Dynamic Content',
                    type: 'dynamic-content',
                    key: 'name',
                    label: 'click me',
                    // icon: 'check',
                    className: 'btn btn-success',
                    modifier: (value, row) => {
                        return {
                            isValue: false,
                            value: 'dynamic-contnent/' + row.invoice
                            // value: row.invoice
                        }
                    }
                },
                {
                    title: 'Image',
                    type: 'image',
                    key: 'image',
                    altText: "No found",
                    className: "avatars-group-w-50",
                    isVisible: false,
                    default: "https://images.unsplash.com/photo-1537815749002-de6a533c64db?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1090&q=80"
                },
                {
                    title: 'Media Object',
                    type: 'media-object',
                    key: 'image',
                    mediaTitleKey: 'name',
                    mediaSubtitleKey: 'email',
                    default: "https://images.unsplash.com/photo-1537815749002-de6a533c64db?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1090&q=80",
                    isVisible: false,
                    modifier: (value, row) => {
                        return value; // img url
                    }
                },
                {
                    title: 'component',
                    type: 'component',
                    key: 'users',
                    isVisible: false,
                    componentName: 'test-avatar-group',
                },
                {
                    title: 'Tags',
                    type: 'component',
                    key: 'users',
                    componentName: 'test-tag-manager',
                },
                {
                    title: 'status',
                    type: 'custom-class',
                    key: 'status',
                    isVisible: false,
                    modifier: (value) => {
                        if (value === 'Active') return "badge badge-pill badge-primary";
                        return "badge badge-pill badge-danger";
                    }
                },
                {
                    title: 'status 2',
                    type: 'custom-html',
                    key: 'status2',
                    isVisible: false,
                    modifier: (value) => {
                        if (value === 'Active') return `<h1>${value}</h1>`;
                        return `<h2>${value}</h2>`;
                    }
                },
                {
                    title: 'user',
                    type: 'object',
                    key: 'created_by',
                    isVisible: false,
                    modifier: (value, row) => {
                        return value.first_name + ' ' + value.last_name
                    }
                },
                {
                    title: 'Action',
                    type: 'action',
                    isVisible: true,
                    key: 'invoice'
                },
            ],
            options: {
                afterRequestError: function (error){
                    console.log('Error happened', error)
                },
                afterRequestSuccess: ({data}) => {
                    console.log(data.data)
                },
                name: 'TestTable',
                url: 'test-value',
                dataKey: 'data',
                showHeader: true,
                paginationOptions: [5, 10, 15],
                datatableWrapper: true,
                paginationBlockClass: 'mt-primary',
                showCount: true,
                showClearFilter: true,
                managePagination: true,
                cardViewComponent: 'test-form',
                cardViewEmptyBlock: false,
                cardViewPagination: false,
                cardViewQueryParams: {
                    'view_type': 'calendar'
                },
                sortByFilter: {
                    isVisible: true,
                    label: 'Sort By',
                    key: 'sort_by',
                    options: ['most_recent','most_viewed','top_rated'],
                },
                enableCookie: true,
                columns: [],
                filters: [
                    {
                        title: "Select an option",
                        type: "dropdown-menu-filter",
                        key: "dropdown-menu",
                        initValue: 3,
                        option: [
                            {
                                id: 1,
                                name: 'How many deals were won?',
                                icon: 'award'
                            },
                            {
                                id: 2,
                                name: 'How many deals were lost?',
                                icon: 'frown'
                            },
                            {
                                id: 3,
                                name: 'How many deals were average?',
                            }
                        ],
                        listValueField: 'name'
                    },
                    {
                        title: "Date Range",
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "nextYear"]
                    },
                    {
                        type: 'date',
                        key: 'single-date',
                    },
                    {
                        title: 'Toggle Button',
                        type: 'toggle-filter',
                        key: 'is_active',
                        buttonLabel: {
                            active: 'Active',
                            inactive: 'Inactive'
                        },
                        header: {
                            title: "Want to filter only active",
                            description: "You can filter your data table which are created based on segment"
                        }
                    },
                    {
                        title: "Sending rate",
                        type: "range-filter",
                        key: "sending rate",
                        maxTitle: "Max usd",
                        minTitle: "Min usd",
                        sign: "$",
                        maxRange: 60,
                        minRange: 20
                    },
                    {
                        title: "Status",
                        type: "checkbox",
                        key: "status",
                        option: [
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
                        title: "List with segment",
                        type: "radio",
                        key: "list-with-segment",
                        initValue: 2,
                        header: {
                            "title": "Want to filter your list?",
                            "description": "You can filter your data table which are created based on segment"
                        },
                        option: [
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
                        title: "Search & select",
                        type: "drop-down-filter",
                        key: "search-select",
                        active: 3,
                        option: [
                            {id: 1, name: 'Cricket'},
                            {id: 2, name: 'Football'},
                            {id: 3, name: 'Badminton'},
                            {id: 4, name: 'Option 4', disabled: true},
                            {id: 5, name: 'Option 5'},
                            {id: 6, name: 'Option 6'},
                        ],
                        listValueField: 'name'
                    },
                    {
                        title: "Multi Select",
                        type: "multi-select-filter",
                        key: "multi select",
                        option: [
                            {id: 1, name: 'Cricket'},
                            {id: 2, name: 'Football'},
                            {id: 3, name: 'Badminton'},
                            {id: 4, name: 'Option 4'},
                            {id: 5, name: 'Option 5'},
                            {id: 6, name: 'Option 6'},
                        ],
                        listValueField: 'name'
                    },
                    {
                        title: "Avatar Filter",
                        active: 1,
                        type: "avatar-filter",
                        key: "user",
                        option: [
                            {
                                id: 1,
                                name: 'Cricket',
                                status: 'success',
                                profile_picture: {
                                    id: 1,
                                    full_url: "https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60",
                                    type: "profile_picture"
                                }
                            },
                            {
                                id: 2,
                                name: 'Football',
                                status: 'danger',
                                img: ''
                            },
                            {
                                id: 3,
                                name: 'Badminton',
                                status: 'success',
                                profile_picture: {
                                    id: 1,
                                    full_url: "https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60",
                                    type: "profile_picture"
                                }
                            },
                            {
                                id: 4,
                                name: 'Option 4',
                                status: 'success',
                            },
                            {
                                id: 5,
                                name: 'Option 5',
                                status: 'warning',
                                img: 'https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60'
                            },
                            {
                                id: 6,
                                name: 'Option 6',
                                status: 'success',
                            }
                        ],
                        listValueField: 'name',
                        imgRelationship: 'profile_picture',
                        imgKey: 'full_url'
                    },
                ],
                enableRowSelect: true,
                // tablePaddingClass: 'p-primary',
                tableShadow: true,
                showSearch: true,
                showFilter: true,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "dropdown",
                actions:
                    [
                        {},
                        {
                            title: 'Active',
                            modifier: (row) => {
                                if (row.status === 'Inactive') {
                                    return true
                                }
                            }
                        },
                        {
                            title: 'Inactivate',
                            modifier: (row) => {
                                if (row.status === 'Active') {
                                    return true
                                }
                            }
                        },
                        {
                            title: 'Edit',
                            icon: 'edit',
                            type: 'none',
                            component: 'test-modal',
                            url: 'edit-test',
                            uniqueKey: 'invoice',
                        },
                        {
                            title: 'Delete',
                            icon: 'trash',
                            component: 'test-modal',
                            type: 'none',
                            url: '',
                        },
                        {
                            title: 'view',
                            icon: 'trash',
                            type: 'page',
                            url: '',
                        }
                    ],
            },
            tenantInfo: {
                logo: {
                    value: 'images/core.png',
                },
                name: 'Bkash',
                admin: {
                    full_name: 'demo@mail.com'
                },
                status: {
                    translated_name: 'Invited',
                    name: 'status_invited'
                },
                created_by: {
                    full_name: 'John doe'
                },
                short_name: 'bkash43',
                tenant_group: {
                    name: 'Telecom'
                },
                url: 'sendex.gainhq.com',
            },
            actions: [
                {
                    "title": "Edit",
                },
                {
                    "title": "Delete",
                }
            ]
        }
    },
    created() {
        this.options.columns = this.defaultColumns;
    },
    methods: {
        getSelectedRows(value) {
            // console.log(value);
        },
        filteredValues(value) {
            // console.log(value);
        },
        getAction(row, actionObj, active) {
            // console.log(actionObj.title);
            if (actionObj.title == 'Edit') {
                this.openModal();
            } else if (actionObj.title == 'Delete') {
                this.openDeleteModal();
            }
        },
        openModal() {
            this.isShowTestModal = true;

            setTimeout(function () {
                $("#exampleModal").modal('show');
            });
        },
        openDeleteModal() {
            this.isShowDeleteModal = true;

            setTimeout(function () {
                $("#delete-modal").modal('show');
            });
        },
        confirmed() {
            this.isShowDeleteModal = false;
            console.log('Clicked Confirmed');
        },
        cancelled() {
            this.isShowDeleteModal = false;
            console.log('Clicked Canceled');
        },
        closeModal() {
            this.isShowTestModal = false;
        },
        testCol() {
            this.options.columns = [...this.defaultColumns, {
                'title': 'Test',
                'type': 'text',
                'key': 'invoice',
                'isVisible': true
            }]
            this.options.filters.find(item => item.type === 'radio').initValue = 1
            this.options.filters.find(item => item.type === 'avatar-filter').active = 3
            this.options.filters.find(item => item.type === 'dropdown-menu-filter').initValue = 1
        },
        testColRev() {
            this.options.columns = [...this.defaultColumns]
        }
    }
}
</script>
