import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    ownerList: [],
};
const getters = {
    getOwner: state => state.ownerList

};

const mutations = {
    OWNER_INFO(state, data) {
        state.ownerList = data
    }
};

const actions = {
    getOwner({commit}) {
        axiosGet(route('crm.auth_user')).then((response) => {
            commit('OWNER_INFO', response.data)
        }).catch((error) => console.log(error));
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
