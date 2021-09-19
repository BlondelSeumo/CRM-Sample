<template>

    <div>
        <input type="text" v-bind:value="value" @input="input($event)">
    </div>

</template>


<script>

    export default {
        name: "TestInput",
        props: ['value'],
        data() {
            return {}
        },
        created() {
            // this.findMixin();

            // if (this.mixinInstance) {
            //     //set initial value
            //     this.mixinInstance.changed(this.$vnode.data.model.expression, this.value);
            // } else {
            //     console.log("You can't use this component without the FormMixin");
            // }
        },
        methods: {
            input($event) {
                this.$emit('input', $event.target.value); //for v-model use
                // this.mixinInstance.changed(this.$vnode.data.model.expression, $event.target.value);
            },
            findMixin() {
                let instance = this,
                    parent = instance;

                //we'll find upto 10 level of parent to get the mixin
                for (let i = 0; i < 10; i++) {
                    if (parent.mixinName === "formMixin") {
                        instance.mixinInstance = parent;
                        break;
                    }

                    parent = parent.$parent;
                }

            }
        }
    }
</script>
