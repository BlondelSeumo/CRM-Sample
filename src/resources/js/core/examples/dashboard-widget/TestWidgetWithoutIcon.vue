<template>
    <app-widget type="app-widget-without-icon" :label="label" :number="number" />
</template>

<script>
    export default {
        name: "TestWidgetWithoutIcon",
        data() {
            return {
            	label: 'Total Sent',
            	number: 5000,
        	}
    	}
    }
</script>
