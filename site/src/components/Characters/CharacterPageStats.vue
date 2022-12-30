<template>
	<v-card outlined color="grey lighten-3">
		<template v-if="!loading">
			<CharacterStatBox class="mb-8 mt-4 mx-4" stat="Strength" :value="12" />
			<CharacterStatBox class="mb-8 mx-4" stat="Dexterity" :value="1222" />
			<CharacterStatBox class="mb-8 mx-4" stat="Strength" :value="13" />
			<CharacterStatBox class="mb-8 mx-4" stat="Strength" :value="111" />
			<CharacterStatBox class="mb-8 mx-4" stat="Strength" :value="11" />
			<CharacterStatBox class="mb-6 mx-4" stat="Strength" :value="1" />
		</template>

		<template v-else>
			<CharacterStatBoxSkeleton class="mb-6 mt-4 mx-4"/>
			<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
			<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
			<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
			<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
			<CharacterStatBoxSkeleton class="mb-6 mx-4"/>
		</template>
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
		}
	}
}
</script>