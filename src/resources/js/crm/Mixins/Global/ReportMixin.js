// Owner
export const owner = {
    computed: {
        owners() {
            return this.$store.getters.getOwner.map(owner => {
                return {
                    id: owner.id,
                    value: owner.full_name
                }
            })
        }
    },

    mounted() {
        this.$store.dispatch('getOwner')
    }
}

// Status Filter
export const status = {

    /*
    * set two data property component
    * typeWiseStatues:[],
      statusType:"your required status type",

    * */
    data(){
      return{
          statusType: 'deal'
      }
    },
    computed: {
        statuses() {
            return this.$store.getters.getStatus.map(status => {
                return {
                    id: Number(status.id),
                    value: status.translated_name,
                    type: status.type
                }
            });
        }
    },
    watch: {
        'statuses.length': {
            handler: function (length) {
                this.typeWiseStatues.push(...this.statuses.filter(element => element.type === this.statusType));
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getStatus')
    }
}


//Pipeline
export const pipeline = {
    computed: {
        pipelines() {
            return this.$store.getters.getPipeline.map(pipeline => {
                return {
                    id: pipeline.id,
                    value: pipeline.name
                }
            })
        }
    },
    watch: {
        'pipelines.length': {
            handler: function (length) {
                this.filters.find(({key, option}) => {
                    if (key === 'pipeline') option.push(...this.pipelines)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getPipeline')
    }
}


// Stage
export const stages = {

    computed: {
        stages() {
            return this.$store.getters.getStages.map(stage => {
                return {
                    id: stage.id,
                    value: stage.name,
                    pipeline_id: stage.pipeline_id
                }
            })
        }
    },

    watch: {
        'stages': {
            handler: function (length) {

                let indexOfStageFilter = this.secondaryFilters.findIndex(element => element.key === "stages");
                this.secondaryFilters.find(({key, option}) => {

                    //get only stages for pipeline_id 1
                    let filteredStages = this.stages.filter((element) => {
                        return element.pipeline_id === 1;
                    });


                    if (key === 'stages') {
                        let isDuplicate = filteredStages.includes(
                            {
                                id: 0,
                                value: this.$t('every_stage'),
                                pipeline_id: 1
                            }
                        );

                        !isDuplicate ? filteredStages.unshift(
                            {
                                id: 0,
                                value: this.$t('every_stage'),
                                pipeline_id: 1
                            }) : false;

                        this.secondaryFilters[indexOfStageFilter].option = filteredStages;
                        this.secondaryFilters[indexOfStageFilter].title = this.$t('every_stage');
                        this.secondaryFilters[indexOfStageFilter].option = filteredStages;
                    }
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getStages');
    }
}
