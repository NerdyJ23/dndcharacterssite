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
			<v-menu open-on-hover offset-y bottom>
				<template v-slot:activator="{on, attrs}">
					<v-btn plain v-bind="attrs" v-on="on">
						<v-icon>mdi-account-circle</v-icon>{{ UserStore.user.username }}
					</v-btn>
				</template>
				<v-list>
					<v-list-item to="/profile">
						<v-list-item-content>Profile</v-list-item-content>
					</v-list-item>

					<v-list-item @click="$emit('logout')">
						<v-list-item-content>Logout</v-list-item-content>
					</v-list-item>
				</v-list>
			</v-menu>
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
