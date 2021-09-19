<template>
    <div class="wrapper">

        <app-chart :type="'horizontal-line-chart'" :title="'Horizontal Line Chart'" :labels="labels" :data-sets="dataSets"/>

    </div>
</template>

<script>
    export default {
        name: "HorizontalLineTest",
        data() {
            return {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                dataSets: [
                    {
                        label: 'Data One',
                        backgroundColor: '#4466F2',
                        data: [40, 20, 52, 39, 30, 60, 39, 80, 45, 30, 22, 15]
                    }
                ]
            }
        },
    }
</script>

