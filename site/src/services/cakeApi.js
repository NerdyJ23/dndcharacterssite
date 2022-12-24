import { cakeApi } from "./api";

export default {
	login(username, password) {
		let formData = new FormData();
		formData.append('username', username);
		formData.append('password', password);

		const response = cakeApi().post(`/login`, formData).catch((error) => {
			return error.response;
		});
		return response;
	},
	getCharacterList() {
		const response = cakeApi().get(`/characters`).catch((error) => {
			return error.response;
		});
		return response;
	},
	getPublicCharacterList() {
		const response = cakeApi().get(`/characters/list?limit=6`).catch((error) => {
			return error.response;
		});
		return response;
	},
	getPortrait(id) {
		const response = cakeApi().get(`/characters/${id}/image`, {responseType: "blob"}).catch((error) => {
			return error.response;
		});
		return response;
	}
}