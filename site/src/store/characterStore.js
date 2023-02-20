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
	],
	statTemplates: [
		{
			id: 0,
			name: "DnD 5e",
			list: ["Strength", "Dexterity", "Constitution", "Intelligence", "Wisdom", "Charisma"]
		},
		{
			id: 1,
			name: "Random",
			list: ["A", "B", "C"]
		}
	],
	gameTypes: {
		dnd: 0,
		callOfCthulu: 1,
		eclipsePhase: 2
	}

}
const actions = {
	async loadImage({commit, state}, id) {
		const response = await characterApi.getPortrait(id);
		if (response.status <= 300 && response.status != 204) {
			return window.URL.createObjectURL(new Blob([response.data]));
		} else {
			return null;
		}
	},
}

const getters = {
	nextLevel() {
		return (exp) => {
			for(let level of state.expBreakpoints) {
				if (exp < level) {
					return level;
				}
			}
			return '∞';
		}
	},
}
export default {
	state,
	actions,
	getters
}