<template>
	<v-row v-if="!loading" elevation="0">
		<v-col xs="12" sm="8" lg="6" v-for="char in characters.list">
			<CharacterPreview
				:label="char.Full_Name"
				:race="char.Race"
				:desc="char.Background"
				:id="char.id"
			/>
		</v-col>
		<v-col v-if="characters.list.length == 0" cols="12">
			No characters? :O
		</v-col>
	</v-row>
</template>
<script>
import cakeApi from "../../services/cakeApi"
import CharacterPreview from "./CharacterPreview";

export default {
	name: "CharacterRecentlyCreatedPreview",
	components: {
		CharacterPreview
	},
	data() {
		return {
			characters: {
				list: []
			},
			loading: true
		}
	},
	mounted() {
		this.load();
	},
	methods: {
		async load() {
			const response = await cakeApi.getPublicCharacterList();
			//Load most recent public characters from api
			if (response.status <= 300) {
				this.characters.list = response.data.result;
			} else {
				console.error("oop?");
			}
			this.loading = false;
		}
	}
}
</script>