<template>
    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-primary" v-if="data.length > 0"
             v-for="(deal, rowIndex) in data" :key="'row'+rowIndex">
            <app-deal-card
                :table-id="id"
                :deal="deal"
                :pipelines="pipelineList"
                :stages="stageList"
                :actions="actions"
                @action-deal-modal="getAction"
            />
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: "DealsGridView",
    props: ['id', 'data', 'actions'],
    computed:{
        ...mapGetters({
            pipelineList: "getPipeline",
            stageList: "getStages",
        }),
    },
    mounted() {
        this.$store.dispatch("getPipeline");
        this.$store.dispatch("getStages");
    },
    methods: {
        getAction(row, action, active) {
            this.$emit('getCardAction', row, action, active);
        }
    }
}
</script>
