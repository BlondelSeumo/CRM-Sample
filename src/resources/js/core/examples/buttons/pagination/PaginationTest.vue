<template>
    <diV class="content-wrapper" style="height: 350px !important;">
        <app-pagination :total-row="totalRow" :row-limit="rowLimit"/>
    </diV>
</template>

<script>
    export default {
        name: "PaginationTest",
        data() {
            return {
                totalRow: 100,
                rowLimit: 10
            }
        }
    }
</script>
