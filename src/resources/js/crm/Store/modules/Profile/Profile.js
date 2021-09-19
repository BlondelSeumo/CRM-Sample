import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    userprofile:{}
};

const getters = {
    getUserInformation: state => {
        return state.userprofile
    }
};

const actions = {
    getUserInformation({commit, state}) {
        axiosGet(route('user.authenticate-user')).then(({ data }) => {
            commit('PROFILE_PERSONAL_INFO', data)
        }).catch((error) => console.log(error));
    },

};

const mutations = {
    PROFILE_PERSONAL_INFO(state, data) {
        state.userprofile = data;
    }
};


export default {
    state,
    getters,
    actions,
    mutations
}
