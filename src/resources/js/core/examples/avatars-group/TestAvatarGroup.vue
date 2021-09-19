<template>
    <div class="content-wrapper p-4">
        <div class="card p-4 card-with-shadow border-0">
            <div class="row">
                <div class="col-xl-6">
                    <app-avatars-group :avatar-group-class="avatarsGroupClass" :avatars="avatars" :shadow="true" :border="true"/>
                </div>
                <div class="col-xl-6">
                    <app-avatars-group :avatars="avatars"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TestAvatarGroup",
        data() {
            return {
                avatarsGroupClass: 'avatars-group-w-60',
                avatars: [
                    {
                        img: 'https://images.unsplash.com/photo-1569779213435-ba3167dde7cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                    {
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                    {
                        img: 'https://images.unsplash.com/photo-1569779213435-ba3167dde7cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                    {
                        img: 'https://images.unsplash.com/photo-1569779213435-ba3167dde7cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                    {
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                    {
                        img: 'https://images.unsplash.com/photo-1569779213435-ba3167dde7cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                    {
                        img: 'https://images.unsplash.com/photo-1569779213435-ba3167dde7cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                    {
                        img: 'https://images.unsplash.com/photo-1569779213435-ba3167dde7cc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Happy daughter',
                        subTitle: 'Software engineer',
                        alt: 'Not Found'
                    },
                ]
            }
        }
    }
</script>
