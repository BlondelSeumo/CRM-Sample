<template>
    <app-modal modal-alignment="top" modal-id="person-org-modal" modal-size="default" @close-modal="closeModal">
        <template slot="header">
            <h5 v-if="componentType == 'person'" class="modal-title">{{ $t('view_all_person') }}</h5>
            <h5 v-else class="modal-title">{{ $t('view_all_organization') }}</h5>
            <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <div v-for="(item, index) in rowData">
                <template v-if="componentType == 'person'">
                    <a v-if="adminAccess(item)"
                       class="mb-1 d-flex" :href="route('persons.edit',{person: item.id})" target="_blank">
                        <span class="org-person-name">{{ item.name }}</span>
                        <span class="text-muted mt-1"></span>
                    </a>
                    <a v-else
                       class="mb-1 d-flex">
                        <span class="org-person-name">{{ item.name }}</span>
                    </a>
                </template>
                <template v-else>
                    <a v-if="adminAccess(item)"
                       class="mb-1 d-flex" :href="route('organizations.edit',{organization: item.id})" target="_blank">
                        <span class="org-person-name">{{ item.name }}</span>
                        <span class="text-muted mt-1"></span>
                    </a>
                    <a v-else
                       class="mb-1 d-flex">
                        <span class="org-person-name">{{ item.name }}</span>
                    </a>
                </template>
            </div>
        </template>
        <template slot="footer">
            <button
                    class="btn btn-secondary mr-2"
                    data-dismiss="modal"
                    type="button"
                    @click.prevent="closeModal">
                {{ $t('close') }}
            </button>
        </template>
    </app-modal>
</template>

<script>
export default {
    name: "ViewAllPersonOrgModal",
    props: ["componentType", "rowData"],
    data(){
        return{
            route,
        }
    },
    methods:{
        adminAccess(item){
            return (window.user.roles[0].is_admin || window.user.roles[0].name == 'Manager')  || window.user.id == item.owner_id;
        },
        closeModal(value) {
            this.$emit('close-modal', value);
        },
    }
}
</script>
<style lang="scss" scoped>
.org-person-name{
    white-space: nowrap !important;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>