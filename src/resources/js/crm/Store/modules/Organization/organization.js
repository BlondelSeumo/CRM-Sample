import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    organizationList: []
};
const getters = {
    getOrganization: state => state.organizationList
};
const mutations = {
    SET_ORGANIZATION_INFO(state, data){
        state.organizationList = data
    }
};
const actions = {
    getOrganization({commit}){
        axiosGet(route('organizations.index', {_query: {all: true}})).then(({data}) => {
            commit('SET_ORGANIZATION_INFO', data)
        }).catch((error) => console.log(error))
    }
};



export default {
    state,
    getters,
    actions,
    mutations
}
