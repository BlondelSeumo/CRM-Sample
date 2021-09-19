// Owner Filter
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

    watch: {
        'owners.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'owner_is') option.push(...this.owners)
                })
            },
            immediate: true
        }
    },

    mounted() {

        setTimeout(() => {
            this.$store.dispatch('getOwner')
        }, 1000)
    }
}


// Type Filter
export const contactType = {
    computed: {
        contactTypes() {
            if (this.$can('view_types')){
                return this.$store.getters.contentType.map(type => {
                    return {
                        id: type.id,
                        value: type.name
                    }
                })
            }

        }
    },
    watch: {
        'contactTypes.length': {
            handler: function (length) {
                if (this.$can('view_types')){
                    this.options.filters.find(({key}, index) => {
                        if (key === 'contact_type') this.options.filters[index].option = [...this.contactTypes]

                    })
                }

            },
            immediate: true
        }
    },
    mounted() {
        if (this.$can('view_types')){
                setTimeout(() => {
                this.$store.dispatch('contentType')
            }, 1000)
        }

    }
}

// Status Filter
export const status = {
    computed: {
        statuses() {
            return this.$store.getters.getStatus.map(status => {
                return {
                    id: status.id,
                    value: status.translated_name
                }
            })
        }
    },
    watch: {
        'statuses.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'status') option.push(...this.statuses)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getStatus')
    }
}

// Proposal Status Filter
export const proposalStatus = {
    computed: {
        statusProposal() {
            return this.$store.getters.getProposalStatus.map(status => {
                return {
                    id: status.id,
                    value: status.translated_name
                }
            })
        }
    },
    watch: {
        'statusProposal.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'status') option.push(...this.statusProposal)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getProposalStatus')
    }
}

// Deal Status Filter
export const dealStatus = {
    computed: {
        statusDeal() {
            return this.$store.getters.getDealStatus.map(status => {
                return {
                    id: status.id,
                    value: status.translated_name
                }
            })
        }
    },
    watch: {
        'statusDeal.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'status') option.push(...this.statusDeal)
                })
            },
            immediate: true
        }
    },
    mounted() {
        setTimeout(() => {
            this.$store.dispatch('getDealStatus')
        }, 1000)
    }
}

// Tags Filter
export const tag = {
    computed: {
        tags() {
            return this.$store.getters.getAllTags.map(tag => {
                return {
                    id: tag.id,
                    value: tag.name
                }
            })
        }
    },
    watch: {
        'tags.length': {
            handler: function (length) {
                this.options.filters.find(({key}, index) => {
                    if (key === 'tags') {
                        this.options.filters[index].option = [...this.tags]
                    }
                })
            }
        }
    },
    mounted() {

        setTimeout(() => {
            this.$store.dispatch('getAllTags')
        }, 1000)
    }

}

// Organization Filter
export const organization = {
    computed: {
        organizations() {
            return this.$store.getters.getOrganization.map(organization => {
                return {
                    id: organization.id,
                    value: organization.name
                }
            })
        }
    },
    watch: {
        'organizations.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'organization') option.push(...this.organizations)
                })
            },
            immediate: true
        }
    },

    mounted() {
        this.$store.dispatch('getOrganization')
    }
}

// Person Filter
export const person = {
    computed: {
        persons() {
            return this.$store.getters.getPerson.map(person => {
                return {
                    id: person.id,
                    value: person.name
                }
            })
        }
    },
    watch: {
        'persons.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'person') option.push(...this.persons)
                })
            },
            immediate: true
        }
    },
    mounted() {

        setTimeout(() => {
            if (this.$can('view_persons')){
                this.$store.dispatch('getPerson')
            }
        }, 1000)
    }
}

// Pipeline Filter
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
                this.options.filters.find(({key, option}) => {
                    if (key === 'pipeline') option.push(...this.pipelines)
                })
            },
            immediate: true
        }
    },
    mounted() {

        setTimeout(() => {
            this.$store.dispatch('getPipeline')
        }, 1000)
    }
}

// Activity Types Filter
export const activityType = {
    computed: {
        activityType() {
            return this.$store.getters.getActivityType.map(activityType => {
                return {
                    id: activityType.id,
                    value: activityType.name
                }
            })
        }
    },
    watch: {
        'activityType.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'activity_type') option.push(...this.activityType)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getActivityType')
    }
}

// Deal Filter
export const deal = {
    computed: {
        deal() {
            return this.$store.getters.getDeal.map(deal => {
                return {
                    id: deal.id,
                    value: deal.title
                }
            })
        }
    },
    watch: {
        'deal.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'deal') option.push(...this.deal)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getDeal')
    }
}

// Phones Filter
export const phoneNumber = {
    computed: {
        phone() {
            return this.$store.getters.getPhoneNumber.map(phone => {
                return {
                    id: phone.id,
                    value: phone.value
                }
            })
        }
    },
    watch: {
        'phone.length': {
            handler: function (length) {
                this.options.filters.find(({key, option}) => {
                    if (key === 'phones') option.push(...this.phone)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getPhoneNumber')
    }
}
