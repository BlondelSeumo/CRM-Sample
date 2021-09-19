<template>
    <app-avatars-group
        :avatars-group-class="avatarsGroupClass"
        :name="avatarsGroupName+index"
        :shadow="true"
        :border="true"
        :avatars="avatars"
    />

</template>

<script>
    import {urlGenerator} from "@app/Helpers/helpers";
    export default {
        name: "ImageGroup",
        props: {
            value: {},
            rowData: {},
            index: {}
        },
        data() {
            return {
                avatarsGroupClass: 'avatars-group-w-50',
                avatarsGroupName: 'exampleAvatarsGroup',
            }
        },
        computed: {
            avatars() {
                return this.value.map(item => {
                    return {
                        img: item.profile_picture ? urlGenerator(item.profile_picture.path) : undefined,
                        alt: item.full_name || '',
                        title: item.full_name || '',
                        subTitle: item.email
                    };
                })
            }
        },
    }
</script>
