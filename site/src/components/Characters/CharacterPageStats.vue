<template>
	<v-card outlined color="grey lighten-3">
		<v-card-text class="mb-4">
			<template v-if="!loading">
				<CharacterStatBox v-for="stat in stats" class="mb-6 mx-4" :stat="stat.Name" :value="stat.Value" />
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
export default {
	name: "CharacterPageStats",
	components: {
		CharacterStatBox,
		CharacterStatBoxSkeleton
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