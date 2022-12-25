<template>
	<v-container v-if="!loading">
		<v-row>
			<v-col cols="3"></v-col>
			<v-col cols="6">
				{{ char.full_name }}
			</v-col>
			<v-col cols="3"></v-col>
		</v-row>
	</v-container>
</template>
<script>
import cakeApi from "../../services/cakeApi";
export default {
	name: "CharacterPageInfo",
	components: {

	},
	props: {

	},
	mounted() {
		this.load();
	},
	data() {
		return {
			loading: true,
			char: null,
		}
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
		}
	}
}
</script>