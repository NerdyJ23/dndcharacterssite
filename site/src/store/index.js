import Vue from 'vue'
import Vuex from 'vuex'

import GenericStore from './genericStore';
Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
		GenericStore
	  }
})
