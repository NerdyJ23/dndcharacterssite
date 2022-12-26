<template>
	<v-card elevation="0" outlined>
		<v-card-title></v-card-title>
		<v-card-text>
			<CharacterRecentCreatedPreview />
		</v-card-text>
	</v-card>
</template>

<script>
import "../styles/style.scss";
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