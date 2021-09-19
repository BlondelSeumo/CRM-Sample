<template>
    <div class="wrapper">

        <app-chart :type="'scatter-chart'" :title="'Scatter Chart'" :data-sets="dataSets"/>

    </div>
</template>

<script>
    export default {
        name: "ScatterTest",
        data() {
            return {
                dataSets: [{
                    label: 'Scatter Dataset 1',
                    fill: false,
                    borderColor: '#46c35f',
                    backgroundColor: '#46c35f',
                    data: [{
                        x: -2,
                        y: 4
                    }, {
                        x: -1,
                        y: 1
                    }, {
                        x: 0,
                        y: 0
                    }, {
                        x: 1,
                        y: 1
                    }, {
                        x: 2,
                        y: 4
                    }]
                },
                    {
                        label: 'Scatter Dataset 2',
                        fill: false,
                        borderColor: '#4466F2',
                        backgroundColor: '#4466F2',
                        data: [{
                            x: -2,
                            y: -4
                        }, {
                            x: -1,
                            y: -1
                        }, {
                            x: 0,
                            y: 1
                        }, {
                            x: 1,
                            y: -1
                        }, {
                            x: 2,
                            y: -4
                        }]
                    }]
            }
        },
    }
</script>

