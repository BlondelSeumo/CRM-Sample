<template>
    <app-badge :label="label" :className="className"/>
</template>

<script>
    export default {
        name: "TestBadge",
        data() {
            return {
                label: 'Success',
                className: 'badge-success',
            }
        },

    }
</script>

<style scoped>

</style>