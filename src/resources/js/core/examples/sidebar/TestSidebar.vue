<template>
    <sidebar :data="data"/>
</template>

<script>
    export default {
        name: "TestSidebar",

        data(){
            return{
                data: [
                    {
                        icon: 'sun',
                        name: 'Dashboard',
                        url: '/',
                        permission: true
                    },
                    {
                        id: 'components',
                        icon: 'briefcase',
                        name: 'Test Components',
                        permission: true,
                        subMenu: [
                            {
                                name: 'Datatable',
                                url: '/',
                                permission: true
                            },
                            {
                                name: 'Form',
                                url: '/form',
                                permission: true
                            },
                        ]
                    }
                ]
            }
        }
    }
</script>
