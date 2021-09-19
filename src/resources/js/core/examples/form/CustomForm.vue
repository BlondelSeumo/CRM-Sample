<template>
  <div class="container" style="min-height:100vh">
    <form ref="form" data-url action method>
      <app-modal name="sign-up" :modal-center="true">
        <template #header>
          <h5 class="modal-title">Sign Up</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </template>
        <template #body>
          <form>
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input
                type="email"
                class="form-control"
                id="exampleInputEmail1"
                aria-describedby="emailHelp"
                placeholder="Enter email"
              />
              <small id="emailHelp" class="form-text text-muted">
                We'll never share your email with anyone
                else.
              </small>
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <input
                type="password"
                class="form-control"
                id="exampleInputPassword1"
                placeholder="Password"
              />
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1" />
              <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
          </form>
        </template>
        <template #footer>
          <button type="button" class="btn btn-default mr-2" data-dismiss="modal">cancel</button>
          <button type="button" class="btn btn-success">log in</button>
        </template>
      </app-modal>
      <app-modal name="Description">
        Appropriately exploit professional infrastructures rather than
        unique growth strategies. Assertively build leveraged growth
        strategies vis-a-vis multimedia based vortals. Progressively
        simplify cross-platform value through interactive imperatives.
        Objectively implement enabled web services after plug-and-play ROI.
        Distinctively impact inexpensive schemas before ins. exploit
        professional infrastructures rather than unique growth strategies.
        Assertively build leveraged growth
      </app-modal>

      <app-input
        name="time"
        type="time"
        :field-props="fieldStatus"
        :time-format="24"
        @changed="changed"
      />
      <button type="submit" @click.prevent="save">submit</button>
    </form>
  </div>
</template>

<script>
import { FormMixin } from "../../mixins/form/FormMixin.js";
import DateRangePicker from "../date-range-picker/DateRangePicker";

export default {
  name: "CustomForm",
  mixins: [FormMixin],
  components: {
    DateRangePicker
  },
  methods: {
    //hooks
    beforeSubmit(fields) {
      return {
        fields: fields
      };
    },
    afterSubmit() {},

    afterSuccess(res) {
      console.log(res);
    },

    afterError(res) {
      console.log(res);
    },

    //
    getValue(v) {
      console.log(v);
    }
  }
};
</script>
