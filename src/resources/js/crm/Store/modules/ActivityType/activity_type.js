import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    ActivityTypeList: []
};

const getters = {
    getActivityType: state => {
        return state.ActivityTypeList
    }
};

const actions = {
    getActivityType({commit}) {
        axiosGet(route('activity_types.index')).then(({data}) => {
            commit('ACTIVITY_TYPE_INFO', data.data)
        }).catch((error) => console.log(error));
    },
};

const mutations = {
    ACTIVITY_TYPE_INFO(state, data) {
        state.ActivityTypeList = data;
    }
};


export default {
    state,
    getters,
    actions,
    mutations
}
