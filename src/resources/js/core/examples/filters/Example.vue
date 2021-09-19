<template>
    <div class="content-wrapper">
        <div v-if="showFilter"
             class="filters-wrapper d-flex justify-content-start flex-wrap">

            <!--Open Filters Button For Mobile-->
            <button class="btn d-block d-sm-none btn-toggle-filters"
                    type="button"
                    @click.prevent="toggleFilters">
                <app-icon :name="'filter'"/>
                {{$t('filters')}}
            </button>

            <span v-if="filters.length > 0" v-show="isFiltersOpen" class="mobile-filters-wrapper">
                <app-filter table-id="demo-table" :filters="filters" @get-values="getFilterValues"/>

                <button type="button"
                        class="btn btn-primary btn-with-shadow d-sm-none btn-close-filter-wrapper d-flex justify-content-center align-items-center"
                        @click="toggleFilters">
                    {{$t('close')}}
                </button>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Example",
        data() {
            return {
                isFiltersOpen: true,
                showFilter: true,
                filters: [
                    {
                        "title": "Select an option",
                        "type":"dropdown-menu-filter",
                        "key":"dropdown-menu",
                        "initValue": 1,
                        "option": [
                            {
                                id: 1,
                                name: 'How many deals were won?',
                                icon: 'award'
                            },
                            {
                                id: 2,
                                name: 'How many deals were lost?',
                                icon: 'frown'
                            }
                        ],
                        listValueField: 'name'
                    },
                    {
                        "title": "Date",
                        "type": "range-picker",
                        "key": "date",
                        "option": ["today", "thisMonth", "last7Days", "nextYear"]
                    },
                    {
                        "title": "Sending rate",
                        "type": "range-filter",
                        "key": "sending rate"
                    },
                    {
                        "title": "Status",
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
                        "title": "List with segment",
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
                        "title": "Search & select",
                        "type": "drop-down-filter",
                        "key": "search-select",
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
                ]
            }
        },
        mounted() {
            this.isFiltersActive();
            window.onresize = () => {
                this.isFiltersActive();
            };
        },
        methods: {
            getFilterValues(values) {
                console.log(values);
            },
            isFiltersActive() {
                this.isFiltersOpen = window.innerWidth > 575;
            },
            toggleFilters() {
                this.isFiltersOpen = !this.isFiltersOpen;
            },
        }

    }
</script>
