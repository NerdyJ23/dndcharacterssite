import Cookies from 'js-cookie'
import cakeApi from "../services/cakeApi";
const state = {
	api: 'https://dnd.jessprogramming.com:8080',
	site: 'http://localhost',
	months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
	weekday: ['Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
	validSession: false,
  }

const getters = {
	checkValidSession() {
		if(typeof Cookies.get('token') === 'undefined') {
			return false;
		}
		return true;
	}
}

const actions = {
	logout() {
		Cookies.remove('token', {path:'/'});
		window.location = '/';
	},

	async loadImage({commit, state}, id) {
		const response = await cakeApi.getPortrait(id);
		if (response.status <= 300) {
			return window.URL.createObjectURL(new Blob([response.data]));
		} else {
			return null;
		}
	}
}
  export default {
	state,
	actions,
	getters
}