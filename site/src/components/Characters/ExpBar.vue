<template>
	<v-card class="mx-4 rounded-lg">
		<div class="exp">

			<div class="exp-background text-center rounded-l-lg" :style="`width: ${width}%;z-index: 2`">&nbsp;</div>
			<div class="exp-text text-center" style="z-index: 1">
				<span class="text-center" style="width:inherit; color:black;">
					{{ exp }} / {{ nextLevel }}
				</span>
			</div>
		</div>
	</v-card>
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
<style lang="scss" scoped>
.exp {
	position: relative;
	&-background {
		border: 2px solid var(--v-primary-darken2);
		position: relative;
		top: 0;
		left: 0;
		background-color: var(--v-primary-base);
		height: 100%;
		padding: 0;
		margin: 0;
	}
	&-text {
		display:block;
		position: absolute;
		top: 0;
		width:100%;
		color: white;
	}
}
</style>