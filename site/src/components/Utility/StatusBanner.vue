<template>
	<v-snackbar
	class="text-body-1 white--text rounded-lg pa-2"
	:color="isFail ? 'red' : 'green'"
	v-model="showBanner"
	:timeout="2000"
	>
		<v-icon v-if="isFail" color="white">mdi-close-circle</v-icon> &nbsp;
		<v-icon v-if="isSuccess" color="white">mdi-check-circle</v-icon> &nbsp;
		<span class="align-end">{{statusText}}</span>
	</v-snackbar>
</template>
<script>
export default {
	props: {
		statusP: {
			type: String,
			required: false
		},
		statusTextP: {
			type: String,
			required: false
		}
	},
	mounted() {
		this.init();
	},
	data: function () {
		return {
			status: '',
			statusText: '',
			defaults: {
				status: ['None', 'Success', 'Fail']
			}
		}
	},
	methods: {
		init() {
			if(this.isValidStatus(this.statusP)) {
				this.status = this.statusP;
			} else {
				this.status = this.defaults.status[0];
			}
			if(typeof this.statusTextP !== 'undefined') {
				this.statusText = this.statusTextP;
			} else {
				this.statusText = '';
			}
		},
		setStatus(status) {
			if(!this.isValidStatus(status)) {
				return;
			}
			switch(status.toLowerCase()) {
				case this.defaults.status[0].toLowerCase():
					this.status = this.defaults.status[0];
					break;
				case this.defaults.status[1].toLowerCase():
					this.status = this.defaults.status[1];
					break;
				case this.defaults.status[2].toLowerCase():
					this.status = this.defaults.status[2];
					break;
			}
		},
		isValidStatus(status) {
			if(typeof status !== 'undefined') {
				if(status.toLowerCase() === this.defaults.status[0].toLowerCase()
				|| status.toLowerCase() === this.defaults.status[1].toLowerCase()
				|| status.toLowerCase() === this.defaults.status[2].toLowerCase()) {
					return true;
				}
			}
			return false;
		},
		setStatusMessage(text) {
			this.statusText = text;
		}
	},
	computed: {
		isFail() {
			return this.status === this.defaults.status[2];
		},
		isSuccess() {
			return this.status === this.defaults.status[1];
		},
		showBanner() {
			return this.isFail || this.isSuccess;
		}
	}
}
</script>