<template>
	<v-card outlined :to="`/characters/${id}`">
		<v-card-text>
			<v-row>
				<v-col cols="4">
					<v-row>
						<v-col cols="12" class="d-flex justify-center align-center">
							<v-img v-if="!loading && (img != null)" :src="img"></v-img>
							<v-icon size="600%" v-else-if="!loading">mdi-account-circle</v-icon>
							<v-skeleton-loader v-else type="image" loading width="100%"></v-skeleton-loader>
						</v-col>
					</v-row>
					<v-row class="text-center dnd-title dnd-title-bold">
						<v-col cols="12">
							{{ label }}
						</v-col>
					</v-row>
				</v-col>

				<v-col cols="8" class="text-center">
					<v-row>
						<v-col cols="12" >{{ race }}</v-col>
						<v-col cols="12">{{ desc }}</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>
<script>
import cakeApi from '../../services/cakeApi';
export default {
	name: "CharacterPreview",
	props: {
		label: {
			type: String,
			required: true
		},
		race: {
			type: String,
			required: true
		},
		desc: {
			type: String,
			required: false,
			default: "A character"
		},
		id: {
			type: String,
			required: true
		}
	},
	data() {
		return {
			loading: true,
			img: null,
		}
	},
	mounted() {
		this.loadProfile();
	},
	methods: {
		async loadProfile() {
			const response = await cakeApi.getPortrait(this.id);
			if (response.status <= 300) {
				this.img = window.URL.createObjectURL(new Blob([response.data]));
			} else if (response >= 500) {
				console.error("error contacting profile image service");
			}
			this.loading = false;
		}
	}
}
</script>