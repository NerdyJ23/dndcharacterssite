<template>
	<v-dialog
	v-model="options.visible"
	max-width="75vw"
	>
	<Login ref="login"/>
	</v-dialog>
</template>

<script>
import Login from "./Login.vue";

export default {
	name: "LoginDialog",
	components: {
		Login
	},
	data: function() {
		return {
			options: {
				visible: false,
			}
		}
	},
	methods: {
		show() {
			this.options.visible = true;
			setTimeout(() => {this.$refs.login.initStatusBanner()}, 1000);
		},
		hide() {
			this.options.visible = false;
		},
	}
}
</script>