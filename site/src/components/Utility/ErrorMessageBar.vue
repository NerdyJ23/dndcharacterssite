<template>
	<v-snackbar color="red" v-model="show" :timeout="timeout">
		<span class="d-flex justify-center">
			{{ message }}
		</span>
	</v-snackbar>
</template>
<script>
export default {
	name: "ErrorMessageBar",
	props: {
		show: {
			type: Boolean,
			required: false,
			default: false
		},
		message: {
			type: String,
			required: false,
			default: "An error has occured"
		},
		timeout: {
			type: Number,
			required: false,
			default: 2000
		}
	}
}
</script>