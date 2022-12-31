<template>
	<div class="dnd-portrait d-flex flex-column">
		&nbsp;
		<v-img v-if="img != null" :src="img" class="dnd-portrait-image" max-width="30%"></v-img>
		<v-skeleton-loader v-else class="dnd-portrait-image" type="image" loading style="width:40%; height: 200px"></v-skeleton-loader>
		<span class="dnd-portrait-text dnd-title dnd-title-bold px-3 rounded-tr-lg">{{ name }}</span>
	</div>
</template>
<script>
	export default {
		name: "CharacterPortraitWithName",
		props: {
			name: {
				type: String,
				required: false,
				default: ""
			},
			id: {
				type: String,
				required: true
			},
		},
		data() {
			return {
				loading: true,
				img: null
			}
		},
		mounted() {
			this.loadProfile();
		},
		methods: {
			async loadProfile() {
				await this.$store.dispatch('loadImage', this.id).then((item) => {
				this.img = item;
			});
			this.loading = false;
			}
		}
	}
</script>
<style lang="scss" scoped>
.dnd-portrait {
	position: relative;
	&-image {
		border: 1px solid var(--v-secondary-base);
	}

	&-text {
		background-color: white;
		border: 1px solid var(--v-secondary-base);
		position: absolute;
		bottom: 0;
	}
}
</style>