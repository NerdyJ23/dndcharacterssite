<template>
	<v-container>
		<v-form v-model="valid">
			<v-row>
				<v-col>
					<v-text-field
						v-model="char.first_name"
						label="First Name"
						required
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						v-model="char.last_name"
						label="Last Name(s)"
					></v-text-field>
				</v-col>
			</v-row>
			<v-row>
				<v-col>
					<v-text-field
						v-model="char.race"
						label="Race"
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						v-model="char.alignment"
						label="Alignment"
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						v-model="char.background"
						label="Background"
					></v-text-field>
				</v-col>
			</v-row>
			<v-row>
				<template>
					<v-col>
						<v-text-field
							v-model="char.health.Max_Health"
							label="Max Health"
							type="number"
						></v-text-field>
					</v-col>
					<v-col>
						<v-text-field
							v-model="char.health.Current_Health"
							label="Current Health"
							type="number"
						></v-text-field>
					</v-col>
					<v-col>
						<v-text-field
						v-model="char.health.Temporary_Health"
						label="Temporary Health"
						type="number"
						></v-text-field>
					</v-col>
				</template>
				<v-col>
					<v-text-field
						v-model="char.exp"
						label="Experience Points"
						type="number"
					></v-text-field>
				</v-col>
			</v-row>
		</v-form>
	</v-container>
</template>
<script>
export default {
	name: "CharacterEditInfo",
	props: {
		char: {
			type: Object,
			required: false
		}
	},
	mounted() {
		if (this.char.health == null) {
			this.char.health = {
				Max_Health: 0,
				Current_Health: 0,
				Temporary_Health: 0,
			}
		}
	}
}
</script>