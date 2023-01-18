import Vue from 'vue'
import Vuex from 'vuex'

import GenericStore from './genericStore';
import UserStore from './userStore';
import CharacterStore from './characterStore';

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
		GenericStore,
		UserStore,
		CharacterStore
	  }
})
