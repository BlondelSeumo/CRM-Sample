<template>
    <div class="border-bottom mb-4 pb-4">
        <p class="mb-2 font-weight-bold">{{ $t('tags') }}</p>
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
    </div>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
export default {
    name: "TagManager",
    mixins: [FormMixin],
    props:['tagData', 'taggableId', 'postUrl'],
    data() {
        return {
            tags: [],
            tagOptions: [],
            tagPreloader: false,
        }
    },
    created() {
        this.tagPreloader = true;
        this.$store.dispatch('getAllTags');
    },
    watch: {
        tagData: {
            handler: function (tags) {
                this.tags = tags.map(item => item.id)
            },
            immediate: true
        },
        allTags: {
            handler: function () {
                this.tagPreloader= false;
            }
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
            });
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
                this.$emit("update-request");
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
                this.$emit("update-request");
                this.$toastr.s(response.data.message);
                let index = this.tags.indexOf(tag_id);
                this.tags.splice(index, 1);
                this.tagPreloader = false;
            })
        },
    },
}
</script>
