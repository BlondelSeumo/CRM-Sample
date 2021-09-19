export default {
    watch: {
        darkMode: function () {
            let htmlElement = document.documentElement;

            if (this.darkMode) {
                localStorage.setItem('theme', 'dark');
                htmlElement.setAttribute('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
                htmlElement.setAttribute('theme', 'light');
            }
        },
        leftMenuType: function () {
            let htmlElement = document.documentElement;

            if (this.leftMenuType === 'normal') {
                localStorage.setItem('sidebar', 'normal');
                htmlElement.setAttribute('sidebar', 'normal');
            } else if (this.leftMenuType === 'icon-only') {
                localStorage.setItem('sidebar', 'icon-only');
                htmlElement.setAttribute('sidebar', 'icon-only');
            } else {
                localStorage.setItem('sidebar', 'floating');
                htmlElement.setAttribute('sidebar', 'floating');
            }
        }
    },
    mounted() {
        // Check for active theme
        let body = $('body'),
            htmlElement = document.documentElement,
            theme = localStorage.getItem('theme'),
            sidebar = localStorage.getItem('sidebar');

        if (theme === 'dark') {
            htmlElement.setAttribute('theme', 'dark');
            this.darkMode = true;
            this.$store.state.theme.darkMode = true;
        } else {
            htmlElement.setAttribute('theme', 'light');
            this.darkMode = false;
            this.$store.state.theme.darkMode = false;
        }

        if (sidebar === 'normal') {
            localStorage.setItem("sidebar", 'normal');
            htmlElement.setAttribute('sidebar', 'normal');
            body.removeClass('sidebar-icon-only');
            body.removeClass('sidebar-hover-only');
            this.leftMenuType = 'normal';
        } else if (sidebar === 'icon-only') {
            localStorage.setItem("sidebar", 'icon-only');
            htmlElement.setAttribute('sidebar', 'icon-only');
            body.removeClass('sidebar-hover-only');
            body.toggleClass('sidebar-icon-only');
            this.leftMenuType = 'icon-only';
        } else if (sidebar === 'floating') {
            localStorage.setItem("sidebar", 'floating');
            htmlElement.setAttribute('sidebar', 'floating');
            body.removeClass('sidebar-icon-only');
            body.toggleClass('sidebar-hover-only');
            $('.sidebar').find('.collapse.show').collapse('hide');
            this.leftMenuType = 'floating';
        }
    },
    methods: {
        sidebarOffcanvas() {
            $('.sidebar-offcanvas').toggleClass('active')
        },
        fullscreen() {
            if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
                if (document.documentElement.requestFullScreen) {
                    document.documentElement.requestFullScreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullScreen) {
                    document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
                } else if (document.documentElement.msRequestFullscreen) {
                    document.documentElement.msRequestFullscreen();
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }
        },
        togglingLeftMenu(action) {
            let body = $('body');

            if (action === 'active-icon-only-menu') {
                body.removeClass('sidebar-hover-only');
                body.toggleClass('sidebar-icon-only');
                this.leftMenuType = 'icon-only';
            } else if (action === 'active-floating-menu') {
                body.removeClass('sidebar-icon-only');
                body.toggleClass('sidebar-hover-only');
                $('.sidebar').find('.collapse.show').collapse('hide');
                this.leftMenuType = 'floating';
            } else if (action === 'active-normal-menu') {
                body.removeClass('sidebar-icon-only');
                body.removeClass('sidebar-hover-only');
                this.leftMenuType = 'normal';
            }
        },
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            this.$store.state.theme.darkMode = !this.$store.state.theme.darkMode;
        }
    }
}
