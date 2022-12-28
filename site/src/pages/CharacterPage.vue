<template>
	<v-container fluid>
		<v-row>
			<v-col cols="3">
				<CharacterPageStats />
			</v-col>
			<v-col cols="9">
				<CharacterPageInfo />
			</v-col>
		</v-row>
	</v-container>
</template>
<script>
import CharacterPageInfo from "../components/Characters/CharacterPageInfo";
import CharacterPageStats from "../components/Characters/CharacterPageStats";

export default {
	name: "CharacterPage",
	components: {
		CharacterPageInfo,
		CharacterPageStats
	}
}
</script>