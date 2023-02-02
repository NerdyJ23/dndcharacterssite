<template>
	<v-container fluid>
		<v-btn @click="showStatTemplateDialog">Use template</v-btn>
		<character-stats-template-dialog ref="statTemplates" @selected="(item) => prefillStats(item)"/>
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
import CharacterStatsTemplateDialog from './CharacterStatsTemplateDialog.vue';
export default {
	name: "CharacterEditStats",
	components: {
		CharacterStatsTemplateDialog
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
		prefillStats(stats) {
			for(let stat of stats) {
				this.stats.push(this.defaultStat(stat));
			}
		},
		defaultStat(name) {
			return {
				id: "",
				name: name,
				value: 10
			};
		},
		showStatTemplateDialog() {
			this.$refs.statTemplates.show();
		}
	}
}
</script>