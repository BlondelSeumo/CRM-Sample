<template>
    <div>
        <a v-if="rowData.contextable_type === 'App\\Models\\CRM\\Deal\\Deal'"
           :href="route('deal_details.page', {'id': rowData.contextable_id})">
            {{ rowData.title }}
        </a>

        <a v-if="rowData.contextable_type === 'App\\Models\\CRM\\Person\\Person'"
           :href="route('persons.edit', {'id': rowData.contextable_id})">
            {{ rowData.title }}
        </a>

        <a v-if="rowData.contextable_type === 'App\\Models\\CRM\\Organization\\Organization'"
           :href="route('organizations.edit', {'id': rowData.contextable_id})">
            {{ rowData.title }}
        </a>

    </div>

</template>

<script>

export default {
    name: "ActivityDetailsPageLink",
    props: {
        rowData: {},
    },
    data() {
        return {
            route
        }
    }
}
</script>

