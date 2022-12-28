<template>
	<v-card outlined>
		<v-card-title>{{ stat }}</v-card-title>
		<v-card-text>
			{{ value }}
		</v-card-text>
		<v-card-actions>
			{{ prefix }} {{ modifier }}
		</v-card-actions>
	</v-card>
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
		}
	}
}
</script>