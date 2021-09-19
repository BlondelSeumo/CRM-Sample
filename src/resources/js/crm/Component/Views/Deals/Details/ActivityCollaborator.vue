<template>
    <div class="card border-0 card-with-shadow mb-primary">
        <div class="card-header d-flex align-items-center justify-content-between p-primary bg-transparent">
            <h5 class="card-title text-muted m-0">{{ $t('collaborators') }}</h5>
        </div>
        <div class="card-body">
            <template v-if="collaboratorsData.length">
                <template v-for="(collaborator, index) in collaboratorsData" v-if="index < 3">
                    <div class="media d-flex align-items-center mb-3">
                        <app-avatar
                            :img="collaborator[0].profile_picture
                                ? urlGenerator(collaborator[0].profile_picture.path)
                                : collaborator[0].profile_picture"
                            :title="collaborator[0].full_name"
                            avatar-class="avatars-w-40"
                            class="mr-2"/>

                        <div class="media-body">
                            {{ collaborator[0].full_name }}
                        </div>
                    </div>
                </template>
            </template>
            <template v-else>
                {{ $t('not_added_yet') }}
            </template>
            <div v-if="Object.keys(activityCollaborators).length > 3" class="d-flex justify-content-center">
                <button class="btn btn-secondary" data-dismiss="modal" type="button" @click.prevent="viewAll()">
                    {{ $t('view_all') }}
                </button>
            </div>
            <app-deal-collaborator
                v-if="viewAllCollaborator"
                :collaborator-url="collaboratorUrl"
                @close-modal="closedViewModal"
            />
        </div>
    </div>
</template>

<script>
import {urlGenerator} from "@app/Helpers/helpers";
export default {
    name: "ActivityCollaborator",
    props: ['activityCollaborators', 'collaboratorsDeal'],
    data() {
        return {
            urlGenerator,
            collaboratorUrl: '',
            viewAllCollaborator: false,
        }
    },
  computed:{
      //props data object to array
    collaboratorsData(){
      return  Object.values(this.activityCollaborators)
    }
  },
    created() {
        this.allCollaborators();
    },
    methods: {
        allCollaborators() {
            this.collaboratorUrl = `crm/deals/${this.collaboratorsDeal.id}/collaborators`;
        },
        viewAll() {
            this.viewAllCollaborator = true;
        },
        closedViewModal() {
            this.viewAllCollaborator = false;
        },
    }
}
</script>

