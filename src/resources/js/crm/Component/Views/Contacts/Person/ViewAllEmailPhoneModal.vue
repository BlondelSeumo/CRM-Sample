<template>
    <app-modal modal-alignment="top" modal-id="person-org-modal" modal-size="default" @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('view_all') }}</h5>
            <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <div v-for="(item, index) in rowData">
                <div>
                    <span class="mb-1 badge badge-round badge-light">{{ item.value }}</span>
                    <span :class="`ml-1 py-1 px-2 badge badge-round badge-${item.type.class}`" v-if="item.type">
                    {{ item.type.name }}
                    </span>
                    <br>
                </div>
            </div>
        </template>
        <template slot="footer">
            <button
                class="btn btn-secondary mr-2"
                data-dismiss="modal"
                type="button"
                @click.prevent="closeModal">
                {{ $t('close') }}
            </button>
        </template>
    </app-modal>
</template>

<script>
export default {
    name: "ViewAllEmailPhoneModal",
    props: ["rowData"],
    data(){
        return{

        }
    },
    methods:{
        closeModal(value) {
            this.$emit('close-modal', value);
        },
    }
}
</script>
