export default {
    methods: {
        scrollToTop(afterRender = true) {
            const handler = () => window.scroll({
                top: 0,
                left: 0,
                behavior: 'smooth'
            });

            if (afterRender) {
                setTimeout(() => handler())
            } else {
                handler();
            }

        }
    }
}