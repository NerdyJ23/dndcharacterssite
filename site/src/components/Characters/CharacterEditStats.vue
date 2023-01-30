<template>
	<v-container fluid>
		<v-btn @click="prefillStats">Use template</v-btn>
		<v-form v-model="valid">
			<v-row v-for="(stat, index) in stats">
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="stat.name"
						label="Name"
						required
						dense
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="stat.value"
						label="Label"
						type="number"
						hide-spin-buttons
						dense
						required
					></v-text-field>
				</v-col>
				<v-col cols="1">
					<v-btn icon>
						<v-icon color="red" :disabled="loading" @click="remove(index)">mdi-delete</v-icon>
					</v-btn>
				</v-col>
			</v-row>
			<v-row>
				<v-col class="d-flex justify-center">
					<v-btn fab color="light-green lighten" :disabled="loading" @click="add">
						<v-icon>mdi-plus</v-icon>
					</v-btn>
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
		},
		loading: {
			type: Boolean,
			required: false,
			default: false
		}
	},
	data() {
		return {
			emptyStat: {
				id: "",
				name: "",
				value: 0
			},
			valid: true,
			deleted: [],
		}
	},
	methods: {
		remove(index) {
			if (this.stats[index].id !== "") {
				this.deleted.push(this.stats[index]);
			}
			this.stats.splice(index,1);
		},
		add() {
			let newStat = {};
			Object.assign(newStat, this.emptyStat);
			this.stats.push(newStat);
		},
		prefillStats(templateId) {
			this.stats.push(this.defaultStat("Strength"));
			this.stats.push(this.defaultStat("Dexterity"));
			this.stats.push(this.defaultStat("Constitution"));
			this.stats.push(this.defaultStat("Intelligence"));
			this.stats.push(this.defaultStat("Wisdom"));
			this.stats.push(this.defaultStat("Charisma"));
		},
		defaultStat(name) {
			return {
				id: "",
				name: name,
				value: 10
			};
		}
	}
}
</script>