import characterApi from "../services/characterApi";
const state = {
	expBreakpoints: [
		0,
		300,
		900,
		2700,
		6500,
		14000,
		23000,
		34000,
		48000,
		64000,
		85000,
		100000,
		120000,
		140000,
		165000,
		195000,
		225000,
		265000,
		305000,
		335000
	]
}
const actions = {
	async loadImage({commit, state}, id) {
		const response = await characterApi.getPortrait(id);
		if (response.status <= 300 && response.status != 204) {
			return window.URL.createObjectURL(new Blob([response.data]));
		} else {
			return null;
		}
	}
}

export default {
	state,
	actions,
}