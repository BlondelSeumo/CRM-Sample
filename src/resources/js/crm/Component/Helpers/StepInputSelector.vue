<template>
  <div class="step-input-selector d-flex justify-content-around">
    <div
      class="step-input d-flex align-items-center justify-content-center"
      data-toggle="tooltip"
      :title="list.name"
      data-placement="top"
      :class="[
        { selected: index < doneIndex },
        { current: index == doneIndex - 1 },
        { 'mr-1': index < stepLists.length - 1 },
        { once: list['label'] },
      ]"
      v-for="(list, index) in stepLists"
      :key="index"
      @click.prevent="addSelectedSteps(index)"
      :disabled="disabled"
    >
      <span v-show="showLabel" class="text-truncate">{{
        index < doneIndex ? list.label : list["label"] ? list.label : list.name
      }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: "StepInputSelector",
  props: {
    showLabel: {
      type: Boolean,
      default: false,
    },
    stepLists: {
      type: Array,
    },
    stepComplete: {
      type: Number,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      doneIndex: -1,
    };
  },
  mounted() {
    setTimeout(function () {
      $('[data-toggle="tooltip"]').tooltip();
    }, 6000);
    this.doneIndex =
      this.$props.stepComplete != undefined
        ? this.$props.stepComplete + 1
        : this.doneIndex;
    //for default first stage selected
    if(this.$props.stepComplete == undefined){
      this.addSelectedSteps(0);
    }
  },
  methods: {
    addSelectedSteps(index) {
      if (index + 1 != this.doneIndex && !this.disabled) {
        this.doneIndex = index + 1;
        this.$emit("stepChanges", index);
      }
    },
  },
};
</script>
<style scoped>
.step-input{
  overflow:hidden;
}
.step-input > span{
  text-indent:0.95em;
}
.selected {
  background: blue;
  color: #fff;
}
.once {
  background: #d2d2d2;
  color: #ababab;
}
</style>
