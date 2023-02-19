<template>
	<div class="dnd-portrait d-flex flex-column">
		<v-skeleton-loader v-if="loading" class="dnd-portrait-image" type="image" loading width="100%"></v-skeleton-loader>
		<v-img v-else :src="portrait" class="dnd-portrait-image" max-width="80%">
			<v-img :src="badge" class="game-badge"></v-img>
		</v-img>
		<span v-if="showName" class="dnd-portrait-text dnd-title dnd-title-bold px-3 rounded-tr-lg">{{ name }}</span>
	</div>
</template>
<script>
import defaultImage from "../../assets/images/characters/default.png";
import DnD from "../../assets/images/characters/dungeons-and-dragons.png";

export default {
	name: "CharacterPortrait",
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
		showName: {
			type: Boolean,
			required: false,
			default: false
		},
		game: {
			type: Number,
			required: false,
			default: 1
		}
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
	},
	computed: {
		portrait() {
			return this.img == null ? defaultImage : this.img;
		},
		badge() {
			switch(this.game) {
				case 1:
					return DnD;
			}
		}
	}
}
</script>