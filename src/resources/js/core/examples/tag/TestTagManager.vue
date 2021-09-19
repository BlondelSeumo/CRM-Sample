<template>
    <div>
        <app-tag-manager
            :tags="tags"
            :list="tagOptions"
            list-value-field="name"
            :tag-preloader="tagPreloader"
            @storeTag="testStoreData"
            @attachTag="attachTag"
            @detachTag="detachTag"
        />
    </div>
</template>

<script>
import CoreLibrary from "../../helpers/CoreLibrary";

export default {
    name: "TestTagManager",
    extends: CoreLibrary,
    data() {
        return {
            value: {},
            tagPreloader: false,
            tags: [],
            tagOptions: [
                {id: 1, name: 'Red', color: '#72C2EE'},
                {id: 2, name: 'Black', color: '#72f2ee'},
                {id: 3, name: 'Yellow', color: '#72C268'},
                {id: 4, name: 'Blue', color: '#F2C268'}
            ]
        }
    },
    methods: {
        testStoreData(arg) {
            console.log(arg,'store')
            this.tagPreloader = true;
            this.addNewTagCreateOption(arg);
        },
        attachTag(tagId) {
            this.tags.push(tagId)
            console.log(tagId,'attach')
        },
        detachTag(tagId) {
            let index = this.tags.indexOf(tagId);
            this.tags.splice(index, 1);
            console.log(tagId,'detach')
        },
        addNewTagCreateOption(value) {
            this.axiosPost({
                url: 'store-tag-options',
                data: value
            }).then(response => {
                this.tagOptions = response.data.list;
                this.tags.push(response.data.new_id);
            }).finally(() => {
                this.tagPreloader = false;
            });
        },
    }
}
</script>