<template>
	<v-progress-linear class="rounded-lg" height="25" :value=" (exp / nextLevel) * 100">
		{{ exp }} / {{ nextLevel }}
	</v-progress-linear>
</template>
<script>
import characterApi from '@/services/characterApi';
import {mapState} from 'vuex';

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
		nextLevel() {
			for(let level of this.CharacterStore.expBreakpoints) {
				if (this.exp < level) {
					return level;
				}
			}
			return '∞';
		},
		width() {
			return (this.exp / this.nextLevel) * 100;
		}
	}

}
</script>