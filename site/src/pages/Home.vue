<template>
	<v-card>
		<v-card-title>Welcome!</v-card-title>
		<v-card-text>
			<v-row>
				<v-col  xl="3" lg="4" sm="6" xs="12">
					<CharacterPreview label="Killjoy McBicepts" url="/char/texts" desc="That one funny vid from league of legends"/>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>

<script>
import CharacterPreview from "../components/Characters/CharacterPreview";

export default {
	name: "HomePage",
	components: {
		CharacterPreview
	}
}
</script>