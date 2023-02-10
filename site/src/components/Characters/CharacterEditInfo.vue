<template>
	<v-container>
		<v-form v-model="valid">
			<v-row>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="char.first_name"
						label="First Name"
						required
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="char.nickname"
						label="Nickname"
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="char.last_name"
						label="Last Name(s)"
					></v-text-field>
				</v-col>
			</v-row>
			<v-row>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="char.race"
						label="Race"
					></v-text-field>
				</v-col>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="char.alignment"
						label="Alignment"
					></v-text-field>
				</v-col>
			</v-row>
			<v-row>
				<v-col>
					<v-text-field
							:disabled="loading"
							v-model="char.background.name"
							label="Background Name"
						></v-text-field>
				</v-col>
				<v-col>
					<v-textarea
							:disabled="loading"
							v-model="char.background.description"
							label="Background Description"
							rows="1"
							counter="200"
							auto-grow
						></v-textarea>
				</v-col>
			</v-row>
			<v-row>
				<template>
					<v-col>
						<v-text-field
							:disabled="loading"
							v-model="char.health.max_health"
							label="Max Health"
							type="number"
						></v-text-field>
					</v-col>
					<v-col>
						<v-text-field
							:disabled="loading"
							v-model="char.health.current_health"
							label="Current Health"
							type="number"
						></v-text-field>
					</v-col>
					<v-col>
						<v-text-field
							:disabled="loading"
							v-model="char.health.temporary_health"
							label="Temporary Health"
							type="number"
						></v-text-field>
					</v-col>
				</template>
				<v-col>
					<v-text-field
						:disabled="loading"
						v-model="char.exp"
						label="Experience Points"
						:messages="`${nextLevel - char.exp} exp away from next level`"
						type="number"
					></v-text-field>
				</v-col>
			</v-row>
		</v-form>
	</v-container>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
	name: "CharacterEditInfo",
	props: {
		char: {
			type: Object,
			required: true
		},
		loading: {
			type: Boolean,
			required: false,
			default: false
		}
	},
	data() {
		return {
			valid: false
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
	},
	computed: {
		...mapGetters({
			getNextLevel: "nextLevel"
		}),
		nextLevel() {
			return this.getNextLevel(this.char.exp);
		}
	}
}
</script>