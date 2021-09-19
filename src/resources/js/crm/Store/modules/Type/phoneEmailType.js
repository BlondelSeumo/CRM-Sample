import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    phoneEmailTypeLists: []
};
const getters = {
    phoneEmailType: state => state.phoneEmailTypeLists

};

const mutations = {
    PHONE_EMAIL_TYPE_INFO(state, data) {
        state.phoneEmailTypeLists = data
    }
};

const actions = {
    phoneEmailType(context) {
        axiosGet(route('phone_email.type')).then((response) => {
            context.commit('PHONE_EMAIL_TYPE_INFO', response.data)
        }).catch((error) => console.log(error));
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
