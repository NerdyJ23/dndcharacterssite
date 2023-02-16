<template>
	<v-card elevation="0">
		<v-card-title>Recently Created:</v-card-title>
		<v-card-text>
			<v-row v-if="!loading" elevation="0">
				<v-col xs="12" sm="8" lg="6" v-for="char in characters.list" :key="char.id">
					<CharacterPreview
						:label="char.full_name"
						:race="char.race"
						:desc="char.background.description"
						:id="char.id"
						:exp="char.exp"
						:classes="char.classes"
					/>
				</v-col>
				<v-col v-if="characters.list.length == 0" cols="12">
					No characters? :O
				</v-col>
			</v-row>
			<v-row v-else>
				<v-col xs="12" sm="8" lg="6" v-for="a in 4" :key="a">
					<CharacterPreviewSkeleton />
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>
<script>
import characterApi from "../../services/characterApi"
import CharacterPreview from "./CharacterPreview";
import CharacterPreviewSkeleton from "../Characters/Skeletons/CharacterPreviewSkeleton";
import ExpBar from "./ExpBar";

export default {
	name: "CharacterRecentlyCreatedPreview",
	components: {
		CharacterPreview,
		CharacterPreviewSkeleton,
		ExpBar
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
			const response = await characterApi.getPublicCharacterList();
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