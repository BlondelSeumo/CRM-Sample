<template>
    <div class="media d-flex align-items-center">
        <div class="avatars-w-50">
            <app-avatar :alt-text="rowData.name"
                        :img="rowData.profile_picture ?
                            urlGenerator(rowData.profile_picture.path) :
                            rowData.profile_picture"
                        :shadow="true"
                        :title="rowData.name"/>
        </div>
        <div class="media-body ml-3">
            <a v-if="data == 'person'"
               :href="urlGenerator(route('persons.edit', {id: rowData.id}))">
                {{ rowData.name }}
            </a>
            <template v-else>
                <template v-if="$can('update_organizations')">
                    <a :href="urlGenerator(route('organizations.edit', {id: rowData.id}))">
                        {{ rowData.name }}
                    </a>
                </template>
                <template v-else>
                    <span> {{ rowData.name }} </span>
                </template>

            </template>

        </div>
    </div>
</template>

<script>
import {urlGenerator} from "@app/Helpers/helpers";

export default {
    name: "MediaName",
    props: {
        rowData: {
            type: Object,
            required: true
        },
        data: {
            type: String,
        }
    },
    data() {
        return {
            route,
            urlGenerator,
        }
    },
}
</script>

