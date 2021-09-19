<template>
    <app-modal
        modal-alignment="top"
        modal-id="details-view-modal"
        modal-body-class="p-0"
        modal-size="full-width"
        @close-modal="closeModal"
    >
        <template slot="header">
            <h4>{{ $t("all_deals") }}</h4>
            <button
                aria-label="Close"
                class="close outline-none"
                data-dismiss="modal"
                type="button"
            >
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
            </button>
        </template>
        <template slot="body">
            <app-table :id="'deals-common-table'" :options="options"/>
        </template>
        <template slot="footer">
            <button
                class="btn btn-secondary mr-2"
                data-dismiss="modal"
                type="button"
                @click.prevent="closeModal"
            >
                {{ $t("close") }}
            </button>
        </template>
    </app-modal>
</template>

<script>
import {numberWithCurrencySymbol} from "@app/Helpers/helpers";
import moment from "moment";

export default {
    name: "ViewAllOrgDealModal",
    props: ["id", "contextType"],
    data() {
        return {
            numberWithCurrencySymbol,
            options: {
                tableShadow: false,
                url: route('deal.person_org', {id: this.id, _query: {context: this.contextType}}),
                showHeader: true,
                columns: [
                    {
                        title: this.$t("title"),
                        type: "link",
                        key: "title",
                        sortAble: true,
                        isVisible: true,
                        modifier: (value, row) => {
                            return route('deal_details.page', {'id': row.id});
                        },
                    },
                    {
                        title: this.$t("status"),
                        type: "custom-html",
                        key: "status",
                        modifier: (value) => {
                            return `<span class="badge badge-pill badge-${
                                value.class ?? "secondary"
                            }">${value.translated_name}</span>`;
                        },
                    },
                    {
                        title: this.$t("deal_value"),
                        type: "custom-html",
                        key: "value",
                        modifier: (value) => {
                            return `<p class="mb-0 d-flex align-items-center text-nowrap">${numberWithCurrencySymbol(
                                value
                            )}</p>`;
                        },
                    },
                    {
                        title: this.$t("lead"),
                        type: "component",
                        componentName: "icon-with-text",
                        key: "contextable",
                    },
                    {
                        title: this.$t("contact_person"),
                        type: "custom-html",
                        key: "contact_person",
                        modifier: (value, row) => {
                            return value.length
                                ? value[0].name
                                : `<p class="m-0 font-size-90 text-secondary">` +
                                this.$t("deal_has_no_contact_person") +
                                `</p>`;
                        },
                    },
                    {
                        title: this.$t("next_activity"),
                        type: "custom-html",
                        key: "next_activity",
                        modifier: (value, row) => {
                            return value
                                ? moment(value.started_at).format("DD-MMM") +
                                " (" +
                                value.activity_type.name +
                                ")"
                                : "-";
                        },
                    },
                    {
                        title: this.$t("owner"),
                        type: "custom-html",
                        key: "owner",
                        modifier: (value, row) => {
                            return value
                                ? value.full_name
                                : `<p class="font-size-80 text-secondary">` +
                                this.$t("deal_has_no_owner") +
                                `</p>`;
                        },
                    },
                    {
                        title: this.$t("tags"),
                        type: "component",
                        key: "tags",
                        isVisible: true,
                        componentName: "tags-type-column",
                    },
                ],
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
        closeModal(value) {
            this.$emit("close-modal", value);
        },
    },
};
</script>
