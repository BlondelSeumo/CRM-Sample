import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    phoneList: [],
};
const getters = {
    getPhoneNumber: state => state.phoneList
};

const mutations = {
    PHONE_INFO(state, data) {
        state.phoneList = data
    }
};

const actions = {
    getPhoneNumber({commit}) {
        axiosGet(route('phone_list')).then(({data}) => {
            commit('PHONE_INFO', data)
        }).catch((error) => console.log(error))
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
