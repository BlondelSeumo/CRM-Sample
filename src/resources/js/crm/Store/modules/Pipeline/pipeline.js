import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    pipelineList: [],
};
const getters = {
    getPipeline: state => state.pipelineList

};

const mutations = {
    PIPELINE_INFO(state, data) {
        state.pipelineList = data
    }
};

const actions = {
    getPipeline({commit}) {
        axiosGet(route('pipelines.index', {_query: {all: true}})).then((response) => {
            commit('PIPELINE_INFO', response.data)
        }).catch((error) => console.log(error));
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
