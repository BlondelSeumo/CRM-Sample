<template>
    <app-widget type="app-widget-with-icon" :label="label" :number="number" :icon="icon" />
</template>

<script>
    export default {
        name: "TestWidgetWithoutIcon",
        data() {
            return {
            	label: 'Total Campaigns',
            	icon: 'sun',
            	number: 5000,
                
        	}
    	}
    }
</script>
