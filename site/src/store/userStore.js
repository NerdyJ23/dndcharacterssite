import cakeApi from "../services/cakeApi";

const state = {
	user: {
        name: "",
        id: ""
    }
  }

const getters = {
  }
const actions = {
	async loadUser() {
		const response = await cakeApi.getUser();
	}
}
  export default {
	state,
	actions,
	getters
}