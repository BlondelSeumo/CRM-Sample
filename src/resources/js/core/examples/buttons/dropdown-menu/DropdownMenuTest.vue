<template>
    <div class="content-wrapper">
        <app-dropdown-menu title="Action" :list="menuList" @action="triggerAction"/>
    </div>
</template>

<script>
export default {
    name: "DropdownMenuTest",
    data() {
        return {
            menuList: [
                {
                    icon: 'plus-square',
                    name: 'Grameenphone',
                    type: 'link',
                    url: '/gp.com'
                },
                {
                    icon: 'edit',
                    name: 'Pathao',
                    type: 'button',
                },
                {
                    icon: 'trash',
                    name: 'Unite Living',
                    type: 'modal',
                    url: '/ul.com'
                },
                {
                    name: 'Unite Living',
                    type: 'link',
                    url: '/ul.com'
                }
            ]
        }
    },
    methods: {
        triggerAction(action) {
            console.log(action);
        }
    }
}
</script>
