<template>
  <div>
    <!--    filter-->
    <div class="row">
      <div class="col-6 col-sm-8 col-md-9 col-lg-10 col-xl-10">
        <div class="filters-wrapper d-flex justify-content-start flex-wrap">
          <!--Open Filters Button For Mobile-->
          <button
            class="btn d-block d-sm-none btn-toggle-filters"
            type="button"
            @click.prevent="toggleFilters"
          >
            <app-icon :name="'filter'" />
            {{ $t("filters") }}
          </button>

          <span v-show="isFiltersOpen" class="mobile-filters-wrapper">
            <app-filter
              :filters="options.filters"
              @get-values="getFilterValues"
            />

            <button
              class="
                btn btn-primary btn-with-shadow
                d-sm-none
                btn-close-filter-wrapper
                d-flex
                justify-content-center
                align-items-center
              "
              type="button"
              @click="toggleFilters"
            >
              {{ $t("close") }}
            </button>
          </span>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2">
        <div class="mr-0 single-filter single-search-wrapper">
          <div
            class="form-group form-group-with-search d-flex align-items-center"
          >
            <app-search @input="getSearchValue" />
          </div>
        </div>
      </div>
    </div>

    <!--kanban view-->
    <div class="proposal-kanban-view card card-with-shadow border-0">
      <div class="card-body" style="padding: 0.5rem">
        <div
          v-if="dataLoaded"
          class="kanban-wrapper custom-scrollbar overflow-auto pl-0"
        >
          <div
            v-for="(status, index) in statusProperty"
            :key="index"
            class="kanban-column position-relative"
            v-if="
              !(
                status.name == 'Draft' &&
                !$can('manage_public_access') &&
                $can('client_access')
              )
            "
            :class="{ 'draft-bg-color': status.name == 'Draft' }"
          >
            <div class="py-3 stage-header rounded-top row">
              <div class="col-10">
                <h6 class="stage-name text-truncate">
                  {{ status.name }}
                </h6>
                <div
                  class="
                    text-muted
                    d-flex
                    flex-wrap
                    align-items-center
                    stage-information
                  "
                >
                  <span
                    >{{
                      proposalData[status.id]
                        ? proposalData[status.id].length
                        : 0
                    }}
                    proposals</span
                  >
                  <span v-if="proposalData[status.id].length">{{
                    numberWithCurrencySymbol(
                      totalDealsValues.length
                        ? collect(totalDealsValues).find(status.id)
                          ? collect(totalDealsValues).find(status.id).value
                          : ""
                        : ""
                    )
                  }}</span>
                  <span v-else>{{ numberWithCurrencySymbol(0) }}</span>
                </div>
              </div>
            </div>
            <draggable
              :options="{
                disabled:
                  status.name == 'Draft' || !$can('update_proposals')
                    ? true
                    : false,
              }"
              :data-stage="status.id"
              :list="proposalData[status.id]"
              :move="dragMove"
              animation="150"
              chosenClass="pipex-sortable-chosen"
              class="kanban-draggable-column"
              dragClass="pipex-sortable-drag"
              easing="cubic-bezier(1, 0, 0, 1)"
              forceFallback="true"
              ghostClass="pipex-sortable-ghost"
              group="deals"
              @change="dragChanged"
              @end="dragEnd"
            >
              <div
                v-for="(element, ix) in proposalData[status.id]"
                :key="ix"
                :data-id="element.id"
                :class="{
                  'cursor-default':
                    status.name === 'Draft' || !$can('update_proposals'),
                }"
                class="card card-with-shadow draggable-item mt-2 border-0"
              >
                <div class="card-body font-size-90">
                  <div class="row py-2">
                    <div class="col-10">
                      <div class="media d-flex align-items-start mb-3 p-1">
                        <a href="" @click.prevent="openPreviewModal(element)">
                          <div class="media-body">
                            {{ element.subject }}
                          </div>
                        </a>
                      </div>
                    </div>
                    <div class="col-2">
                      <div
                        class="dropdown options-dropdown d-inline-block"
                        v-if="
                          $can('create_proposals') ||
                          $can('update_proposals') ||
                          $can('delete_proposals')
                        "
                      >
                        <button
                          type="button"
                          class="btn-option btn"
                          data-toggle="dropdown"
                          :title="$t('actions')"
                        >
                          <app-icon name="more-vertical" />
                        </button>

                        <div
                          class="dropdown-menu dropdown-menu-right py-2 mt-1"
                        >
                          <a
                            v-if="
                              element.status.name == 'status_draft' &&
                              $can('create_proposals')
                            "
                            class="dropdown-item px-4 py-2 font-size-90"
                            @click.prevent="sendProposal(element)"
                            href="#"
                          >
                            {{ $t("send") }}
                          </a>
                          <a
                            v-if="$can('update_proposals')"
                            class="dropdown-item px-4 py-2 font-size-90"
                            @click.prevent="editProposal(element.id)"
                            href="#"
                          >
                            {{ $t("edit") }}
                          </a>
                          <a
                            v-if="$can('delete_proposals')"
                            class="dropdown-item px-4 py-2 font-size-90"
                            @click.prevent="deleteProposal(element.id)"
                            href="#"
                          >
                            {{ $t("delete") }}
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div
                    v-if="element.deal"
                    class="media d-flex align-items-start mb-3 p-1"
                  >
                    <div class="media-body">
                      {{ element.deal.title }}
                    </div>
                    <span class="font-size-90">{{
                      numberWithCurrencySymbol(element.deal.value)
                    }}</span>
                  </div>

                  <div
                    v-if="element.deal && element.deal.contact_person.length"
                    class="media d-flex align-items-start mb-3 p-1"
                  >
                    <app-avatar
                      :alt-text="$t('not_found')"
                      :img="
                        element.deal.contact_person[0].profile_picture
                          ? urlGenerator(
                              element.deal.contact_person[0].profile_picture
                                .path
                            )
                          : urlGenerator(`/images/person.png`)
                      "
                      avatar-class="avatars-w-20"
                      class="mr-2"
                    />
                    <div class="media-body">
                      {{ element.deal.contact_person[0].name }}
                    </div>
                  </div>

                  <template
                    v-if="
                      !(!$can('manage_public_access') && $can('client_access'))
                    "
                  >
                    <hr v-if="element.tags.length" class="mt-0" />
                    <div class="row">
                      <div
                        v-if="element.tags.length > 0"
                        class="
                          col-12
                          d-flex
                          tags-background
                          flex-wrap
                          justify-content-start
                        "
                      >
                        <span
                          v-for="(tag, index) in element.tags"
                          :key="index"
                          :style="{
                            'background-color': tag.color_code,
                          }"
                          class="
                            badge badge-sm badge-pill
                            text-capitalize
                            mr-1
                            mb-1
                            badge-text-truncate
                          "
                          >{{ tag.name }}
                        </span>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </draggable>
          </div>
        </div>
        <app-overlay-loader v-else />
      </div>
    </div>

    <app-confirmation-modal
      v-if="confirmationModalActive"
      modal-id="proposal-delete-modal"
      @confirmed="confirmed"
      @cancelled="cancelled"
    />

    <app-check-email-deliver
      v-if="isCheckMailModalActive"
      :header-title="$t('send_proposal')"
      @close-modal="closeModalCheckMail"
    />

    <app-send-proposal-modal
      v-if="isSendProposalModalActive"
      :table-id="tableId"
      :selected-url="selectUrl"
      :selected-deal="proposalDeal"
      @close-modal="closeModal"
    />

    <app-proposal-preview-modal
      :content="selectedProposalContent"
      :title="selectedProposalTitle"
      modalId="proposal-preview"
      @close="isPreviewModalActive = false"
      v-if="isPreviewModalActive"
    />
  </div>
