<template>
    <form ref="form">
        <div class="form-group">
            <label>{{ $t('contents') }}</label>
            <app-input
                id="database-template-title"
                name="custom_content"
                v-model="template.custom_content"
                :required="true"/>
        </div>
        <div class="form-group text-center">
            <button
                type="button"
                class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                data-toggle="tooltip"
                data-placement="top"
                v-for="t in Object.keys(all_tags)"
                @click="insertAtCaret('database-template-title', t)"
                :title="tags[t]"
            >{{ t }}
            </button>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal" @click.prevent="closeModal">
                {{ $t('cancel') }}
            </button>
            <button type="button" class="btn btn-primary" @click.prevent="submitData">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
                <template v-if="!loading">{{ $t("save") }}</template>
            </button>


        </div>
    </form>
</template>

<script>

import {TemplateMixins} from "./Mixins/TemplateMixins";

export default {
    name: "DatabaseTemplate",
    mixins: [TemplateMixins],
    props: ['props'],
    data() {
        return {
            loaded: false,
            tags: {
                '{name}': this.$t('The resource name of the event'),
                '{app_name}': this.$t('Name of the app'),
                '{brand_name}': this.$t('brand_name'),
                '{action_by}': this.$t('The user who performed the action'),
            },
        }
    },
    computed: {
        setTemplateObj() {
            let data = this.$store.getters.editNotificationEvent;
            this.template = data.templates.find(item => {
                item.custom_content = item.custom_content ? item.custom_content : item.default_content;
                return item.type === 'database'
            });
            return this.template;
        },
        all_tags() {
            let tags = {...this.tags};
            if (this.props.alias === 'app') {
                delete tags['{brand_name}'];
                return tags;
            }
            delete tags['{app_name}'];
            return tags;
        }
    },

}
</script>

<style scoped>
.margin-left-2 {
    margin-left: 4px;
}

.margin-left-2:first-child {
    margin-left: 0;
}
</style>
