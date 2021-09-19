<template>
    <div class="content-wrapper">
        <div class="card card-with-shadow border-0">
            <div class="card-body">
                <app-chart :type="'custom-line-chart'" :labels="labels" :data-sets="dataSets"/>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CustomLineTest",
        data() {
            return {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                dataSets: [
                    {
                        borderWidth: 2,
                        borderColor: 'red',
                        backgroundColor: 'transparent',
                        data: [500, 0, 450, 250, 650, 400, 700]
                    },
                    {
                        borderWidth: 2,
                        borderColor: 'blue',
                        backgroundColor: 'transparent',
                        data: [0, 500, 250, 450, 350, 650, 200]
                    },
                    {
                        borderWidth: 2,
                        borderColor: 'blue',
                        backgroundColor: 'transparent',
                        data: [0, 500, 250, 450, 350, 650, 200]
                    }
                ]
            }
        },
    }
</script>

