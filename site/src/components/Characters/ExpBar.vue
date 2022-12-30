<template>
	<v-progress-linear class="rounded-lg" height="25" :value=" (exp / nextLevel) * 100">
		{{ exp }} / {{ nextLevel }}
	</v-progress-linear>
</template>
<script>
export default {
	name: "ExpBar",
	props: {
		exp: {
			type: Number,
			required: false,
			default: 0
		}
	},
	data() {
		return {
			expList: [
				0,
				300,
				900,
				2700,
				6500,
				14000,
				23000,
				34000,
				48000,
				64000,
				85000,
				100000,
				120000,
				140000,
				165000,
				195000,
				225000,
				265000,
				305000,
				335000
			]
		}
	},
	computed: {
		nextLevel() {
			for(let level of this.expList) {
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