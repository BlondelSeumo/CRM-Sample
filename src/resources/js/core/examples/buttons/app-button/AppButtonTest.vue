<template>
    <app-button :label="label" :class-name="className" :is-disabled="isDisabled" @submit="submit"/>
</template>

<script>
    export default {
        name: "AppButtonTest",
        data() {
            return{
                label: 'Success',
                className: 'btn btn-success',
                isDisabled: false,
            }
        },
        methods: {
            submit(value){
            	console.log(value);
            }
        }
    }
</script>
