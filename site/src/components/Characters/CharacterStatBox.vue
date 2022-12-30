<template>
	<div class="stat-box d-flex flex-column pb-4">
		<span class="stat-box-title">{{ label }}</span>
		<span class="stat-box-value">{{ value }}</span>
		<span v-if="$vuetify.breakpoint.sm"> {{ prefix }} {{ modifier }}</span>
		<span v-else class="stat-box-modifier" style="display:block">{{ prefix }} {{ modifier }}</span>
	</div>
</template>
<script>
export default {
	name:"CharacterStatBox",
	props: {
		stat: {
			type: String,
			required: true,
		},
		value: {
			type: Number,
			required: true
		},
	},
	computed: {
		modifier() {
			return Math.floor((this.value - 10) / 2);
		},
		prefix() {
			if (0 >= this.modifier) {
				return;
			}
			return '+';
		},
		label() {
			if (this.$vuetify.breakpoint.sm) {
				return this.stat.substr(0,3);
			}
			return this.stat;
		}
	}
}
</script>
<style lang="scss" scoped>
	.stat-box {
		text-align: center;
		position: relative;
		border-radius: 10px !important;
		border: 3px solid var(--v-secondary-base);
		background-color: white;
		// border-image: url('~@/assets/images/stat-box/border.svg') 120 stretch;

		&-title {
			margin-bottom: 2px;
			border-bottom: 1px solid var(--v-secondary-base);
			margin-top: 0;
		}

		&-value {
			margin: 5px 0 5px 0;
		}

		&-modifier {
			position: absolute;
			top: 80%;
			left: 12px;
			right: 12px;
			margin-left: auto;
			margin-right: auto;
			background-color: white;
			border: 2px solid var(--v-secondary-base);
			border-radius: 50%;
		}
	}
</style>