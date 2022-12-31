import Vue from 'vue'
import Vuex from 'vuex'

import GenericStore from './genericStore';
import UserStore from './userStore';

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
		GenericStore,
		UserStore
	  }
})
