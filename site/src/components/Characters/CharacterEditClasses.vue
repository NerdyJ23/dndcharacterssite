<template>
	<v-container fluid>
		<v-form v-model="valid">
			<v-row v-for="(item, index) in classes">
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="item.name"
						label="Class"
						required
						dense
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="item.level"
						label="Level"
						type="number"
						hide-spin-buttons
						required
						dense
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
	name: "CharacterEditClasses",
	props: {
		classes: {
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
			emptyClass: {
				id: "",
				name: "",
				value: 0
			},
		}
	},
	methods: {
		remove(index) {
			if (this.classes[index].id !== "") {
				this.$emit("delete", this.classes[index]);
			}
			this.classes.splice(index,1);
		},
		add() {
			let newClass = {};
			Object.assign(newClass, this.emptyClass);
			this.classes.push(newClass);
		},
	}
}
</script>