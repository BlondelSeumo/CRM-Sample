<template>
    <div class="d-flex align-items-center">
        <template v-if="$can('update_deals')">
            <a :href="route('deal_details.page', {id: rowData.id})">
                {{ rowData.title }}
            </a>
        </template>
        <template v-else>
            <span>{{ rowData.title }}</span>
        </template>

    </div>
</template>

<script>
export default {
    name: "DealTitleColumn",
    props: {
        rowData: {
            type: Object,
            required: true
        },
        value: {
            type: String,
        }
    },
    data() {
        return {
            route,
        }
    },
}
</script>

