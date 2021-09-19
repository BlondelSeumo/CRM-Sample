<template>
  <div class="content-wrapper">
    <app-breadcrumb
        :page-title="$t('import_pipeline')"
        :directory="[$t('pipelines'), $t('import_pipeline')]"
        :icon="'users'"
        :button="{label: $t('back_to_pipeline'), url:urlGenerator('/crm/pipelines/list/view')}"
    />

    <div class="card border-0 card-with-shadow">
      <form class="mb-0" ref="form" data-url="crm/pipeline-import" enctype="multipart/form-data">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="note-title d-flex">
                <app-icon name="book-open"/>
                <h6 class="card-title pl-2">{{ $t('csv_format_guideline') }}</h6>
              </div>
              <div class="note note-warning p-4 mb-5">
                <p class="my-1">- {{ $t('csv_guideline_1') }}</p>
                <p class="my-1">- {{ $t('csv_guideline_2') }}</p>
                <p class="my-1">- {{ $t('csv_guideline_3') }}</p>
<!--                <p class="my-1">- {{ $t('csv_guideline_4') }}</p>-->
              </div>

              <div class="mt-4">
                <span class="my-2">{{ $t('csv_download_label') }}</span>
                <a href="#" @click.prevent="sampleDownload">{{ $t('download') }}</a>
                <div class="mt-3">
                  <app-input type="dropzone" v-model="files" :maxFiles="1"/>
                  <template v-if="check(errors).isArray()">
                    <small v-for="error in errors" class="text-danger">
                      <template v-for="er in error">
                        {{ er }}
                        <br/>
                      </template>
                    </small>
                  </template>

                  <template v-else>
                    <small class="text-danger" v-if="errors.import_file">{{ errors.import_file[0] }}</small>
                  </template>
                </div>
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
          <template v-if="!loading">{{ $t('save') }}</template>
        </button>
        <button type="button" class="btn btn-secondary mr-4" data-dismiss="modal" @click="closeModal"
        >{{ $t('cancel') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";
import {check} from "@app/Helpers/check";
import HelperMixin from "@app/Mixins/Global/HelperMixin";
import {urlGenerator} from "@app/Helpers/helpers";

export default {
  name: "PipelineImport",
  mixins: [FormMixin, HelperMixin],
  data() {
    return {
      files: [],
      errors: [],
      check,
      urlGenerator,
      loading: false,
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
      this.save(formData);
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors;
    },
    afterSuccess(response) {
      this.loading = false;
      this.$toastr.s(response.data.message);
      this.scrollToTop(false);
      setTimeout(() => {
        location.reload()
      }, 1000)
    },
    closeModal() {
      location.reload();
    },
    sampleDownload() {
      let commas = "";
      let keys = ["name"];

      commas = ",".repeat(keys.slice(5).length);

      this.downloadCSV(
          `${keys.join(",")}\n` +
          `Demo One Pipeline${commas}\n` +
          `Demo Two Pipeline${commas}\n` +
          `Demo Three Pipeline${commas}\n`
      );
    },
    downloadCSV(csv) {
      let e = document.createElement("a");
      e.href = "data:text/csv;charset=utf-8," + encodeURI(csv);
      e.target = "_blank";
      e.download = `${this.$t("pipelines")}.csv`;
      e.click();
    },
  },
};
</script>