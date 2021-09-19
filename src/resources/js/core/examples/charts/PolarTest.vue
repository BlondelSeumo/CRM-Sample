<template>
    <div class="wrapper">
        <app-chart :type="'polar-chart'" :title="'Polar Chart'" :labels="labels" :data-sets="dataSets"/>
    </div>
</template>

<script>
    export default {
        name: "PolarTest",

        data() {
            return {
                labels: ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling', 'Running'],
                dataSets: [
                    {
                        label: 'My First dataset',
                        backgroundColor: '#4466F2',
                        pointBackgroundColor: 'rgba(179,181,198,1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(179,181,198,1)',
                        data: [65, 59, 76, 70, 56, 55, 40]
                    },
                    {
                        label: 'My Second dataset',
                        backgroundColor: '#6a008a',
                        pointBackgroundColor: 'rgba(255,99,132,1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(255,99,132,1)',
                        data: [28, 80, 40, 19, 96, 27, 100]
                    }
                ]
            }
        }
    }
</script>
