import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    languageData: [],
};
const getters = {
    getLocaleOptions: state => state.languageData
};

const mutations = {
    LANGUAGE_INFO(state, data) {
        state.languageData = data
    }
};

const actions = {
    getLocaleOptions({commit}) {
        axiosGet(route('lang.index')).then(({data}) => {
            commit('LANGUAGE_INFO', data)
        }).catch((error) => console.log(error))
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
