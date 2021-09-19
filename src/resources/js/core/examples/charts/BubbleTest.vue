<template>
    <div class="content-wrapper">
        <div class="card card-with-shadow border-0">
            <div class="card-body">
                <app-chart :type="'bubble-chart'" :title="'Bubble Chart'" :data-sets="dataSets"/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "BubbleTest",

        data() {
            return {
                dataSets: [
                    {
                        label: 'Data One',
                        backgroundColor: '#4466F2',
                        data: [
                            {
                                x: 20,
                                y: 25,
                                r: 5
                            },
                            {
                                x: 40,
                                y: 10,
                                r: 15
                            },
                            {
                                x: 30,
                                y: 22,
                                r: 30
                            }
                        ]
                    },
                    {
                        label: 'Data Two',
                        backgroundColor: '#8A1700',
                        data: [
                            {
                                x: 10,
                                y: 30,
                                r: 15
                            },
                            {
                                x: 20,
                                y: 20,
                                r: 10
                            },
                            {
                                x: 15,
                                y: 16,
                                r: 30
                            }
                        ]
                    }
                ]
            }
        }
    }
</script>
