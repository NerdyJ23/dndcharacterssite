<template>
	<v-container fluid>
		<v-row>
			<v-col cols="2">
				<CharacterPageStats v-if="loading" :loading="loading"/>
				<CharacterPageStats v-else :loading="loading"/>
			</v-col>
			<v-col cols="10">
				<CharacterPageInfo v-if="loading" :loading="loading"/>
				<CharacterPageInfo v-else
					:full_name="char.full_name"
					:loading="loading"
				/>
			</v-col>
		</v-row>
	</v-container>
</template>
<script>
import cakeApi from "../services/cakeApi";

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
			const response = await cakeApi.getCharacter(this.$route.params.id);
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