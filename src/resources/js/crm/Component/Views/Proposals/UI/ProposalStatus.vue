<template>
    <div>
        <div v-if="rowData.status.name == 'status_draft' || (!this.$can('manage_public_access') && this.$can('client_access'))">
            <span :class="`badge badge-pill badge-${rowData.status.class}`">
                {{ rowData.status.translated_name }}</span>
        </div>
        <div v-else class="dropdown d-inline-block btn-dropdown">
            <button
                :class="'btn dropdown-toggle ml-0 mr-2 py-1 px-2 btn-outline-' + rowData.status.class"
                aria-expanded="false"
                aria-haspopup="true"
                data-toggle="dropdown"
                type="button"
            >
                {{ rowData.status.translated_name }}
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item"
                   href="#"
                   @click.prevent="changeStatus(rowData, 'status_accepted')">
                    {{ $t('accepted') }}
                </a>
                <a class="dropdown-item" href="#"
                   @click.prevent="changeStatus(rowData, 'status_rejected')">{{ $t('status_rejected') }}
                </a>
                <a class="dropdown-item" href="#"
                   @click.prevent="changeStatus(rowData, 'status_no_reply')">
                    {{ $t('status_no_reply') }}
                </a>
            </div>
        </div>
    </div>

</template>
<script>

import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {api} from "../../../../Helpers/api";
import {collect} from "../../../../Helpers/Collection";
import moment from "moment";

export default {
    name: "ProposalStatus",
    mixins: [FormMixin],
    props: {
        rowData: {
            type: Object,
        },
        tableId: {
            type: String,
        },
    },
    data() {
        return {
            status_id: null,
            accepted_at: null
        }
    },
    methods: {
        changeStatus(rowData, status_type) {
            if (status_type == 'status_accepted'){
                this.accepted_at = moment().format('YYYY-MM-DD H:m:s');
            }
            this.axiosGet(
                route(`statuses.index`, {_query: { name: status_type, type: "proposal"}}))
                .then((res) => {
                    this.status_id = collect(res.data).first().id
                    this.statusUpdate(rowData, this.status_id);
                })
        },

        statusUpdate(rowData, status_id) {
            let formData = {
                'subject': rowData.subject,
                'content': rowData.content,
                'status_id': status_id,
            }
            if (this.accepted_at){
                formData.accepted_at = this.accepted_at;
            }
            this.submitFromFixin(`patch`, route('proposals.update', {id: rowData.id}), formData);
        },

        afterSuccess({data}) {
            this.$toastr.s(data.message);
            this.$hub.$emit("reload-" + this.tableId);
        },
    }
};
</script>
