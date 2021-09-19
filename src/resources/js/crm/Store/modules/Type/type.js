import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    typeLists: []
};
const getters = {
    contentType: state => state.typeLists

};

const mutations = {
    CONTACT_TYPE_INFO(state, data) {
        state.typeLists = data
    }
};

const actions = {
    contentType(context) {
        axiosGet(route('types.index')).then((response) => {
            context.commit('CONTACT_TYPE_INFO', response.data.data)
        }).catch((error) => console.log(error));
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
