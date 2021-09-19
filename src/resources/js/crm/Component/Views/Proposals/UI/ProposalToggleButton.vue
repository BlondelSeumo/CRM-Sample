<template>
  <div class="d-flex align-items-center">
    <template v-if="rowData.status.name == 'status_draft'">-</template>
    <template v-else>
        <div class="d-flex align-items-center">
          <label class="custom-control border-switch mb-0 mr-3">
            <input type="checkbox"
                   class="border-switch-control-input"
                   @change="statusChange($event)"
                   :checked='isChecked(rowData.status.name)'>
            <span class="border-switch-control-indicator"></span>
          </label>
        </div>
    </template>
  </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin";
import { api } from "@app/Helpers/api";
import { collect } from "@app/Helpers/Collection";
import moment from "moment";

export default {
  name: "ProposalToggleButton",
  mixins: [FormMixin],
  props: {
    rowData: {
      type: Object
    },
    tableId: {
      type: String
    }
  },
  data(){
    return{
       formData:{}
    }
  },
  
  methods:{
    isChecked(status){
      return status == "status_accepted" ? true : false
    },
    statusChange(event) {
      event.target.checked ? this.statusUpdate('status_accepted') :
          this.statusUpdate('status_sent');
    },

    statusUpdate(status){
      this.formData.subject = this.rowData.subject;
      this.formData.content = this.rowData.content;
      this.formData.status_id = this.rowData.status.id;
      this.formData.accepted_at = status == 'status_sent' ? null :moment().format('YYYY-MM-DD H:m:s');

      api
          .route("crm/statuses")
          .params({name: status, type: "proposal"})
          .get()
          .then((res) => {
            this.formData.status_id = collect(res).first().id;

            this.axiosPut({
              url: `crm/proposals/${this.rowData.id}`,
              data: this.formData,
            }).then((response) => {
              this.$toastr.s(response.data.message);
              this.$hub.$emit("reload-" + "send-proposal-modal");
            });
          });
    },
  }
}
</script>

