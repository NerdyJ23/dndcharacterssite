<template>
	<v-container>
		<v-btn v-for="index in pageCount" @click="$emit('pageChanged', index)">{{ index }}</v-btn>
	</v-container>
</template>
<script>
export default {
	name: "Pagination",
	props: {
		limit: {
			type: Number,
			required: false,
			default: 30
		},
		total: {
			type: Number,
			required: false,
			default: 0
		}
	},
	data() {
		return {
			page: 1
		}
	},
	computed: {
		pageCount() {
			return Math.ceil(this.total / this.limit);
		}
	}
}
</script>