<template>
    <app-breadcrumb :page-title="pageTitle" :directory="directory" :icon="icon"/>
</template>

<script>
    export default {
        name: "TestBreadcrumb",
        data() {
            return {
                pageTitle: 'Home',
                directory: 'Datatable',
                icon: 'sun',
            }
        },

    }
</script>

<style scoped>

</style>