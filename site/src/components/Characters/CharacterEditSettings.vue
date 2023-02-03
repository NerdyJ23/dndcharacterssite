<template>
	<v-container>
		<v-row>
			<v-checkbox v-model="char.public" label="Public"></v-checkbox>
		</v-row>
	</v-container>
</template>
<script>
	export default {
		name: "CharacterEditSettings",
		props: {
			char: {
				type: Object,
				required: true
			},
			loading: {
				type: Boolean,
				required: false,
				default: false
			}
		}
	}
</script>