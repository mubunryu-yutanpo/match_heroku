// Vueでフラッシュメッセージを表示する（結局使わなそう）

import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
  flashMessage: '',
  flashMessageType: '',
};

const mutations = {
  SET_FLASH_MESSAGE(state, message) {
    state.flashMessage = message;
  },
  SET_FLASH_MESSAGE_TYPE(state, type) {
    state.flashMessageType = type;
  },
};

const actions = {
  setFlashMessage({ commit }, message) {
    commit('SET_FLASH_MESSAGE', message);
  },
  setFlashMessageType({ commit }, type) {
    commit('SET_FLASH_MESSAGE_TYPE', type);
  },
};

export default new Vuex.Store({
  state,
  mutations,
  actions,
});
