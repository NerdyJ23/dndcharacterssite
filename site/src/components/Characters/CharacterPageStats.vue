<template>
	<v-card outlined color="grey lighten-3">
		<v-card-text class="mb-4">
			<template v-if="!loading" v-for="stat in stats">
				<v-row>
					<v-col>
						<CharacterStatBox class="mx-8" :stat="stat.Name" :value="stat.Value" :key="stat.Name"/>
					</v-col>
					<v-col v-for="skill in skills" :key="skill.Name">
						<CharacterSkillItem v-if="skill.stat == stat.Name" :label="stat.Name" :statValue="stat.Value" id="1"/>
					</v-col>
				</v-row>
			</template>
			<template v-else>
				<CharacterStatBoxSkeleton class="mb-6 mt-4 mx-4"/>
				<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
				<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
				<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
				<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
				<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
			</template>
		</v-card-text>
	</v-card>
</template>

<script>
import CharacterStatBox from './CharacterStatBox';
import CharacterStatBoxSkeleton from './Skeletons/CharacterStatBoxSkeleton';
import CharacterSkillItem from './CharacterSkillItem.vue';
export default {
	name: "CharacterPageStats",
	components: {
		CharacterStatBox,
		CharacterStatBoxSkeleton,
		CharacterSkillItem
	},
	props: {
		loading: {
			type: Boolean,
			required: false,
			default: true
		},
		stats: {
			type: Array,
			required: false,
			default: () => {
				return [];
			}
		}
	}
}
</script>