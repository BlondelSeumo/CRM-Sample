<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="'Test Table'" :icon="'settings'" :directory="'Datatable'"/>
        <div class="card border-0 bg-transparent">

             <div class="card-header d-flex align-items-center p-primary primary-card-color">

                <h4 class="card-title d-inline-block mb-0">Table name</h4>
                <app-search @input="getSearchValue"/>

            </div>
            <div class="card-body p-0">
                <app-table :id="tableId" :options="options" :filtered-data="filteredData" :search="search" @action="getAction"/>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "NewExampleWithoutWrapper",

        data() {
            return {
                tableId: 'test-table-2',
                filteredData: {},
                search: '',
                options: {
                    name: 'TestTable',
                    url: 'test-value',
                    datatableWrapper: false,
                    showHeader: true,
                    columns: [
                        {
                            title: 'Invoice',
                            type: 'link',
                            key: 'invoice',
                            sortAble: true,
                            isVisible: true,
                            modifier: (value, row) => {
                                return `test/${row.invoice}/test`;
                            }
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
                            isVisible: true
                        },
                        {
                            title: 'Action',
                            type: 'action',
                            key: 'invoice',
                            isVisible: true
                        },
                    ],
                    showFilter: false,
                    paginationType: "pagination",
                    responsive: true,
                    rowLimit: 10,
                    orderBy: 'desc',
                    actionType: "dropdown",
                    actions: [
                        {
                            title: 'Edit',
                            icon: 'edit',
                            type: 'page',
                            component: 'test-modal',
                            url: 'edit-test',
                            uniqueKey: 'invoice',
                            className: 'btn-primary',
                        }, {
                            title: 'Delete',
                            icon: 'trash',
                            component: 'test-modal',
                            type: 'page',
                            url: '',
                        },
                        {
                            title: 'view',
                            icon: 'trash',
                            type: 'none',
                            url: '',
                        }
                    ],
                }
            }
        },

        methods: {
            getAction(rowData, actionObj, active) {
                console.log(rowData, 'row');
                console.log(actionObj, 'actionObj');
                console.log(active, 'active');
            },
            getSearchValue(value){
                let instance = this;
                this.search = value;
                setTimeout(function(){
                    instance.$hub.$emit('reload-'+instance.tableId);
                });
            },
        }
    }
</script>
