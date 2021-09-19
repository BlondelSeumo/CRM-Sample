<template>
    <div class="wrapper">
        <button @click="clickMe">click</button>
        <app-chart v-if="load" type="bar-chart" title="Bar Chart" :labels="labels" :data-sets="dataSets"/>
    </div>
</template>

<script>
    import coreLibrary from '../../helpers/CoreLibrary';

    export default {
        name: "ChartTest",
        extends: coreLibrary,
        data() {
            return {
                load: false,
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                dataSets: []
            }
        },
        created(){
            this.getData();
        },
        methods: {
            getData(){
                let instance = this;
                instance.load = false;
                this.axiosGet('get-test-chart').then(res =>{
                    instance.dataSets = res.data;
                    instance.load = true;
                }).catch(error =>{

                })
            },
            clickMe(){
                this.getData();
            }
        }
    }
</script>

