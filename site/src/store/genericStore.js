import Cookies from 'js-cookie'
const state = {
	api: 'https://dev-dnd.jessprogramming.com:8080',
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
	}
}

export default {
	state,
	actions,
	getters
}