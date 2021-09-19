<template>
  <app-modal
    modal-id="details-view-modal"
    modal-size="full-width"
    modal-alignment="top"
    modal-body-class="p-0"
    @close-modal="closeModal"
  >
    <template slot="header">
      <h4 class="ml-4">{{ $t("all_followers") }}</h4>
      <button
        type="button"
        class="close outline-none"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <div class="p-3">
        <app-table :id="'follower-common-table'" :options="options" @action="getAction" />
      </div>
    </template>
    <template slot="footer">
      <button
        type="button"
        class="btn btn-secondary mr-2"
        data-dismiss="modal"
        @click.prevent="closeModal"
      >
        {{ $t("close") }}
      </button>
    </template>
  </app-modal>
</template>

<script>
export default {
  name: "ViewAllFollowerModal",
  props: {
    followerData: {
      required: true,
      type: String,
    },
  },
  data() {
    return {
      options: {
        name: "followerTable",
        url: this.followerData,
        showHeader: true,
        tableShadow: false,
        columns: [
          {
            title: this.$t("name"),
            type: "text",
            key: "name",
          },
          {
            title: this.$t("type"),
            type: "custom-html",
            key: "contact_type",
            modifier: (value, row) => {
              return `<span class="badge badge-pill badge-${
                value.class ?? "secondary"
              }">${value.name}</span>`;
            },
          },
          {
            title: this.$t("closed_deal"),
            type: "text",
            key: "close_deals_count",
          },
          {
            title: this.$t("opened_deal"),
            type: "text",
            key: "open_deals_count",
          },
          {
            title: this.$t("owner"),
            type: "object",
            key: "owner",
            modifier: (value, row) => {
              return value ? value.full_name : "";
            },
          },
          {
            title: this.$t("tags"),
            type: "component",
            key: "tags",
            componentName: "tags-followers",
          },
          // {
          //   title: this.$t('tags'),
          //   type: 'object',
          //   key: 'tags',
          //   modifier: function (tags) {
          //
          //     return tags.length ? `${collection(tags).pluck('name').join(', ')}` : ' ';
          //   }
          // },
        ],
        // datatableWrapper:false,
        showFilter: false,
        showSearch: false,
        paginationType: "pagination",
        responsive: true,
        rowLimit: 10,
        orderBy: "desc",
      },
    };
  },
  methods: {
    getAction() {},
    closeModal(value) {
      this.$emit("close-modal", value);
    },
  },
};
</script>
