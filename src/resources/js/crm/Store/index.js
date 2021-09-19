import Vue from 'vue'
import Vuex from 'vuex'
import Profile from "./modules/Profile/Profile";
import ActivityType from "./modules/ActivityType/activity_type";
import Person from "./modules/Person/person";
import Tags from "./modules/Tags/tags";
import ContactType from "./modules/Type/type";
import PhoneEmailType from "./modules/Type/phoneEmailType";
import Owner from "./modules/Owner/owner";
import Status from "./modules/Status/status";
import Additional from "./modules/Additional/additional";
import Pipeline from "./modules/Pipeline/pipeline";
import Stage from "./modules/Stage/stage";
import Organization from "./modules/Organization/organization";
import Example from './modules/Example';
import Deal from './modules/Deal/deals';
import Notification from './modules/Setting/notification';
import CheckEmailSetting from './modules/Setting/checkEmailDeliverySetting';
import PhoneNumber from './modules/Phone/phones';
import Language from './modules/Language/language';
import {formatted_date, formatted_time} from "../Helpers/helpers";

Vue.use(Vuex);


export default new Vuex.Store({
    state: {
        loading: false,
        settings: {
            dateFormat: formatted_date(),
            timeFormat: parseInt(formatted_time()),
        },
        theme: {
            darkMode: false
        }

    },
    mutations: {
        SET_LOADER(state, data) {
            state.loading = data;
        }
    },
    modules: {
        Profile,
        ActivityType,
        Person,
        Tags,
        ContactType,
        PhoneEmailType,
        Owner,
        Status,
        Additional,
        Pipeline,
        Stage,
        Organization,
        Example,
        Deal,
        Notification,
        CheckEmailSetting,
        PhoneNumber,
        Language
    }
});
