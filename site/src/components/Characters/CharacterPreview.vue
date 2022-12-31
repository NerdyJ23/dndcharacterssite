<template>
	<v-card outlined :to="`/characters/${id}`">
		<v-card-text>
			<v-row>
				<v-col cols="4" class="d-flex justify-center align-center">
					<v-img v-if="!loading && (img != null)"
						:src="img"
						height="100%"
					></v-img>
					<v-icon size="600%" v-else-if="!loading">mdi-account-circle</v-icon>
					<v-skeleton-loader v-else type="image" loading width="100%"></v-skeleton-loader>
				</v-col>

				<v-col cols="8" class="text-center">
					<v-row>
						<v-col cols="12" class="dnd-title dnd-title-bold">{{ label }}</v-col>
						<v-col cols="12" >{{ race }}</v-col>
						<v-col cols="12">{{ desc }}</v-col>
						<v-col cols="12">
							<ExpBar :exp="exp"/>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>
<script>
import ExpBar from "./ExpBar";
import { mapGetters } from "vuex";

export default {
	name: "CharacterPreview",
	components: {
		ExpBar
	},
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
		},
		exp: {
			type: Number,
			required: false,
			default: 0
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
			await this.$store.dispatch('loadImage', this.id).then((item) => {
				this.img = item;
			});
			this.loading = false;
		}
	},
	computed: {
	}
}
</script>