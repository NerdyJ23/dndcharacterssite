<template>
<div>
	<v-toolbar elevation="1">
		<!-- <v-btn plain @click="$emit('toggleDrawer')"><v-icon>mdi-menu</v-icon></v-btn> -->
		<v-toolbar-title class="text-h5">
			<v-btn to="/" plain>DnD Character Site</v-btn>
		</v-toolbar-title>
		<v-spacer></v-spacer>
		<div v-if="GenericStore.validSession">
			<v-btn to="/receipt" plain>My Characters</v-btn>
			<v-btn to="/profile" plain>{{ UserStore.name }}</v-btn>
			<v-btn plain @click="$emit('logout')">Logout</v-btn>
		</div>
		<div v-else>
			<v-btn @click="$emit('login')" plain>Login</v-btn>
		</div>
	</v-toolbar>
</div>
</template>

<script>

import { mapState } from "vuex";

export default {
	name: "Navbar",
	computed: {
		...mapState(["GenericStore"]),
		...mapState(["UserStore"])
	}
}
</script>
