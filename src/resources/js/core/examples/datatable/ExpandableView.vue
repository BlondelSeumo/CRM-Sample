<template>
    <div class="p-primary">
        <h4 class="text-success"> This is expandable column component</h4>
        <p>{{data.expandable_data}}</p>
        <button class="btn btn-danger" type="button" @click.prevent="consoleSomething">HI</button>
    </div>
</template>

<script>
    export default {
        name: "ExpandableView",
        props:['tableID','data'],
        methods:{
            consoleSomething(){
                console.log('HI');
            }
        }
    }
</script>
