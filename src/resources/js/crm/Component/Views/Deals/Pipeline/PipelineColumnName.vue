<template>
    <div class="d-flex align-items-center">
        <template v-if="$can('update_pipelines')">
            <a :href="route('deals_pipeline.page', {_query:{'pipeline': rowData.id}})">
                {{ rowData.name }}
            </a>
        </template>
        <template v-else>
            <span>{{ rowData.name }}</span>
        </template>

    </div>
</template>

<script>
export default {
    name: "PipelineColumnName",
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


