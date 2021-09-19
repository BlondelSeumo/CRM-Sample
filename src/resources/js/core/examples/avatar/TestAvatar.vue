<template>
    <div class="content-wrapper" style="height: 100vh;">
        <app-breadcrumb
            page-title="Page title"
            :directory="['Directory','Sub-directory']"
            :icon="'grid'"
            :button="{label:'Form',url:'/form'}"
        />
        <div class="row">
            <app-avatar
                title="avatar"
                :avatar-class="avatarClass"
                status="success" :shadow="true"
                :border="true"
                :img="img"
                :alt-text="altText"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "TestAvatar",
    data() {
        return {
            avatarClass: "avatars-w-100",
            img: "https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60",
            altText: "Not Found"
        }
    }
}
</script>
