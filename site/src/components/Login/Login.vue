<template>
<v-card>
	<v-card-title>{{options.mode}}</v-card-title>
		<v-form ref="login" class="mx-4" @submit.prevent="login" :disabled="options.disabled">
			<v-card-text>
				<v-overlay
					v-if="options.loading"
					:absolute="options.absoluteOverlay"
				>
					<v-progress-circular indeterminate size="50"></v-progress-circular>
				</v-overlay>
				<StatusBanner ref="status"></StatusBanner>
				<v-row>
					<v-text-field
					label="username"
					v-model="username"
					prepend-icon="mdi-account"
					ref="username"
					autofocus
					></v-text-field>
				</v-row>
				<v-row>
					<v-text-field
					label="password"
					v-model="password"
					prepend-icon="mdi-form-textbox-password"
					type="password"
					></v-text-field>
				</v-row>
			</v-card-text>

			<v-card-actions class="d-flex justify-end">
				<v-btn color="green lighten-2" type="submit">Login</v-btn>
				<v-btn outlined color="red" @click="options.visible = false">Cancel</v-btn>
			</v-card-actions>
		</v-form>
	</v-card>
</template>
<script>
import StatusBanner from '../Utility/StatusBanner.vue';
import cakeApi from "../../services/cakeApi";
import { mapState } from "vuex";

export default {
	name: "Login",
	mounted() {
		this.init();
	},
	props: {
		isDialog: {
			type: Boolean,
			required: false,
			default: false
		}
	},
	data: function() {
		return {
			username: '',
			password: '',
			rememberUsername: false,
			defaults: {
				modes: ['Login', 'Register'],
			},
			options: {
				loading: false,
				mode: '',
				disabled: false,
				absoluteOverlay: true,
			}
		}
	},
	components: {
   		StatusBanner,
	},
	methods: {
		init() {
			this.options.mode = this.defaults.modes[0];
			this.username = '';
			this.password = ''
		},
		async login() {
			this.isLoading(true);
			const response = await cakeApi.login(this.username, this.password);

			if (response.status <= 300) {
				this.$refs.status.setStatus('Success');
				this.$refs.status.setStatusMessage('Success! Redirecting...');
				setTimeout(() => {
					this.options.visible = false
					this.GenericStore.validSession = true;
					this.isLoading(false);
					if (!this.isDialog) {
						this.$router.go(-1);
					}
					},1000);
				this.$emit('loggedin');
			} else {
				console.error(`${response.status}: ${response.statusText}`);
				this.isLoading(false);
				this.$refs.status.setStatus('Fail');
				this.$refs.status.setStatusMessage(`${response.status}: ${response.statusText}`);
				this.password = '';
			}
		},
		isLoading(value) {
			this.$refs.status.init();
			this.options.disabled = value;
			this.loading = value;
		},
		initStatusBanner() {
			this.$refs.status.init()
		}
	},
	computed: {
		...mapState(["GenericStore"])
	}
}
</script>