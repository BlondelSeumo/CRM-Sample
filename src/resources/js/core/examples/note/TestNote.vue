<template>
    <div class="content-wrapper">
        <app-note :title="title" :notes="notes"/>
    </div>
</template>

<script>
export default {
    name: "TestNote",
    data() {
        return {
            title: 'First Note',
            notes: [' - Lorem Ipsum has been the industries standard dummy text ever since the 1500s. Lorem Ipsum has been the industries standard dummy text ever since the 1500s.'],
        }
    },

}
</script>
