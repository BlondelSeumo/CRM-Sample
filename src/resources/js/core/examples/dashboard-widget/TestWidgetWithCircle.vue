<template>
    <app-widget type="app-widget-with-circle" :label="label" :number="number" />
</template>

<script>
    export default {
        name: "TestWidgetWithCircle",
        data() {
            return {
            	label: 'Delivary Rate',
            	number: 75,
        	}
    	}
    }
</script>
