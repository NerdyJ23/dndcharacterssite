<template>
	<v-container>
		<v-btn v-for="a in pageCount" @click="$emit('pageChanged', a)">{{ a }}</v-btn>
	</v-container>
</template>
<script>
export default {
	name: "Pagination",
	props: {
		page: {
			type: Number,
			required: false,
			default: 1
		},
		limit: {
			type: Number,
			required: false,
			default: 30
		},
		count: {
			type: Number,
			required: false,
			default: 0
		}
	},
	computed: {
		pageCount() {
			return (this.limit * this.page / this.count);
		}
	}
}
</script>