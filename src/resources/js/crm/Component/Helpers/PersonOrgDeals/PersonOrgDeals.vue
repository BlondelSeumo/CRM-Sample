<template>
  <div class="card border-0 card-with-shadow">
    <div class="card-header d-flex align-items-center justify-content-between p-primary bg-transparent">
      <h5 class="card-title text-muted m-0">{{ $t('deals') }} ({{ Deals.length }})</h5>
      <a href="#" class="text-muted" @click.prevent="commonDealModalOpen">
        <app-icon stroke-width="1" name="plus-square"/>
      </a>
    </div>
    <div class="card-body font-size-90">
      <div v-if="Deals.length" class="default-base-color rounded p-2">
        <div v-for="(deal, index) in Deals"
             :key="index" v-if="index < 3"
             class="card border-0 card-with-shadow shadow person-details-deal"
             :class="index===Deals.length-1 || index===2 ?'':'mb-2'">
          <a :href="urlGenerator(route('deal_details.page', {id: deal.id}))">
            <div class="card-body p-3">
              <div class="media d-flex align-items-center mb-3">
                <app-avatar :title="deal.title"
                            class="mr-2"
                            avatar-class="avatars-w-30"
                            :img=" deal.contextable ? deal.contextable.profile_picture ?
									urlGenerator(deal.contextable.profile_picture.path): urlGenerator(`/images/${componentType}.png`) : ''"
                />
                <div class="media-body">
                  {{deal.title}}
                </div>
              </div>
              <div class="text-muted d-flex align-items-center justify-content-between">
                <div class="media d-flex align-items-center" v-if="deal.owner">
                  <app-avatar class="mr-2"
                              :title="deal.owner.full_name"
                              avatar-class="avatars-w-30"
                              :img="deal.owner.profile_picture ?
                                                            urlGenerator(deal.owner.profile_picture.path) :
                                                            deal.owner.profile_picture"
                  />
                  <div class="media-body">
                    {{deal.owner.full_name}}
                  </div>
                </div>
                <span class="font-size-90"> {{numberWithCurrencySymbol(deal.value)}} </span>
              </div>
            </div>
          </a>
        </div>
        <div class="d-flex justify-content-center mt-2" v-if="Deals.length > 3">
          <button type="button" class="btn btn-secondary btn-block" @click.prevent="viewAll()">
            {{ $t('view_all') }}
          </button>
        </div>
      </div>
    </div>
    <app-deal-modal
      v-if="isCommonDealModal"
      :pre-selected-option="preSelected"
      :component-type="componentType"
      :selectedUrlId="Id"
      @close-modal="closeCommonDealModal"
    />

    <app-common-all-deals
      v-if="viewAllModal"
      :id="Id"
      :context-type="contextType"
      :table-id="'details-view-modal'"
      @close-modal="closedViewModal"
    />
  </div>
</template>

<script>

import {numberWithCurrencySymbol, urlGenerator} from "@app/Helpers/helpers";
import { collect } from "@app/Helpers/Collection";

export default {
  name: "PersonOrgDeals",
  props: {
    Deals:{
      required: true
    },
    Id:{
      required:true
    },
    quickView:{
      required: false
    },
    contactList: {
      required: false
    },
    componentType: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      preSelected: {},
      urlGenerator,
        route,
      isCommonDealModal: false,
      viewAllModal: false,
      numberWithCurrencySymbol
    }
  },
  computed:{
    contextType(){
      if(this.componentType == 'person'){
         return 'person'
      }else {
        return  'organization'
      }
    }
  },
  mounted() {
    if(this.componentType == 'person'){
      this.preSelected.person_id = this.Id;
    }else {
      this.preSelected.organization_id = this.Id;
    }
  },
  methods: {
    commonDealModalOpen() {
      this.isCommonDealModal = true;
      $('#deal-modal').modal('show');
    },
    closeCommonDealModal() {
      this.isCommonDealModal = false;
      $('#deal-modal').modal('hide');
    },
    viewAll(){
      this.quickView ? this.$emit("viewAllDeal", this.Id) : this.viewAllModal = true;
    },
    closedViewModal() {
      $('#details-view-modal').modal('hide');
      this.viewAllModal = false;
    },
  }
}
</script>


