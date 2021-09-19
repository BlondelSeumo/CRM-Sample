<template>
    <div class="wrapper">

        <app-chart type="line-chart" title="Line Chart" :labels="labels" :data-sets="dataSets"/>

    </div>
</template>

<script>
    export default {
        name: "LineTest",
        data() {
            return {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                dataSets: [
                    {
                        label: 'Data One',
                        backgroundColor: 'rgba(106, 0, 138, 0.5)',
                        data: [40, 39, 10, 40, 39, 80, 40]
                    },
                    {
                        label: 'Data Two',
                        backgroundColor: 'rgba(68, 102, 242, 0.5)',
                        data: [60, 45, 35, 20, 65, 30, 70]
                    },
                ]
            }
        },
    }
</script>

