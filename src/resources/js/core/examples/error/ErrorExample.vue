<template>
    <app-error :error-type="'500'"
               :error-title="'Bad request'"
               :error-description="'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries.'"/>
</template>

<script>
    export default {
        name: "ErrorExample"
    }
</script>
