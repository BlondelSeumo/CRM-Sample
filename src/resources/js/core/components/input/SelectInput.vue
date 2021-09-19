<template>
  <select
    :id="inputFieldId"
    :style="'backgroundImage: url(' + downArrow() + ')'"
    :disabled="data.disabled"
    ref="select"
    :class="'custom-select' + data.inputClass"
    v-bind:value="value"
    v-on="listeners"
  >
    <!-- placeholder -->
    <option disabled value="" hidden v-if="data.placeholder">
      {{ data.placeholder }}
    </option>
    <option
      v-for="(item, index) in data.list"
      :value="item.id"
      :disabled="item.disabled"
      :selected="item.id === value"
      :key="index"
    >
      {{ item[data.listValueField] }}
    </option>
  </select>
</template>

<script>
import { InputMixin } from "./mixin/InputMixin.js";
import AppFunction from "../../helpers/app/AppFunction";

export default {
  name: "SelectInput",
  mixins: [InputMixin],
  methods: {
    downArrow() {
      if (this.$store.state.theme.darkMode) {
        return AppFunction.getAppUrl("images/chevron-down-dark.svg");
      } else {
        return AppFunction.getAppUrl("images/chevron-down.svg");
      }
    },
  },
};
</script>
