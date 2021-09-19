import {axiosGet} from "../../../Helpers/AxiosHelper";
import * as dateTimeFunc from '../../../Helpers/DateTimeHelper';
import {urlGenerator} from  "@app/Helpers/helpers";

const state = {
    typeList: [],
    defaulStagetList: [],
    appTypeList: [],
    users: [],
    userRole: [],
    customFieldTypeList: [],
    topNotificationList: [],
    templateList: [],
    customFiledList: [],
    roleList:[],
    countryList:[]

};
const getters = {
    getPhoneEmailType: state => state.typeList,
    getDefaultStage: state => state.defaulStagetList,
    getAllAppTypeList: state => state.appTypeList,
    getUsers: state => state.users,
    getUsersAndRoles: state => state.userRole,
    customFieldTypeList: state => state.customFieldTypeList,
    getAllNotification: state => state.topNotificationList,
    getTemplate: state => state.templateList,
    getCustomFiled: state => state.customFiledList,
    getRole: state => state.roleList,
    getCountry: state => state.countryList
};

const mutations = {
    PHONE_EMAIL_TYPE(state, data) {
        state.typeList = data
    },
    DEFAULT_STAGE_INFO(state, data) {
        state.defaulStagetList = data
    },
    APP_TYPE_INFO(state, data) {
        state.appTypeList = data
    },
    SET_USERS(state, data) {
        state.users = data
    },
    SET_USERS_ROLES(state, data) {
        state.userRole = data
    },
    SET_CUSTOM_FIELD_INFO(state, data) {
        state.customFieldTypeList = data
    },
    TOP_NOTIFICATION_LIST(state, data) {

        state.topNotificationList = data.map(item => {
            const {id, data, notifier, created_at, read_at} = item,
                profile_picture = notifier?.profile_picture;

            return {
                id: id,
                img: !_.isEmpty(profile_picture) ? urlGenerator(profile_picture.path) : '',
                name: data.name,
                title: data.message,
                description: '',
                time: dateTimeFunc.getTimeFromDateTime(created_at, settings.timeFormat),
                //time: dateTimeFunc.getDateFromNow(created_at, settings.timeFormat),
                date: dateTimeFunc.getDateFromNow(created_at, settings.dateFormat),
                //status: read_at ? settings['old'] : settings['new'],
                status: read_at ? 'old' : 'new',
                url: urlGenerator(data.url)
            }
        })
    },
    TEMPLATE_LIST(state, data) {
        state.templateList = data
    },
    CUSTOM_FILED_LIST(state, data){
        state.customFiledList = data
    },
    SET_ROLE_INFO(state, data){
        state.roleList = data
    },
    SET_COUNTRY_INFO(state, data){
        state.countryList = data
    }
};
const actions = {
    getPhoneEmailType(context) {
        axiosGet(route('phone_email.type')).then(response => {
            context.commit('PHONE_EMAIL_TYPE', response.data)
        }).catch((error) => console.log(error));
    },
    getDefaultStage(context) {
        axiosGet(route('stages-default.index')).then(response => {
            context.commit('DEFAULT_STAGE_INFO', response.data)
        }).catch((error) => console.log(error));
    },
    getAllAppTypeList({commit}) {
        axiosGet(route('types.index')).then(({data}) => {
            commit('APP_TYPE_INFO', data)
        }).catch((error) => console.log(error));
    },
    getUsers({commit, state}, payload = {}) {
        const url = payload.users ? `${route('users.index')}?existing=${payload.users}` : route('users.index');
        axiosGet(url).then(({data}) => {
            commit('SET_USERS', data)
        }).catch((error) => console.log(error));
    },
    getUsersAndRoles({commit}) {
        axiosGet(route('core.users.index')).then((response) => {
            commit('SET_USERS_ROLES', response.data.data)
        })
    },
    addUser({commit, state}, user) {
        commit('SET_USERS', state.users.concat([user]));
    },
    getCustomFieldType({commit}) {
        axiosGet(route('custom-fields.types')).then(({data}) => {
            commit('SET_CUSTOM_FIELD_INFO', data)
        }).catch((error) => console.log(error));
    },
    getAllNotification({commit}) {
        axiosGet(route('user-notifications.index')).then(({data}) => {
            commit('TOP_NOTIFICATION_LIST', data.data)
        })
    },
    getTemplate({commit}) {
        axiosGet(route('templates.index')).then(({data}) => {
            commit('TEMPLATE_LIST', data.data)
        })
    },
    getCustomFiled({commit}, {type: type}) {
        axiosGet(route('custom-fields', {_query: {type: type}})).then((response) => {
           commit('CUSTOM_FILED_LIST', response.data)
        })
    },
    getRole({commit}){
        axiosGet(route('core.roles.index')).then(({data}) => {
            commit('SET_ROLE_INFO', data.data)
        })
    },
    getAllCountry({commit}){
        axiosGet(route('countries')).then(({data}) => {
            commit('SET_COUNTRY_INFO', data)
        })
    }

};


export default {
    state,
    getters,
    mutations,
    actions
}
