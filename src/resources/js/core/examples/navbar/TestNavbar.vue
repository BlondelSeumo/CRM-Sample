<template>

    <app-navbar :logo-url="logoUrl" :user="user" selected-language="EN"
                :language-data="languageData"
                :notificationData="notificationData"
                :all-notification-url="allNotificationUrl"
                :profile-data="profileData"
                @clicked="clickedNotification">
        <template slot="center-option">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-primary">Center</button>
            </div>
        </template>
        <template slot="left-option">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-primary">Left</button>
            </div>
        </template>
    </app-navbar>

</template>

<script>
    export default {
        name: "TestNavbar",
        data() {
            return {
                logoUrl: '/images/core.png',
                allNotificationUrl: '/form',
                user: {
                    full_name: 'John Doe',
                    img: 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=800&q=80',
                    status: 'online',
                    role: 'Admin'
                },
                languageData: [
                    {
                        img: 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Flag_of_Great_Britain_%281707%E2%80%931800%29.svg/1024px-Flag_of_Great_Britain_%281707%E2%80%931800%29.svg.png',
                        title: 'English',
                        url: 'gp.com'
                    },
                    {
                        img: 'https://cdn.countryflags.com/thumbs/spain/flag-400.png',
                        title: 'Spanish',
                        url: 'pathao.com'
                    },
                    {
                        img: 'https://upload.wikimedia.org/wikipedia/commons/b/bc/Flag_of_India.png',
                        title: 'Hindi',
                        url: 'ul.com'
                    },
                    {
                        img: 'https://upload.wikimedia.org/wikipedia/commons/6/62/Flag_of_France.png',
                        title: 'French',
                        url: 'ul.com'
                    }
                ],
                notificationData: [
                    {
                        name: 'Sani Khan',
                        // img: 'https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Notification title',
                        description: 'Cras sit amet nibh libero, in gravida nulla',
                        time: '9:00 AM',
                        date: 'Today',
                        status: 'New',
                        url: 'gp.com'
                    },
                    {
                        name: '',
                        img: 'https://images.unsplash.com/photo-1527980965255-d3b416303d12?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=800&q=80',
                        title: 'Notification title',
                        description: 'Cras sit amet nibh libero, in gravida nulla',
                        time: '9:00 AM',
                        date: '12 Jan',
                        status: 'Old',
                        url: 'gp.com'
                    },
                    {
                        name: '',
                        img: 'https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Notification title',
                        description: 'Cras sit amet nibh libero, in gravida nulla',
                        time: '9:00 AM',
                        date: 'Yesterday',
                        status: 'New',
                        url: 'gp.com'
                    },
                    {
                        name: '',
                        img: 'https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Notification title',
                        description: 'Cras sit amet nibh libero, in gravida nulla',
                        time: '9:00 AM',
                        date: 'Yesterday',
                        status: 'New',
                        url: 'gp.com'
                    },
                    {
                        name: '',
                        img: 'https://images.unsplash.com/photo-1506919258185-6078bba55d2a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60',
                        title: 'Notification title',
                        description: 'Cras sit amet nibh libero, in gravida nulla',
                        time: '9:00 AM',
                        date: 'Yesterday',
                        status: 'New',
                        url: 'gp.com'
                    }
                ],
                profileData: [
                    {
                        optionName: 'My Profile',
                        optionIcon: 'user',
                        url: '/my-profile'
                    },
                    {
                        optionName: 'Settings',
                        optionIcon: 'settings',
                        url: '#'
                    },
                    {
                        optionName: 'Logout',
                        optionIcon: 'log-out',
                        url: '/'
                    },
                ]
            }
        },
        methods: {
            clickedNotification(notification){
                console.log(notification);
            }
        }
    }
</script>
