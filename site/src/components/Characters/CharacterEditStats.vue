<template>
	<v-container fluid>
		<v-form v-model="valid">
			<v-row v-for="stat in stats" :key="stat.id">
				<v-col>
					<v-text-field
						v-model="stat.name"
						label="Name"
						required
						dense
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						v-model="stat.value"
						label="Label"
						type="number"
						hide-spin-buttons
						dense
						required
					></v-text-field>
				</v-col>
			</v-row>
		</v-form>
	</v-container>
</template>
<script>
export default {
	name: "CharacterEditStats",
	components: {

	},
	props: {
		stats: {
			type: Array,
			required: false,
			default: () => {return []}
		}
	},
	data() {
		return {
			valid: true
		}
	}
}
</script>