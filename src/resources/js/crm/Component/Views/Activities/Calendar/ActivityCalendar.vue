<template>
  <div>
    <!--filter-->
    <div class="row">
      <div class="col-6 col-sm-8 col-md-9 col-lg-9 col-xl-9">
        <div
          v-if="options.showFilter"
          class="filters-wrapper d-flex justify-content-start flex-wrap"
        >
          <!--Open Filters Button For Mobile-->
          <button
            class="btn d-block d-sm-none btn-toggle-filters"
            type="button"
            @click.prevent="toggleFilters"
          >
            <app-icon :name="'filter'" />
            {{ $t("filters") }}
          </button>

          <span v-show="isFiltersOpen" class="mobile-filters-wrapper">
            <app-filter
              :filters="options.filters"
              table-id="activity-calendar"
              @get-values="getFilterValues"
            />

            <button
              type="button"
              class="btn btn-primary btn-with-shadow d-sm-none btn-close-filter-wrapper d-flex justify-content-center align-items-center"
              @click="toggleFilters"
            >
              {{ $t("close") }}
            </button>
          </span>
          <div class="d-flex align-items-center single-filter ml-3">
            <a v-if="visibleClearFilter"
               href="#"
               @click.prevent="clearAllFilter">
              {{ $t('clear_all_filters') }}
            </a>
          </div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-3">
        <div
          v-if="options.showSearch"
          class="mr-0 single-filter single-search-wrapper"
        >
          <div
            class="form-group form-group-with-search d-flex align-items-center justify-content-end"
          >
            <app-search @input="getSearchValue" />
          </div>
        </div>
      </div>
    </div>

    <!--calendar-->
    <div
      class="card card-with-shadow border-0"
      style="min-height: 400px">
      <div class="card-body p-primary">
        <app-calendar
          :preloader="!dataLoaded"
          :options="prepareCalendarOptions"
        />
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "ActivityCalendar",
  props: {
    options: {
      type: Object
    },
    dataLoaded: {}
  },
  data() {
    return {
      // For Calendar
      calendarOptions: {
        select: this.handleClick,
        eventClick: this.selectedEventHandle,
        initialDate: new Date()
      },
      // For filter
      isFiltersOpen: true,
      filterValue: {},
      filterChanged: false,
      visibleClearFilter: false
    }
  },
  computed: {
    prepareCalendarOptions() {
      return {
        ...this.calendarOptions,
        initialEvents: this.options.events,
      }
    },
  },
  watch: {
    dataLoaded: {
      handler: function (value){
        if(value){
          setTimeout(()=>{
            this.setView();
          })
        } else {
          let x = localStorage.getItem("calendar-default-view");
          if(x) this.calendarOptions.initialView = x;
          else this.calendarOptions.initialView = 'dayGridMonth';
        }
      },
      immediate: true
    }
  },
  mounted() {
    this.isFiltersActive();
    window.onresize = () => {
      this.isFiltersActive();
    };
    this.getFilterValues({context: 1})
  },
  methods: {
    handleClick(arg) {
      this.$emit("select", arg);
    },
    selectedEventHandle(arg) {
      this.$emit("eventClick", arg);
    },
    //for filter
    getFilterValues(values) {

      if(!values['done_activity']){
        values['done_activity'] = ''
      }
      this.filterValue = values;
      this.checkClearFilterVisibility(this.filterValue);
      this.$emit("trigger-filter", this.filterValue);
      this.filterChanged = true;

      if (values["schedule"]) {
        this.calendarOptions.initialDate = values.schedule.start;
      }

      // let urlParam = new URLSearchParams(values).toString();
      // this.axiosGet("crm/activities?" + urlParam)
      //   .then((res) => {
      //     // console.log(res);
      //     this.options.events = [];
      //     res.data.forEach((el) => {
      //       let arr = el.contextable_type
      //         ? el.contextable_type.split("\\")
      //         : [];
      //       let doneClassName = el.status_id == 10 ? 'done':'todo';
      //       this.options.events.push({
      //         title: el.title,
      //         className: [el.activity_type.name.toLowerCase(), doneClassName],
      //         start: el.started_at + " " + el.start_time,
      //         end: el.ended_at + " " + el.end_time,
      //         contextableType: arr[arr.length - 1],
      //         activityType: el.activity_type.name,
      //         databaseId: el.id,
      //         status_id:el.status_id,
      //         activity_type_id:el.activity_type_id
      //       });
      //     });
      //     setTimeout(() => {
      //       this.filterChanged = false;
      //     }, 500);
      //   })
      //   .catch((er) => console.log(er.message));

       setTimeout(() => {
            this.filterChanged = false;
          }, 500);

    },
    toggleFilters() {
      this.isFiltersOpen = !this.isFiltersOpen;
    },
    getSearchValue(value) {
      this.filterValue.search = value;
      this.$emit("trigger-filter", this.filterValue);
    },
    isFiltersActive() {
      this.isFiltersOpen = window.innerWidth > 575;
    },
    checkClearFilterVisibility(value) {
      let initialFilterKeys = this.options.filters
              .filter((item) => item.key !== ("context"))
              .map((i) => i.key),
          filteredKeysWithValue = initialFilterKeys.filter((item) => {
            if (value[item] === null) return false;
            if (typeof value[item] === "object" && Object.keys(value[item]).length === 0)
              return false;
            return value[item];
          });
      this.visibleClearFilter = filteredKeysWithValue.length > 0;
    },

    clearAllFilter() {
      this.$hub.$emit("clearAllFilter-activity-calendar");
      this.getFilterValues({context: 1});
    },

    setView(){
      $('.fc-dayGridMonth-button').click(()=> {
          localStorage.setItem('calendar-default-view','dayGridMonth');
      });
      $('.fc-timeGridWeek-button').click(()=> {
        localStorage.setItem('calendar-default-view','timeGridWeek');
      });
      $('.fc-timeGridDay-button').click(()=> {
        localStorage.setItem('calendar-default-view','timeGridDay');
      })
    }
  },
};
</script>
<style>
.calendar-view .fc-dayGridMonth-view .fc-today {
    background: #f0f3ff6b !important;
}
.fc-v-event .fc-event-main{
  color: #6f6f6f !important;
}
.calendar-view .fc-event{
  color: #6f6f6f !important;
  cursor: pointer !important;
  transition: 0.1s ease-in-out;
  box-shadow: 1px 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.calendar-view .fc-event:hover {
  box-shadow: -5px 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;
}
.calendar-view .fc-event.meeting {
  background: #ddf3bc !important;
}

.calendar-view .fc-event.email {
  background: #c4ced6 !important;
}

.calendar-view .fc-event.call {
  background: #fbc5c5 !important;
}

.calendar-view .fc-event.task {
  background: #e5e69f !important;
}

.calendar-view .fc-event.deadline {
  background: #a9e8f7 !important;
}

.calendar-view .fc-event.lunch {
  background: #e0d420 !important;
}
.calendar-view .fc-event.done {
    text-decoration: line-through !important;
}
</style>

