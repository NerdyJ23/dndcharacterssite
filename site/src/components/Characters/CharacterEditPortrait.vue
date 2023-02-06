<template>
	<v-container>
		<CharacterPortrait :id="id" style="max-width: 20%" ref="portrait"/>
		<v-btn v-if="hasImage" @click="clearPortrait">Reset Image</v-btn>
		<v-btn @click="$refs.profileUpload.click()">
			<v-icon>mdi-cloud</v-icon>Upload Image
		</v-btn>
		<input type="file" ref="profileUpload" />
	</v-container>
</template>
<script>
import CharacterPortrait from './CharacterPortrait.vue';
import characterApi from '../../services/characterApi';

export default {
	name: "CharacterEditPortrait",
	props: {
		id: {
			type: String,
			required: true
		}
	},
	components: {
		CharacterPortrait
	},
	methods: {
		clearPortrait() {
			console.log('clicked');
			this.$refs.portrait.img = null;
		},
		uploadImage() {
			if (this.$refs.profileUpload.files.length != 0) {
				const response = characterApi.uploadImage(this.id, this.$refs.profileUpload.files[0]);
				if (response.status != 204) {
					console.error("couldnt upload image");
				}
			}
		}
	},
	computed: {
		hasImage() {
			if(this.$refs.portrait) {
				return this.$refs.portrait.img != null;
			}
			return false;
		}
	}
}
</script>