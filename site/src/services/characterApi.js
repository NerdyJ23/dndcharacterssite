import { cakeApi } from "./api";

export default {
	getCharacter(id) {
		const response = cakeApi().get(`/characters/${id}`, {
			withCredentials: true
		}).catch((error) => {
			return error.response;
		});
		return response;
	},
	getCharacterList() {
		const response = cakeApi().get(`/characters`, {
			withCredentials: true
		}).catch((error) => {
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
	},
	createCharacter(char) {
		const response = cakeApi().post(`/characters`, char, {
			withCredentials: true
		}).catch((error) => {
			return error.response;
		});
		return response;
	}
}