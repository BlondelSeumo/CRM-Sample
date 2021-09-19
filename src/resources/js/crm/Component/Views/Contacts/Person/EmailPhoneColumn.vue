<template>
    <div>
        <template v-for="(item, index) in emailPhoneData" v-if="index < 2">
            <div class="d-flex align-items-center">
                <span class="mb-1 badge badge-round badge-light">{{ item.value }}</span>
                <span :class="`ml-1 py-1 px-2 badge badge-round badge-${item.type.class}`" v-if="item.type">
                    {{ item.type.name }}
                    </span>
                <br>
            </div>

            <small class="cursor-pointer" @click.prevent="viewAll" v-if="emailPhoneData.length > 2 && index == 1">
                <b>+{{ emailPhoneData.length - 2 }} more</b>
            </small>
        </template>

        <all-email-phone
            v-if="isViewModalOpen"
            :row-data="emailPhoneData"
            @close-modal="closeModal"
        />
    </div>
</template>

<script>
import {collection} from "../../../../Helpers/helpers";
export default {
    name: "EmailColumn",
    props: {
        rowData: {
            type: Object,
            required: true
        },
        value: {
            type: Array,
        }
    },
    data() {
        return {
            route,
            isViewModalOpen: false,
        }
    },
    methods:{
        viewAll(){
            this.isViewModalOpen = true;
        },
        closeModal(){
            this.isViewModalOpen = false;
            $("#person-org-modal").modal("hide");
        },
    },
    computed: {
        emailPhoneData() {
            let mailList = this.value.length ? collection(this.value).get("value", "type") : [];
            return mailList;
        }
    }
}
</script>
