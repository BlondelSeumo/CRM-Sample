<template>
    <div>
        <div v-for="(item, index) in personOrgData" v-if="index < 2">
            <template v-if="$can('update_persons')">
                <template v-if="rowData.persons">
                    <a v-if="adminAccess(item)"
                       class="mb-1 d-flex" :href="route('persons.edit',{person: item.id})" target="_blank">
                        <span class="link-list">{{ item.name }}</span>
                        <span class="text-muted mt-1">
                    <i data-feather="external-link"></i>
                </span>
                    </a>
                    <a v-else
                       class="mb-1 d-flex">
                        <span class="link-list">{{ item.name }}</span>
                    </a>
                </template>
                <template v-else>
                    <a v-if="adminAccess(item)"
                       class="mb-1 d-flex" :href="route('organizations.edit',{organization: item.id})" target="_blank">
                        <span class="link-list">{{ item.name }}</span>
                        <span class="text-muted mt-1">
                    <i data-feather="external-link"></i>
                        </span>
                    </a>
                    <a v-else
                       class="mb-1 d-flex">
                        <span class="link-list">{{ item.name }}</span>
                    </a>
                </template>
            </template>

            <template v-else>
                <span>{{ item.name }}</span>
            </template>

            <small class="cursor-pointer" @click.prevent="viewAll" v-if="personOrgData.length > 2 && index == 1">
                <b>+{{ personOrgData.length - 2 }} more</b>
            </small>
        </div>

        <all-person-org
            v-if="isViewModalOpen"
            :row-data="personOrgData"
            :component-type="rowData.persons ? 'person' : 'organization'"
            @close-modal="closeModal"
        />
    </div>
</template>

<script>
import {collection} from "../../../Helpers/helpers";

export default {
    name: "CommonPersonOrgColumn",
    props: {
        rowData: {
            type: Object,
            required: true
        },
        value: {
            type: Array,
        }
    },
    data() {
        return {
            route,
            isViewModalOpen: false,
        }
    },
    methods:{
        viewAll(){
            this.isViewModalOpen = true;
        },
        closeModal(){
            this.isViewModalOpen = false;
            $("#person-org-modal").modal("hide");
        },
        adminAccess(item){
            return (window.user.roles[0].is_admin || window.user.roles[0].name == 'Manager')  || window.user.id == item.owner_id;
        },
    },
    computed: {
        personOrgData() {
            let personsOrgList = this.value.length ? collection(this.value).get("name", "id", "owner_id") : [];
            return personsOrgList;
        }
    }
}
</script>
