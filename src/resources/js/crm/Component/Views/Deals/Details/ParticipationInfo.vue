<template>
  <div class="card border-0 card-with-shadow mb-primary">
    <div class="card-header d-flex align-items-center justify-content-between p-primary bg-transparent">
      <h5  class="card-title text-muted m-0">{{ $t('participants') }}({{ participantData.length }})</h5>
    </div>
    <div class="card-body">
           <template v-if="participantData.length">
          <template v-for="(participant, index) in participantData" v-if="index < 3">
            <div class="media d-flex align-items-center mb-3">
              <app-avatar
                  :title="participant[0].name"
                  class="mr-2"
                  avatar-class="avatars-w-40"
                  :img="participant[0].profile_picture
                                ? urlGenerator(participant[0].profile_picture.path)
                                : participant[0].profile_picture"/>
              <div class="media-body">
                {{participant[0].name}}
              </div>
            </div>
          </template>
           </template>
      <template v-else>
        {{$t('not_added_yet')}}
      </template>
      <div class="d-flex justify-content-center" v-if="Object.keys(activityParticipations).length > 3">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click.prevent="viewAll()">
          {{ $t('view_all') }}
        </button>
      </div>
      <app-deal-participant
          v-if="viewAllParticipants"
          :participant-url="participantUrl"
          @close-modal="closedViewModal"
      />
    </div>
  </div>
</template>

<script>
    import {urlGenerator} from "@app/Helpers/helpers";
    export default {
        name: "ParticipationInfo",
        props: ['activityParticipations', 'participantsDeal'],
      data(){
          return{
            urlGenerator,
            participantUrl: '',
            viewAllParticipants: false,
          }
      },
      computed:{
        //props data object to array
          participantData(){
            return  Object.values(this.activityParticipations)
          }
      },
      created() {
        this.allParticipants();
      },
      methods:{
        allParticipants() {
          this.participantUrl = `crm/deals/${this.participantsDeal.id}/participants`;
        },
        viewAll() {
          this.viewAllParticipants = true;
        },
        closedViewModal() {
          this.viewAllParticipants = false;
        },
      }
    }

</script>
