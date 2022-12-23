<template>
	<v-dialog v-model="active" :max-width="`${width}vw`">
		<v-card>
			<v-card-title class="text-center" :class="color"><slot name="title"></slot></v-card-title>
			<v-card-text class="py-4"><slot name="description"></slot></v-card-text>
			<v-divider/>
			<v-card-actions class="d-flex justify-end py-3">
				<v-btn color="green lighten-2" type="button" @click="$emit('confirm'); hideDialog()">{{ confirmText }}</v-btn>
				<v-btn outlined color="red" type="button" @click="$emit('cancel'); hideDialog()">Cancel</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
export default {
	name:"SimpleDialog",
	props: {
		confirmText: {
			type: String,
			required: false,
			default: "Yes"
		},
		width: {
			type: Number,
			required: false,
			default: 50
		},
		color: {
			type: String,
			required: false,
			default: ""
		}
	},
	data() {
		return {
			active: false
		}
	},
	methods: {
		showDialog() {
			this.active = true;
		},
		hideDialog() {
			this.active = false;
		}
	}
}
</script>