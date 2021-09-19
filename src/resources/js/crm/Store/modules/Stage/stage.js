import { axiosGet } from "../../../Helpers/AxiosHelper";

const state = {
  stageList: [],
};
const getters = {
  getStages: (state) => state.stageList,
};

const mutations = {
  STAGE_INFO(state, data) {
    state.stageList = data;
  },
};

const actions = {
  getStages(context) {
    axiosGet(route('stages.index', {_query: {all: true}}))
      .then((response) => {
        context.commit("STAGE_INFO", response.data);
      })
      .catch((error) => console.log(error));
  },
};

export default {
  state,
  getters,
  mutations,
  actions,
};
