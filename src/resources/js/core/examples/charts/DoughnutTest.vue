<template>
    <div class="wrapper">
        <app-chart :type="'dough-chart'" :title="'Doughnut Chart'" :labels="labels" :data-sets="dataSets"/>
    </div>
</template>

<script>
    export default {
        name: "DoughnutTest",

        data() {
            return {
                labels: ['VueJs', 'EmberJs', 'ReactJs', 'AngularJs'],
                dataSets: [
                    {
                        backgroundColor: [
                            '#f96868',
                            '#4466F2',
                            '#2e383e',
                            '#6a008a'
                        ],
                        data: [20, 25, 40, 15]
                    }
                ]
            }
        }
    }
</script>
