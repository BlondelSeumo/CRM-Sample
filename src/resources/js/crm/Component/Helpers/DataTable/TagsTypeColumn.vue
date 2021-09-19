<template>
    <div>
        <app-tag-manager
            :tags="tags"
            :list="allTags"
            list-value-field="name"
            :tag-preloader="tagPreloader"
            @storeTag="storeAndAttachTag"
            @attachTag="attachTag"
            @detachTag="detachTag"
        />
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
export default {
    name: "TagsTypeColumn",
    mixins: [FormMixin],
    props: {
        rowData: {
            type: Object
        },
        tableId: {
            type: String
        }
    },
    data() {
        return {
            isTagsEditable: true,
            postUrl: '',
            tags: [],
            tagOptions: [],
            tagPreloader: false,
        }
    },
    created() {
        this.tagPreloader = true;
    },
    watch: {
        rowData: {
            handler: function (data) {
                this.tags = data.tags.map(item => item.id);
                this.tagsPostUrl();
            },
            deep: true,
            immediate: true
        },
        allTags: {
            handler: function (data) {
                if(data.length) setTimeout(()=> this.tagPreloader = false);
                else {
                    this.tagPreloader = false;
                }
            },
            immediate: true
        }
    },
    computed: {
        allTags() {
            return this.$store.getters.getAllTags.map((tag)=>{
                return {
                    id: tag.id,
                    color: tag.color_code,
                    name: tag.name
                };
            })
        }
    },
    methods: {
        storeAndAttachTag({name, color}) {
            this.tagPreloader = true;
            this.axiosPost({
                url: route('tags.store'),
                data: {name, color_code: color}
            }).then(response => {
                this.$store.dispatch('getAllTags');
                this.attachTag(response.data.id);
            }).catch((error) => this.$toastr.e(error.response.data.errors.name[0]));
        },
        attachTag(tag_id) {
            this.tagPreloader = true;
            this.axiosPost({
                url: this.postUrl,
                data: {tag_id}
            }).then(response => {
                this.$toastr.s(response.data.message);
                this.tags.push(tag_id);
                this.tagPreloader = false;
            });
        },
        detachTag(tag_id) {
            this.tagPreloader = true;
            this.axiosPut({
                url: this.postUrl,
                data: {tag_id}
            }).then(response => {
                this.$toastr.s(response.data.message);
                let index = this.tags.indexOf(tag_id);
                this.tags.splice(index, 1);
                this.tagPreloader = false;
            })
        },
        tagsPostUrl() {
            if (this.tableId === 'person-modal') {
                this.postUrl = route('persons.attach-tag', {id: this.rowData.id});
            } else if (this.tableId === 'organization-modal') {
                this.postUrl = route('organizations.attach-tag', {id: this.rowData.id});
            } else if (this.tableId === 'send-proposal-modal') {
                this.postUrl = route('proposals.tag-attach', {id: this.rowData.id});
            } else if (this.tableId === 'deal-modal') {
                this.postUrl = route('deal.attach-tag', {id: this.rowData.id});
            } else if (this.tableId === 'deals-common-table') {
                this.postUrl = route('deal.attach-tag', {id: this.rowData.id});
            }
        }
    }

}
</script>
