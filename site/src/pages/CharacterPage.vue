<template>
	<v-container fluid>
		<v-row>
			<v-col cols="3">
				<CharacterPageStats v-if="loading" :loading="loading"/>
				<CharacterPageStats v-else :loading="loading" :stats="char.stats"/>
			</v-col>
			<v-col>
				<CharacterPageInfo v-if="loading" :loading="loading"/>
				<CharacterPageInfo v-else
					:char="char"
					:loading="loading"
				/>
			</v-col>
		</v-row>
	</v-container>
</template>
<script>
import characterApi from "../services/characterApi";

import CharacterPageInfo from "../components/Characters/CharacterPageInfo";
import CharacterPageStats from "../components/Characters/CharacterPageStats";

export default {
	name: "CharacterPage",
	components: {
		CharacterPageInfo,
		CharacterPageStats
	},
	data() {
		return {
			loading: true,
			char: null,
		}
	},
	mounted() {
		this.load();
	},
	methods: {
		async load() {
			const response = await characterApi.getCharacter(this.$route.params.id);
			console.log(response.data);
			if (response.status <= 300) {
				this.char = response.data.result;
			} else {
				console.error("character loaded failed");
			}
			this.loading = false;
		},
		characterAttr(attr) {
			if (this.char !== null && this.char[attr] !== undefined) {
				return this.char[attr];
			}
			return '';
		}
	}
}
</script>