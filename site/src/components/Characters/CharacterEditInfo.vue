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
							v-model="char.health.max_health"
							label="Max Health"
							type="number"
						></v-text-field>
					</v-col>
					<v-col>
						<v-text-field
							v-model="char.health.current_health"
							label="Current Health"
							type="number"
						></v-text-field>
					</v-col>
					<v-col>
						<v-text-field
						v-model="char.health.temporary_health"
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
				max_health: 0,
				current_health: 0,
				temporary_health: 0,
			}
		}
	}
}
</script>