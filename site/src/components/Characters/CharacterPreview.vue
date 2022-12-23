<template>
	<v-card outlined :to="url">
		<v-card-text>
			<v-row>
				<v-col cols="4" class="d-flex align-center my-2">
					<v-img :src="thumbnail"></v-img>
					{{ thumbnail }}
				</v-col>

				<v-col cols="8" class="text-center">
					<v-row>
						<v-col cols="12"><strong>{{ label }}</strong></v-col>
					</v-row>
					<v-row>
						<v-col cols="12">{{ race }}</v-col>
						<v-col cols="12">{{ desc }}</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>
<script>
export default {
	name: "CharacterPreview",
	props: {
		thumbnail: {
			type: String,
			required: false,
			default: "/img/template.jpg"
		},
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
		url: {
			type: String,
			required: true
		}
	},
	data() {
		return {

		}
	}
}
</script>