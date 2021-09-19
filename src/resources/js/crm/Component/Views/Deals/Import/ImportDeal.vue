<template>
  <div class="content-wrapper">
    <app-breadcrumb
      :page-title="$t('import_deal')"
      :directory="[$t('deals'), $t('import_deal')]"
      :icon="'users'"
      :button="{ label: $t('back_to_deal') }"
    />

    <div class="card border-0 card-with-shadow">
      <form
        class="mb-0"
        ref="form"
        :data-url="route('deal.import-store')"
        enctype="multipart/form-data"
      >
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="note-title d-flex">
                <app-icon name="book-open" />
                <h6 class="card-title pl-2">{{ $t("csv_format_guideline") }}</h6>
              </div>
              <div class="note note-warning p-4 mb-5">
                <p class="my-1">- {{ $t("csv_guideline_1") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_2") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_3") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_4") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_6") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_8") }}</p>
                <p class="my-1">- {{ $t("deal_import_guideline") }}</p>
                <p class="my-1">
                  <b>- {{ $t("csv_guideline_7") }}</b>
                </p>
              </div>

              <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-3">
                {{ $t("csv_download_label") }}
                <app-input
                  type="checkbox"
                  :list="[{ id: 1, value: $t('include_custom_fields') }]"
                  v-model="sampleFileType"
                  :label-class="'mb-0'"
                  class="mx-sm-3"
                />

                <a href="#" @click.prevent="sampleDownload">{{ $t("download") }}</a>
              </div>

              <div class="form-group">
                <app-input v-model="files" :maxFiles="1" type="dropzone" />

                <div v-if="stat.errors > 0" class="mt-2">
                  <span class="text-secondary">
                    <app-icon class="mr-2" name="check-circle" stroke-width="1" />
                    <span class="text-success"> {{ stat.successfull }} </span>
                    successfull rows
                  </span>
                  <div class="progress mt-1 mb-1">
                    <div
                      :aria-valuenow="`${stat.successRate}`"
                      :style="`width: ${stat.successRate}%`"
                      aria-valuemax="100"
                      aria-valuemin="0"
                      class="progress-bar progress-bar-striped bg-success"
                      role="progressbar"
                    >
                      {{ stat.successRate }}
                      %
                    </div>
                  </div>
                  <span class="text-secondary">
                    <app-icon class="mr-2" name="x-circle" stroke-width="1" />
                    <span class="text-warning">{{ stat.unsuccessfull }}</span>
                    unsuccessfull rows <br />
                  </span>
                  <div class="progress mt-1 mb-1">
                    <div
                      :aria-valuenow="`${stat.unsuccessRate}`"
                      :style="`width: ${stat.unsuccessRate}%`"
                      aria-valuemax="100"
                      aria-valuemin="0"
                      class="progress-bar progress-bar-striped bg-danger"
                      role="progressbar"
                    >
                      {{ stat.unsuccessRate }}
                      %
                    </div>
                  </div>
                  <span class="text-secondary">
                    <app-icon class="mr-2" name="info" stroke-width="1" />
                    <span class="text-danger">{{ stat.errors }}</span>
                    {{ $t("errors_found") }}
                  </span>
                  <div class="progress mt-1 mb-1">
                    <div
                      :aria-valuenow="`${stat.errorRate}`"
                      :style="`width: ${stat.errorRate}%`"
                      aria-valuemax="100"
                      aria-valuemin="0"
                      class="progress-bar progress-bar-striped bg-secondary"
                      role="progressbar"
                    >
                      {{ stat.errorRate }}
                      %
                    </div>
                  </div>
                </div>

                <small v-if="errors.import_file" class="text-danger">
                  {{ errors.import_file[0] }}
                </small>

                <template v-if="errors.length > 0">
                  <div
                    class="alert alert-danger alert-dismissible fade show mt-3"
                    role="alert"
                  >
                    <h5 class="alert-heading">Error!</h5>
                    {{ errors.length }} required fields are missing
                    <hr />
                    Missing field :
                    <em
                      ><code>{{ errors.join(",") }}</code></em
                    >
                    <button
                      type="button"
                      class="close"
                      data-dismiss="alert"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </form>
      <div class="card-footer p-primary bg-transparent">
        <button type="button" class="btn btn-primary" @click.prevent="submitData">
          <span class="w-100">
            <app-submit-button-loader v-if="loading"></app-submit-button-loader>
          </span>
          <template v-if="!loading">{{ $t("save") }}</template>
        </button>
        <button
          type="button"
          class="btn btn-secondary mr-4"
          data-dismiss="modal"
          @click="cancel"
        >
          {{ $t("cancel") }}
        </button>
        <button
          v-if="stat.errors > 0"
          class="btn btn-warning btn-sm"
          href=""
          @click.prevent="importErrorOpen"
        >
          <app-icon name="download" class="mr-2" stroke-width="1" />{{
            $t("importReport.xlsx")
          }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { FormMixin } from "@core/mixins/form/FormMixin.js";
import { check } from "@app/Helpers/check";
import HelperMixin from "@app/Mixins/Global/HelperMixin";
import { urlGenerator } from "@app/Helpers/helpers";

export default {
  name: "DealImport",
  mixins: [FormMixin, HelperMixin],
  props: {
    validKeys: {
      type: Array,
    },
  },
  data() {
    return {
        route,
      files: [],
      errors: {},
      check,
      stat: {},
      loading: false,
      customFieldType: 0,
      sampleFileType: [],
    };
  },
  methods: {
    beforeSubmit() {
      this.loading = true;
    },
    submitData() {
      let formData = new FormData();
      if (this.files.length) {
        formData.append("import_file", this.files[0]);
      }
      this.save(formData, {
        responseType: "blob",
      });
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors ?? response.data;
    },
    afterSuccess(response) {
      this.loading = false;
      if (response.data.stat?.errors > 0) {
        if (response.data.stat.successfull > 0) {
          this.$toastr.i(response.data.message);
        }
        this.stat = response.data.stat;
      } else {
        this.$toastr.s(response.data.message);
        this.scrollToTop(false);
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
    },
    afterFinalResponse() {
      this.loading = false;
    },
    cancel() {
      location.reload();
    },
    sampleDownload() {
      let commas = "";
      let keys = [
        "title",
        "value",
        "pipeline_name",
        "status",
        "owner_email",
        "lead",
        "expired_at",
        "created_at",
        "updated_at",
      ];

      if (this.sampleFileType.length) {
        keys = [...this.validKeys];
        commas = ",".repeat(keys.slice(5).length);
      }

      this.downloadCSV(
        `${keys.join(",")}\n` +
          `Demo one deal,100,Demo One Pipeline,status_open,admin@demo.com,P:Godfather:Johnson & Jhonson, 2020-10-10, 2020-08-24 08:27:09, 2020-08-24 08:27:09${commas}\n` +
          `Demo two deal,200,Demo Two Pipeline,status_won,rosey_martin@pipex.com,O:D'Caprio:GainHQ, 2020-10-10, 2020-08-24 08:27:09, 2020-08-24 08:27:09${commas}\n` +
          `Demo three deal,300,Demo Three Pipeline,status_lost,jack_sparrow@pipex.com,O:James Bond:Rakuten, 2020-10-10, 2020-08-24 08:27:09, 2020-08-24 08:27:09${commas}\n`
      );
    },

    downloadCSV(csv) {
      let e = document.createElement("a");
      e.href = "data:text/csv;charset=utf-8," + encodeURI(csv);
      e.target = "_blank";
      e.download = `${this.$t("deals")}.csv`;
      e.click();
    },
    importErrorOpen() {
      var downloadLink = window.document.createElement("a");
      downloadLink.href = urlGenerator("/storage/importReport.xlsx");
      downloadLink.download = "Import Report.xlsx";
      // document.body.appendChild(downloadLink);
      downloadLink.click();
    },
  },
};
</script>