</template>

<script>
import draggable from "vuedraggable";
import { FormMixin } from "../../../../core/mixins/form/FormMixin";
import { owner, proposalStatus, tag } from "@app/Mixins/Global/FilterMixins";
import { numberWithCurrencySymbol, urlGenerator } from "@app/Helpers/helpers";
import { collect } from "@app/Helpers/Collection";
import { mapGetters } from "vuex";
import moment from "moment";

export default {
  name: "KanbanView",
  components: { draggable },
  mixins: [FormMixin, owner, proposalStatus, tag],
  data() {
    return {
      numberWithCurrencySymbol,
      urlGenerator,
      collect,
      tableId: "proposal-kanban-view",
      formData: {},
      proposalCardMoved: false,
      confirmationModalActive: false,
      isCheckMailModalActive: false,
      isSendProposalModalActive: false,
      isPreviewModalActive: false,
      selectedProposalContent: {},
      selectedProposalTitle: "",
      proposalId: null,
      dataLoaded: false,
      totalDealsValues: [],
      selectUrl: "",
      searchValue: "",
      // for filter and search
      isFiltersOpen: true,
      proposalDeal: {},
      filterValues: {},
      options: {
        filters: [
          {
            title: this.$t("owner"),
            type: "checkbox",
            key: "owner_is",
            option: [],
          },
          {
            title: "Created date",
            type: "range-picker",
            key: "created_at",
            option: ["today", "thisMonth", "last7Days", "thisYear"],
          },
          {
            title: "Proposal have deal",
            type: "radio",
            key: "proposal_with_deal",
            header: {
              title: "Want to filter your list?",
              description:
                "You can filter your data table which are created based on deal",
            },
            option: [
              {
                id: 1,
                name: "Have deal",
              },
              {
                id: 2,
                name: "Don't have deal",
              },
            ],
            listValueField: "name",
          },
          {
            title: this.$t("tag"),
            type: "multi-select-filter",
            key: "tags",
            option: [],
            permission:
              !this.$can("manage_public_access") && this.$can("client_access")
                ? false
                : true,
          },
        ],
      },
      arrayNegotiationsStarted: [],
      proposalData: [],
      formattedProposalData: {},
      statusProperty: [],
      sentStatusId: null,
    };
  },
  computed: {
    ...mapGetters(["checkEmailDelivery"]),
  },
  methods: {
    openPreviewModal(proposal) {
      this.selectedProposalContent = proposal.content;
      this.selectedProposalTitle = proposal.subject;
      this.isPreviewModalActive = true;
    },
    editProposal(proposal) {
      let selectedUrl = route("proposals.edit", { id: proposal });
      window.open(selectedUrl, "_self");
    },
    deleteProposal(proposal) {
      this.proposalId = proposal;
      this.confirmationModalActive = true;
    },
    confirmed() {
      let url = route("proposals.destroy", { id: this.proposalId });
      this.axiosDelete(url)
        .then((response) => {
          this.$toastr.s(response.data.message);
          this.getFilterValues(this.filterValues);
        })
        .catch(({ error }) => {
          //trigger after error
        })
        .finally(() => {
          this.cancelled();
        });
    },
    cancelled() {
      this.confirmationModalActive = false;
    },
    sendProposal(proposal) {
      if (this.checkEmailDelivery != 1) {
        this.isCheckMailModalActive = true;
      } else {
        if (
          proposal.deal &&
          proposal.deal.contact_person.length &&
          proposal.deal.contact_person[0].email.length == 1
        ) {
          this.directSendProposal(proposal);
        } else {
          this.proposalDeal = proposal.deal ? proposal.deal : {};
          this.selectUrl = route("proposals.show", { id: proposal.id });
          this.openModal();
        }
      }
    },
    directSendProposal(proposal) {
      let proposalData = {};
      proposalData.url = route("proposals.send");
      proposalData.data = {
        custom_content: proposal.content,
        status_id: this.sentStatusId,
        deal_id: proposal.deal_id,
        id: proposal.id,
        subject: proposal.subject,
        email: collect(proposal.deal.contact_person).first().email[0].value,
      };
      this.axiosPost(proposalData)
        .then((response) => {
          this.$toastr.s(response.data.message);
          this.getFilterValues(this.filterValues);
        })
        .catch((error) => {
          this.$toastr.e(error.response.data.message);
        })
        .finally(() => {});
    },
    closeModalCheckMail() {
      this.isCheckMailModalActive = false;
      $("#check-email-modal").modal("hide");
    },

    openModal() {
      this.isSendProposalModalActive = true;
    },

    closeModal() {
      this.isSendProposalModalActive = false;
      $("#send-proposal-modal").modal("hide");
    },

    //Drag and drop
    dragEnd(evt) {
      if (this.proposalCardMoved) {
        this.formData.status_id = evt.to.dataset.stage;
        let currentProposal = this.proposalData[this.formData.status_id].find(
          (el) => el.id == this.formData.id
        );
        this.formData.subject = currentProposal.subject;
        this.formData.content = currentProposal.content;
        if (evt.to.dataset.stage == 8) {
          this.formData.accepted_at = moment().format("YYYY-MM-DD H:m:s");
        }
        this.axiosPatch({
          url: route("proposals.update", { id: this.formData.id }),
          data: this.formData,
        })
          .then((res) => {
            this.proposalCardMoved = false;
          })
          .catch((er) => console.log(er));
        setTimeout(() => {
          this.setTotalDealsValues();
        }, 100);
      }
    },

    dragMove(evt) {},

    dragChanged(evt) {
      if (evt.removed) {
        this.formData.id = evt.removed.element.id;
        this.proposalCardMoved = true;
      }
    },

    // for filter and search
    getFilterValues(value) {
      this.dataLoaded = false;
      this.filterValues = value;
      this.filterValues.search = this.searchValue;

      this.axiosGet(
        route("proposals.index", {
          _query: { all: true, ...this.filterValues },
        })
      )
        .then((res) => {
          this.proposalData = res.data;
          this.setTotalDealsValues();
        })
        .catch((er) => console.log(er))
        .finally(() => {});
    },
    toggleFilters() {
      this.isFiltersOpen = !this.isFiltersOpen;
    },
    getSearchValue(value) {
      this.searchValue = value;
      this.filterValues.search = this.searchValue;
      this.getFilterValues(this.filterValues);
    },
    isFiltersActive() {
      this.isFiltersOpen = window.innerWidth > 575;
    },
    async getStatusProperty() {
      return this.axiosGet(route("statuses.index"))
        .then((res) => {
          let rawData = res.data;
          let draft_status = rawData.find((el) => el.name == "status_draft"),
            sent_status = rawData.find((el) => el.name == "status_sent"),
            other_status = rawData.filter(
              (el) => el.name !== "status_draft" && el.name !== "status_sent"
            );
          this.sentStatusId = sent_status.id;
          rawData = [draft_status, sent_status, ...other_status];
          this.statusProperty = collect(rawData)
            .where("type", "=", "proposal")
            .get()
            .map((item) => {
              return {
                id: item.id,
                name: item.translated_name,
              };
            });
        })
        .catch((er) => console.log(er))
        .finally(() => {});
    },
    getProposalData() {
      this.axiosGet(route("proposals.index", { _query: { all: true } }))
        .then((res) => {
          this.proposalData = res.data;
          this.setTotalDealsValues();
        })
        .catch((er) => console.log(er))
        .finally(() => {});
    },

    setTotalDealsValues() {
      let totalDealsValues = [];
      let totalDealsValue = 0;
      for (let key in this.proposalData) {
        if (this.proposalData[key].length > 0) {
          this.proposalData[key].forEach((element, i) => {
            if (element.deal) {
              totalDealsValue =
                Number(totalDealsValue) +
                (element.deal.value ? Number(element.deal.value) : Number(0));
              if (i + 1 == this.proposalData[key].length) {
                let totalValue = {
                  id: Number(key),
                  value: Number(totalDealsValue),
                };
                totalDealsValues.push(totalValue);
                totalDealsValue = 0;
              }
            }
          });
        } else {
          totalDealsValue = 0;
          let totalValue = {
            id: Number(key),
            value: Number(totalDealsValue),
          };
          totalDealsValues.push(totalValue);
        }
      }
      this.totalDealsValues = totalDealsValues;
      this.dataLoaded = true;
    },
  },
  watch: {
    "tags.length": {
      handler: function (length) {
        this.options.filters.find(({ key }, index) => {
          if (key === "tags") {
            this.options.filters[index].option = [...this.tags];
          }
        });
      },
      immediate: true,
    },
    proposalData: {
      handler: function (data) {
        this.statusProperty.forEach((status) => {
          if (data[status.id] == undefined) {
            data[status.id] = [];
          }
        });
      },
    },

    // dynamic height for kanban view.
    statusProperty: function () {
      $(".main-panel").css({
        minHeight: window.innerHeight + "px",
      });
      $(".kanban-view").css({
        paddingBottom: 0,
      });
      $(".kanban-view .kanban-draggable-column").css({
        height: window.innerHeight - 290 + "px",
      });
    },
  },
  mounted() {
    this.$store.dispatch("checkEmailDelivery");
    this.getStatusProperty().then(() => this.getProposalData());

    this.isFiltersActive();
    window.onresize = () => {
      this.isFiltersActive();
      $(".kanban-view .kanban-draggable-column").css({
        height: window.innerHeight - 290 + "px",
      });
    };
  },
};
</script>

<style lang="scss" scoped>
[theme="dark"] {
  .kanban-wrapper .kanban-column {
    background: #{darken(#2b303b, 7%)};
  }

  a {
    color: #{lighten(#4466f2, 20%)};
  }

  .deal-value {
    color: #6cabb9;
  }

  .tags-background {
    background: #2b303b;
  }

  .draft-bg-color {
    background-color: rgba(255, 204, 23, 0.09) !important;
  }
}
</style>
