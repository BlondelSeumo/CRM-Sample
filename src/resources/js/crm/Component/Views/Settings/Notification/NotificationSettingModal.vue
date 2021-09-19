<template>
    <app-modal modal-id="notification-setting-modal" modal-size="default" modal-alignment="top"
               @close-modal="closeModal">
        <template slot="header">
            <h5 class="modal-title">{{ $t('settings') }} : {{ eventInformation.translated_name }}</h5>
            <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <app-overlay-loader v-if="dataLoaded"/>
            <form ref="form" :data-url="`${selectedUrl}`" v-else>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <h6>{{ $t('notification_channel') }}</h6>
                            <app-input
                                type="multi-select"
                                v-model="settings.notify_by"
                                :list="makeChannelArr"
                                list-value-field="name"
                                :required="true"
                                :isAnimatedDropdown="true"/>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6>{{ $t('notification_audiences') }}</h6>
                    </div>
                    <div class="col-12">
                        <label>{{ $t('roles') }}</label><br>
                        <app-input
                            type="multi-select"
                            v-model="settings.roles"
                            :list="roleList"
                            list-value-field="name"
                            :required="true"
                            :isAnimatedDropdown="true"/>
                    </div>
                    <div class="col-12">
                        <label>{{ $t('users') }}</label><br>
                        <app-input
                            type="multi-select"
                            v-model="settings.users"
                            :list="userList"
                            list-value-field="full_name"
                            :isAnimatedDropdown="true"/>
                    </div>
                </div>
            </form>

        </template>

        <template slot="footer">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>

            <button type="button" class="btn btn-primary" @click.prevent="submitData">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
                <template v-if="!loading">{{ $t("save") }}</template>
            </button>

        </template>
    </app-modal>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin";
import {mapGetters, mapState} from 'vuex';
import {FormSubmitMixin} from "../../../../Mixins/Global/FormSubmitMixin";

export default {
    name: "NotificationSettingModal",
    props: {
        tableId: String,
        eventId: {}
    },
    mixins: [FormMixin, FormSubmitMixin],
    data() {
        return {
            eventInformation: _.cloneDeep(this.eventId),
            settings: {
                users: [],
                roles: [],
                notify_by: [],
                notification_event_id: null,
                audiences: []
            },
            channelList: [],
        }
    },
    computed: {
        ...mapState({
            dataLoaded: state => state.loading
        }),
        ...mapGetters({
            userList: 'getOwner',
            notificationSettings: 'notificationSettings'
        }),
        roleList() {
            return this.$store.getters.getRole.filter(item => item.name !== 'Client');
        },
        makeChannelArr() {
            let output = [];
            this.notificationSettings.filter((item, index) => {
                let obj = {
                    id: item.name,
                    name: this.$t(item.name)
                };
                output = [...output, obj];
            });
            return output;
        }
    },
    methods: {
        setSelectedData() {
            let audiencesArr = this.eventInformation.settings['audiences'];
            this.settings.notify_by = this.eventInformation.settings['notify_by']
            this.settings.notification_event_id = this.eventInformation.id;
            audiencesArr.map(item => {
                if (item.audience_type == 'roles')
                    this.settings.roles = item.audiences
                else if (item.audience_type == 'users')
                    this.settings.users = item.audiences
            });
        },
        submitData() {
            this.settings.audiences = [
                this.settings.roles.length ? {
                    audience_type: 'roles',
                    audiences: this.settings.roles ? this.settings.roles : []
                } : '',
                this.settings.users.length ? {
                    audience_type: 'users',
                    audiences: this.settings.users ? this.settings.users : []
                } : '',
            ];
            this.settings.audiences = this.settings.audiences.filter(a => a);
            this.fieldStatus.isSubmit = true;

            if (this.settings.users.length > 0 || this.settings.roles.length > 0) {
                this.save(this.settings)
            }
        },
    },

    mounted() {
        this.setSelectedData();
        this.$store.dispatch('getNotificationsChannels');
    }
}
</script>
