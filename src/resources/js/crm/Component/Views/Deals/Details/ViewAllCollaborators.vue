<template>
    <app-modal modal-id="details-view-modal"
               modal-size="full-width"
               modal-alignment="top"
               modal-body-class="p-0"
               @close-modal="closeModal">
        <template slot="header">
            <h4>{{ $t('all_collaborators') }}</h4>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">

            <app-table :id="'test-table'" :options="options" @action="getAction"/>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('close') }}
            </button>
        </template>
    </app-modal>
</template>

<script>

export default {
    name: "ViewAllCollaborators",
    props: {
        collaboratorUrl:{
            required:true,
            type:String
        }
    },
    data() {
        return {
            options: {
                name: 'collaboratorTable',
                url: this.collaboratorUrl,
                showHeader: true,
                tableShadow: false,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'full_name',
                    },
                    {
                        title: this.$t('email'),
                        type: 'text',
                        key: 'email',
                    },
                ],
                datatableWrapper:false,
                showFilter: false,
                showSearch: false,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
            }
        }
    },
    methods: {
        getAction() {

        },
        closeModal(value) {
            this.$emit('close-modal', value);
        },
    }
}
</script>
