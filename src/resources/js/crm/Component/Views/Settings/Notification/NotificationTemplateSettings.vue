<template>
  <app-modal modal-id="notification-template" modal-body-class="p-0" modal-size="large" modal-alignment="top" @close-modal="closeModal">
    <template slot="header">
      <h5 class="modal-title">{{ $t('template') }} : {{ eventName.translated_name }}</h5>
      <button type="button" class="close outline-none" data-dismiss="modal" aria-label="Close">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
      </button>
    </template>
    <app-overlay-loader v-if="!preLoader"/>
    <template slot="body" v-else>
      <app-tab type="horizontal" :tabs="tabs"/>
    </template>
  </app-modal>
</template>

<script>

import {FormMixin} from "@core/mixins/form/FormMixin";

export default {
  name: "NotificationTemplateSettings",
  props: {
    tableId: String,
    eventId: {},
  },
  mixins: [FormMixin],
  data() {
    return {
      preLoader: false,
      tabs: [
        {
          name: this.$t('database'),
          title: this.$t('database'),
          component: "app-database-template",
          props: this.tableId,
          permission: false,
          icon: 'bell',
          type:'database'
        },
        {
          name: this.$t('mail'),
          title: this.$t('mail'),
          component: "app-mail-template",
          permission: true,
          icon: 'mail',
          props: this.tableId,
          type: 'mail'
        },
      ]
    }
  },

  computed: {
    eventName() {
      return this.$store.getters.editNotificationEvent
    }
  },
  methods: {
    afterSuccessFromGetEditData(response) {
      this.template = response.data
      setTimeout(() => {
        this.preLoader = true;
      }, 1500)
    },
    closeModal(value) {
      this.$emit('close-modal', value);
    },
  },

  watch:{
    eventName: {
      handler: function (eventName) {
        eventName.templates.forEach((item, index) => {
          this.tabs.forEach((element) => {
            if (element.type == item.type){
              element.permission = true;
            }
          })
        })
      },
      deep: true
    }
  },

  created() {
    if (this.eventId) {
      this.$store.dispatch('editNotificationEvent', this.eventId)
    }
  }
}
</script>
