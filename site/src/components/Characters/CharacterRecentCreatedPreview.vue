<template>
	<v-card elevation="0">
		<v-card-text>
			<v-row>
				<v-col cols="3">
					CHARACTER OBJECT HERE
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>
<script>
export default {
	name: "CharacterRecentlyCreatedPreview",
	data() {
		return {
			characters: {
				list: []
			}
		}
	},
	methods: {
		async load() {
			//Load most recent public characters from api
		}
	}
}
</script>