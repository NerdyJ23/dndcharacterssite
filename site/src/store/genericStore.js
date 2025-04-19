import Cookies from 'js-cookie';
import cakeApi from "../services/cakeApi";

const state = {
	api: 'https://dnd.jessica-moolenschot.dev:8080',
	site: 'http://localhost',
	months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
	weekday: ['Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
	validSession: false,
  }

const getters = {

}

const actions = {
	logout() {
		Cookies.remove('token', {path:'/'});
		window.location = '/';
	},
	async checkValidSession() {
		const response = await cakeApi.getUserDetails();
		state.validSession = response.status != 403;
	}
}

export default {
	state,
	actions,
	getters
}