<template>
	<div>
		<v-card class="mb-4" elevation="0">
			<v-card-title>Public</v-card-title>
			<v-card-text>
				<v-row>
					<template v-if="!loading">
						<v-col v-for="char in publicCharacters" xs="12" sm="8" lg="4" :key="char.id">
							<CharacterPreview
								class="fill-height"
								:label="char.full_name"
								:race="char.race"
								:desc="char.background"
								:id="char.id"
								:exp="char.exp"
							/>
						</v-col>
					</template>
					<template v-else>
						<v-col v-for="item in 4" :key="item">
							<CharacterPreviewSkeleton xs="12" sm="8" lg="4" />
						</v-col>
					</template>
				</v-row>
			</v-card-text>
		</v-card>
		<v-card elevation="0">
			<v-card-title>Private</v-card-title>
			<v-card-text>
				<v-row>
					<template v-if="!loading">
						<v-col v-for="char in privateCharacters" xs="12" sm="8" lg="4" :key="char.id">
							<CharacterPreview
								class="fill-height"
								:label="char.full_name"
								:race="char.race"
								:desc="char.background"
								:id="char.id"
								:exp="char.exp"
							/>
						</v-col>
					</template>
					<template v-else>
						<v-col v-for="item in 4" :key="item">
							<CharacterPreviewSkeleton xs="12" sm="8" lg="4" />
						</v-col>
					</template>
				</v-row>
			</v-card-text>
		</v-card>
	</div>
</template>
<script>
import characterApi from "../services/characterApi";
import CharacterPreview from "../components/Characters/CharacterPreview.vue";
import CharacterPreviewSkeleton from "../components/Characters/Skeletons/CharacterPreviewSkeleton";
import ExpBar from "../components/Characters/ExpBar.vue";

export default {
	name: "MyCharactersPage",
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
			const response = await characterApi.getCharacterList();
			//Load most recent public characters from api
			if (response.status <= 300) {
				this.characters.list = response.data.result;
			} else {
				console.error("oop?");
			}
			this.loading = false;
		}
	},
	computed: {
		publicCharacters() {
			return this.characters.list.filter(char => char.public);
		},
		privateCharacters() {
			return this.characters.list.filter(char => !char.public);
		}
	}
}
</script>