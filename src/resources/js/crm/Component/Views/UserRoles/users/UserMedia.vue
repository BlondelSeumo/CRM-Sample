<template>
    <div class="media d-flex align-items-center">
        <div class="avatars-w-50">
            <app-avatar :title="fullName"
                        status="success"
                        :shadow="true"
                        :img="$optional(value, 'path')"
                        :alt-text="fullName"
            />
        </div>
        <div class="media-body ml-3">
            {{ fullName }}
            <p class="text-muted font-size-90 mb-0">{{ rowData.email }}</p>
        </div>
    </div>
</template>
<script>
    export default {
        name: "UserMedia",
        props: {
            value: {},
            rowData: {},
            index: {}
        },
        computed: {
            fullName() {
                return this.rowData.full_name ?
                    this.rowData.full_name
                    : '';
            }
        }
    }
</script>
