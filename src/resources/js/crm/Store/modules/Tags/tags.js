import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    tagsList: [],
};
const getters = {
    getAllTags: state => state.tagsList
};

const mutations = {
    TAG_INFO(state, data){
        state.tagsList = data
    }
};

const actions = {
    getAllTags({commit}){
        axiosGet(route('tags.index')).then(({data}) => {
            commit('TAG_INFO', data)
        }).catch((error) => console.log(error))
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
