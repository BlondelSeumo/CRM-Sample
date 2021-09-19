<template>
    <div class="content-wrapper">
        <div class="card card-with-shadow border-0" style="min-height: 400px">
            <div class="card-body p-primary">
                <app-calendar :preloader="preloader" :options="options"/>
            </div>
        </div>
    </div>
</template>

<script>
import AxiosFunction from "../../helpers/axios/AxiosFunction";

export default {
    name: "TestCalendarView",
    data() {
        return {
            preloader: false,
            options: {
                select: this.createEvent,
                eventClick: this.selectedEvent,
                eventChange: this.eventUpdate,
                initialEvents: [],
                initialDate: new Date(),
            }
        }
    },
    created() {
        this.preloader = true;
    },
    mounted() {
        this.getCalendarData();
    },
    methods: {
        createEvent(arg) {
            // finding calendar data for creating event
            console.log(arg);
        },
        selectedEvent(arg) {
            // get selected event for action
            console.log(arg);
        },
        eventUpdate(arg) {
            // Update Event
            console.log(arg);
        },
        getCalendarData() {
            AxiosFunction.axiosGet('test-calendar-events')
                .then(res => this.options.initialEvents = res.data)
                .finally(() => this.preloader = false)
        }
    },
}
</script>
