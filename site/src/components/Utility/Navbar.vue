<template>
<div>
	<v-toolbar elevation="1">
		<v-toolbar-title class="text-h5">
			<v-btn to="/" plain>DnD Character Site</v-btn>
		</v-toolbar-title>
		<v-spacer></v-spacer>
		<div v-if="GenericStore.validSession">
			<v-menu
				open-on-hover
				offset-y
				bottom
				close-on-click
				close-on-content-click
			>
				<template v-slot:activator="{on, attrs}">
					<v-btn plain v-bind="attrs" v-on="on">
						<v-icon>mdi-bookshelf</v-icon><span class="hidden-sm-and-down">Characters</span>
					</v-btn>
				</template>
				<v-list flat>
					<v-list-item to="/characters">
						<v-list-item-content>My Characters</v-list-item-content>
					</v-list-item>

					<v-list-item to="/characters/create">
						<v-list-item-content>Create New Character</v-list-item-content>
					</v-list-item>
				</v-list>
			</v-menu>
			<v-menu open-on-hover offset-y bottom>
				<template v-slot:activator="{on, attrs}">
					<v-btn plain v-bind="attrs" v-on="on">
						<v-icon>mdi-account-circle</v-icon><span class="hidden-sm-and-down">{{ UserStore.user.username }}</span>
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
	},
	watch: {
		"GenericStore.validSession": {
			handler() {
				if (this.GenericStore.validSession) {
					this.$store.dispatch('loadUser');
				}
			}, immediate: true
		}
	}
}
</script>
