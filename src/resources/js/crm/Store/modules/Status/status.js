import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    statusList: [],
    proposalStatusList: [],
    dealStatusList: [],
    activityStatusList: [],

};

const getters = {
    getStatus: state => state.statusList,
    getProposalStatus: state => state.proposalStatusList,
    getDealStatus: state => state.dealStatusList,
    getActivityStatus: state => state.activityStatusList
};
const mutations = {
    STATUS_INFO(state, data) {
        state.statusList = data;
    },
    PROPOSAL_STATUS_INFO(state, data) {
        state.proposalStatusList = data;
    },
    ACTIVITY_STATUS_INFO(state, data) {
        state.activityStatusList = data;
    },
    DEAL_STATUS_INFO(state, data) {
        state.dealStatusList = data
    }
};

const actions = {
    getStatus({commit}) {
        axiosGet(route('statuses.index')).then(response => {
            commit('STATUS_INFO', response.data)
        }).catch((error) => console.log(error));
    },
    getProposalStatus({commit}) {
        axiosGet(route('statuses.index', {_query: {type: "proposal"}})).then(response => {
            commit('PROPOSAL_STATUS_INFO', response.data)
        }).catch((error) => console.log(error));
    },
    getActivityStatus({commit}) {
        axiosGet(route('statuses.index', {_query: {type: "activity"}})).then(response => {
            commit('ACTIVITY_STATUS_INFO', response.data)
        }).catch((error) => console.log(error));
    },
    getDealStatus({commit}) {
        axiosGet(route('statuses.index', {_query: {type: "deal"}})).then(response => {
            commit('DEAL_STATUS_INFO', response.data)
        }).catch((error) => console.log(error));
    },
};


export default {
    state,
    getters,
    actions,
    mutations
}
