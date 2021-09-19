import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    personList: []
};

const getters = {
    getPerson: state => state.personList
};

const actions = {
    getPerson({commit}) {
        axiosGet(route('persons.index', {_query: {all: true}})).then(({data}) => {
            commit('PERSON_INFO', data)
        }).catch((error) => console.log(error));
    },
};

const mutations = {
    PERSON_INFO(state, data) {
        state.personList = data;
    }
};


export default {
    state,
    getters,
    actions,
    mutations
}
