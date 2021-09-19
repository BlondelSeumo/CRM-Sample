import {formatted_date} from "../crm/Helpers/helpers";
export default {

    state: {

        settings: {
            dateFormat: formatted_date(),
            timeFormat: window.settings.time_format == 'h' ? 12 : 24,
        },
        theme: {
            darkMode: false
        }

    }

    // getters: {
    // },

    // actions: {

    // },

    // mutations: {

    // }
}
