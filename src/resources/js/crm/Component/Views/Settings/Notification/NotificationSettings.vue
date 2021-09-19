<template>
    <div>
        <app-table :id="tableId" :options="options" @action="getAction"/>

        <app-notification-setting-modal
            v-if="showNotificationSettingsModal"
            :eventId="event_id"
            :table-id="tableId"
            :selected-url="notificationSettingSelectedUrl"
            @close-modal="closeSettingModal"/>

        <app-notification-template-setting
            v-if="showTemplateModal"
            :eventId="event_id"
            :selected-url="notificationSelectedUrl"
            :table-id="tableId"
            modal-id="notification-template"
            @close-modal="closeModal"
        />
    </div>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin.js";

export default {
    name: "NotificationSettings",
    mixins: [FormMixin],
    data() {
        return {
            tableId: 'notification-table',
            event_id: '',
            notificationSelectedUrl: '',
            notificationSettingSelectedUrl: '',
            showTemplateModal: false,
            showNotificationSettingsModal: false,

            options: {
                tableShadow: false,
                tablePaddingClass: 'px-0 py-primary',
                name: 'NotificationSettings',
                url: route('notification-events.index'),
                showHeader: true,
                columns: [
                    {
                        title: this.$t('event_name'),
                        type: 'text',
                        key: 'translated_name',

                    },
                    {
                        title: this.$t('notification_channel'),
                        type: 'custom-html',
                        key: 'settings',
                        isVisible: true,
                        modifier: (settings, row) => {
                            if (['user_invitation', 'password_reset', 'deal_assigned'].includes(row.name)) {
                                return `<span class="badge badge-pill badge-success">${this.$t('mail')}</span>`;
                            }
                            if (!settings)
                                return '';
                            return settings.notify_by.map(type => {
                                return `<span class="badge badge-pill ${type === 'database' ? 'badge-primary' : 'badge-success'}">
               ${this.$t(type)}</span>`
                            }).join(' ')
                        }
                    },
                    {
                        title: this.$t('templates'),
                        type: 'button',
                        key: 'id',
                        className: 'btn btn-sm btn-primary py-1',
                        icon: 'trello',
                        actionType: 'manage',
                        modifier: (id) => {
                            return this.$t('update');
                        }
                    },
                    {
                        title: this.$t('action'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                filters: [],
                showSearch: true,
                showFilter: true,
                showManageColumn: false,
                paginationType: "pagination",
                responsive: true,
                rowLimit: 20,
                showAction: true,
                orderBy: 'desc',
                actionType: "default",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'settings',
                        type: 'modal',
                        actionType: 'edit',
                        modifier: (row) => {
                            return !['user_invitation', 'password_reset', 'deal_assigned'].includes(row.name);
                        }

                    }
                ],
            }
        }
    },
    methods: {
        getAction(row, action, active) {
            if (action.actionType === 'edit') {
                this.notificationSettingSelectedUrl = route('core.notification-settings.update', {'id': row.id});
                this.showNotificationSettingsModal = true
                this.event_id = row

            } else if (action.actionType === 'manage') {
                this.showTemplateModal = true;
                this.event_id = row.id
                this.notificationSelectedUrl = route('core.notification-templates.update', {'id': row.id});
            }
        },
        closeModal() {
            this.showTemplateModal = false;
            this.notificationSelectedUrl = '';
            this.event_id = '';
            $("#notification-template").modal('hide');
        },
        closeSettingModal() {
            this.event_id = '';
            this.notificationSettingSelectedUrl = ''
            this.showNotificationSettingsModal = false
            $("#notification-setting-modal").modal('hide');
        }
    },
    created() {
        this.$store.dispatch('getOwner');
        this.$store.dispatch('getRole');
    }
}
</script>
