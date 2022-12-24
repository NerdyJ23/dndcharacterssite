<template>
	<v-card>
		<v-card-title>Welcome!</v-card-title>
		<v-card-text>
			<CharacterRecentCreatedPreview />
		</v-card-text>
	</v-card>
</template>

<script>
import CharacterPreview from "../components/Characters/CharacterPreview";
import CharacterRecentCreatedPreview from "../components/Characters/CharacterRecentCreatedPreview";
export default {
	name: "HomePage",
	components: {
		CharacterPreview,
		CharacterRecentCreatedPreview
	}
}
</script>