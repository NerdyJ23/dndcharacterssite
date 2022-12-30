<template>
	<v-container v-if="!loading">
		<v-row>
			<v-col cols="3"></v-col>
			<v-col cols="6" class="dnd-title dnd-title-bold">
				{{ full_name }}
			</v-col>
			<v-col cols="3"></v-col>
		</v-row>
	</v-container>
</template>
<script>
export default {
	name: "CharacterPageInfo",
	props: {
		full_name: {
			type: String,
			required: false,
			default: ""
		},
		loading: {
			type: Boolean,
			required: false,
			default: true
		}
	}
}
</script>