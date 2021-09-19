import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    DealList: [],
    dealId: window.localStorage.getItem('dealId'),
    dealIdClear: window.localStorage.removeItem('dealId')
};

const getters = {
    getDeal: state => state.DealList,
    setProposalDealId: state => state.dealId,
};

const actions = {
    getDeal({commit}){
        axiosGet(route('deals.index', {_query: {all: true}})).then(({data}) => {
            commit('DEAL_INFO', data)
        }).catch((error) => console.log(error))
    },
    setProposalDealId({commit}, payload){
        commit('SET_DEAL_ID', payload)
    },
    clearDealID({commit}){
        commit('CLEAR_DEAL_ID')
    }
};

const mutations = {
    DEAL_INFO(state, data) {
        state.DealList = data;
    },
    SET_DEAL_ID(state, payload){
        state.dealId = payload;
        window.localStorage.setItem('dealId', payload);
    },
    CLEAR_DEAL_ID(state){
        state.dealIdClear = '';
    }
};


export default {
    state,
    getters,
    actions,
    mutations
}
