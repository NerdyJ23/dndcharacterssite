<template>
	<v-progress-linear class="rounded-lg" height="25" :value=" (exp / nextLevel) * 100">
		{{ exp }} / {{ nextLevel }}
	</v-progress-linear>
</template>
<script>
import { mapState, mapGetters} from 'vuex';

export default {
	name: "ExpBar",
	props: {
		exp: {
			type: Number,
			required: false,
			default: 0
		}
	},
	computed: {
		...mapState(["CharacterStore"]),
		...mapGetters({
			getNextLevel: "nextLevel"
		}),
		width() {
			return (this.exp / this.nextLevel) * 100;
		},
		nextLevel() {
			return this.getNextLevel(this.exp);
		}
	}

}
</script>