<template>
	<v-overlay :value="loading">
		<v-progress-circular
			indeterminate
			:size="loaderSize"
			:width="width"
			:color="color"
		></v-progress-circular>
	</v-overlay>
</template>
<script>
export default {
	name: "OverlayLoader",
	props: {
		loading: {
			type: Boolean,
			required: false,
			default: false
		},
		width: {
			type: Number,
			required: false,
			default: 5
		},
		color: {
			type: String,
			required: false,
			default: "primary"
		}
	},
	computed: {
		loaderSize() {
			return window.innerWidth / 10 < 50 ? 50 : window.innerWidth / 10;
		}
	}
}
</script>