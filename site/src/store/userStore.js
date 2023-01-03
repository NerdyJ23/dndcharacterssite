import cakeApi from "../services/cakeApi";

const state = {
	user: {
        username: '',
		first_name: '',
		last_name: ''
    }
  }

const getters = {
  }
const actions = {
	async loadUser() {
		const response = await cakeApi.getUserDetails();
		console.log(response);
		if (response.status <= 300) {
			state.user = response.data.user;
		}
	}
}
  export default {
	state,
	actions,
	getters
}