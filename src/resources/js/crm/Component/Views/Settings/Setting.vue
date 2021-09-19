<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="$t('settings')" :directory="$t('settings')" :icon="'settings'"/>
        <app-tab v-if="dataLoaded" :tabs="tabs" :icon="tabIcon"/>
    </div>
</template>

<script>

    import {FormMixin} from "../../../../core/mixins/form/FormMixin";

    export default {
        name: "Setting",
        props: {
            marketPlaceVersion:{
                default: false,
            },
            appUrl:{

            }
        },
        mixins: [FormMixin],

        data() {
            return {
                dataLoaded: false,
                loggedInUser: {},
                tabIcon: 'settings',
                tabs: [
                    {
                        'name': 'General',
                        'title': this.$t('general'),
                        'component': 'app-general-settings',
                        'permission': ''
                    },
                    {
                        'name': 'Custom fields',
                        'title': this.$t('custom_fields'),
                        'component': 'app-custom-field',
                        'permission': '',
                        headerButton: {
                            label: this.$t('add_custom_field'),
                            class: 'btn btn-primary'
                        }
                    },
                    {
                        'name': 'Email setup',
                        'title': this.$t('email_setup'),
                        'component': 'app-email-setup',
                        'permission': '',
                        'props': "",
                    },
                    {
                        'name': 'Broadcast setup',
                        'title': this.$t('broadcast_setup'),
                        'component': 'app-broadcast-setup',
                        'permission': '',
                        'props': "",
                    },
                  {
                    'name': 'Notification',
                    'title': this.$t('notification'),
                    'component': 'app-notification-settings',
                      'permission': '',
                  },
                  {
                    'name': 'Update',
                    'title': this.$t('update'),
                    'component': 'app-update',
                      'permission': '',
                      'props': "",
                  },

                ],
            }
        },

        mounted() {
            this.tabs.forEach((el) => {
                el["props"] = {
                    isMarketPlaceVersion: this.marketPlaceVersion,
                    appUrl: this.appUrl
                };
            });
            this.dataLoaded = true;
        }

    }
</script>
