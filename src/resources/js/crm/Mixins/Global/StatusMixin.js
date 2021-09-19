export default {
    computed: {
        statuses() {
            return this.$store.state.additional.statuses.map((status) => {
                return {
                    id: status.id,
                    value: status.value,
                    class: status.class
                }
            })
        },
    },

    methods: {
        status(id) {
            return this.statuses.find(status => {
                if (status.id === id) return true;
            })
        }
    },
    watch: {
        'statuses.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'status')  option.push(...this.statuses)
                })
            },
            immediate: true
        }
    }

}
