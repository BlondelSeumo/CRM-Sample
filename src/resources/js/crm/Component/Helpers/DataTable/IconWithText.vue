<template>
  <div class="d-flex align-items-center" :key="iconName" v-if="rowData.contextable != null">
    <app-icon :name="iconName"/>
    <p class="mb-0 ml-1">{{value.name}}</p>
  </div>
  <div v-else class="d-flex align-items-center ml-1 font-size-90 text-secondary">
    {{ $t('deal_has_no_lead')}}
  </div>
</template>

<script>
export default {
  name: "IconWithText",
  props: ['rowData', 'value', 'tableId'],
  computed: {
    iconName() {
      if (this.rowData.contextable != null){
        return this.rowData.contextable_type === 'App\\Models\\CRM\\Organization\\Organization' ? 'briefcase' : 'user'
      }
    }
  }
}
</script>
