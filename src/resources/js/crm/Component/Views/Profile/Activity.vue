<template>
  <div class>
    <app-table id="activity-log" :options="options" />
  </div>
</template>

<script>
import { formatDateToLocal, ucFirst } from "@app/Helpers/helpers";
export default {
  name: "Activity",
  data() {
    return {
      options: {
        name: "Activity",
        url: "admin/user/activity-log",
        showHeader: true,
        tableShadow: false,
        tablePaddingClass: "px-0 py-primary",
        columns: [
          {
            title: this.$t("title"),
            type: "text",
            key: "description",
          },

          {
  title: this.$t("name"),
  type: "custom-html",
  key: "subject",
  modifier: (subject, activity) => {
    if (subject)
      return (ucFirst(this.$t(subject.name)) || subject.title || subject.lost_reason ||subject.full_name) ||
          subject.subject;
    let attributes = this.$optional(activity, 'properties', 'attributes');
    let old = this.$optional(activity, 'properties', 'old');
    if (attributes && Object.keys(attributes).length) {
      return (attributes.name || attributes.full_name) || attributes.subject || attributes.title || attributes.lost_reason;
    }else if(old && Object.keys(old).length){
      return (old.name ||  old.full_name) || old.subject || old.title || old.lost_reason;
    }
  },
},

          {
            title: this.$t("time"),
            type: "custom-html",
            key: "created_at",
            modifier: (created_at) => formatDateToLocal(created_at, true),
          },
        ],
        showSearch: true,
        showFilter: true,
        showManageColumn: false,
        paginationType: "pagination",
        responsive: true,
        rowLimit: 10,
        showAction: false,
        orderBy: "desc",
      },
    };
  },
};
</script>

<style scoped>
</style>
