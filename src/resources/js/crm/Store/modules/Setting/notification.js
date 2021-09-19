import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    edit_event:{},
    channels : [],
};
const getters = {
    editNotificationEvent: state => state.edit_event,
    notificationSettings: state => state.channels
};


const mutations = {
    EDIT_NOTIFICATION_EVENT(state, data){
        state.edit_event = data
    },
    GET_NOTIFICATION_CHANNELS(state, data){
        state.channels = data;
    },
};

const actions = {
    editNotificationEvent({commit}, payload){
        axiosGet(route('notification-events.show', {id: payload})).then(({data}) =>{
            commit('EDIT_NOTIFICATION_EVENT', data)
        })
    },

    getNotificationsChannels({commit, state}){
        commit('SET_LOADER', true);
        axiosGet(route('notification-channels.index')).then(({data}) => {
            commit('GET_NOTIFICATION_CHANNELS',data);
            commit('SET_LOADER', false);
        });
    },
};



export default {
    state,
    getters,
    actions,
    mutations
}
