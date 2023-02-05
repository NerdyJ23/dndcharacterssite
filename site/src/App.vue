<template>
  <v-app>
    <v-main>
		<Navbar @toggleDrawer="toggleDrawer" ref="navbar" @login="showLogin" @logout="logout" />
		<v-divider></v-divider>
		<v-card class="d-flex" elevation="0">
			<router-view v-if="show" style="width:auto" class="pl-10 col-12"></router-view>
		</v-card>
		<Login ref="login" @loggedin="loadUser"/>
    </v-main>
  </v-app>
</template>

<script>
import Navbar from './components/Utility/Navbar';
import NavigationDrawer from './components/Utility/NavigationDrawer';
import { mapState } from "vuex";
import Login from './components/Login/LoginDialog';

export default {
	async mounted() {
		this.$vuetify.theme.dark = true;
		await this.$store.dispatch('checkValidSession');
		this.show = true;
	},
	data() {
		return {
			show: false
		}
	},
  	name: 'App',
  	components: {
		Navbar,
		NavigationDrawer,
		Login
  	},
	methods: {
		toggleDrawer() {
			this.$refs.drawer.toggle();
		},
		showLogin() {
			this.$refs.login.show();
		},
		logout(){
			this.$store.dispatch('logout');
		},
		loadUser() {
			this.$store.dispatch('loadUser');
		},
		needSession() {
			if (!this.GenericStore.validSession) {
				this.$router.push("/login");
			}
		}
	},
	computed: {
		...mapState(["GenericStore"])
	},
	provide() {
		return {
			needSession: this.needSession
		}
	}
}
</script>

<style lang="scss">
.sticky-bar{
	position: sticky !important;
	bottom: 0;
	opacity: 0.7;
	&:hover {
		opacity:1;
	}
}
</style>