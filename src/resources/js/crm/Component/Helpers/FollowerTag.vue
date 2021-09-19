<template>
  <div class="tags-type-column">
    <template v-if="this.rowData.tags.length > 0">
                    <span v-for="(tag,index) in this.rowData.tags"
                          :key='index'
                          class="badge badge-text-truncate badge-sm badge-pill text-capitalize mr-1 mb-1"
                          :style="'background-color:' + tag.color_code ">{{ tag.name }}
                    </span>
    </template>
  </div>
</template>

<script>
export default {
  name: "FollowerTag",
  props: {
    rowData: {
      type: Object
    },
  },
}
</script>

<style scoped>

</style>