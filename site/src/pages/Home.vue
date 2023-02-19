<template>
	<v-card elevation="0" outlined>
		<v-card-text>
			<v-card outlined>
				<v-card-text>
					<v-carousel cycle continuous show-arrows-on-hover hide-delimiters>
						<v-carousel-item>
							<SiteBlurb />
						</v-carousel-item>
						<v-carousel-item>
							<CharacterRecentCreatedPreview />
						</v-carousel-item>
					</v-carousel>
				</v-card-text>
			</v-card>
		</v-card-text>
	</v-card>
</template>

<script>
import "../styles/style.scss";
import SiteBlurb from "@/components/Home/SiteBlurb.vue";
import CharacterPreview from "../components/Characters/CharacterPreview";
import CharacterRecentCreatedPreview from "../components/Characters/CharacterRecentCreatedPreview";
export default {
	name: "HomePage",
	components: {
		CharacterPreview,
		CharacterRecentCreatedPreview,
		SiteBlurb
	}
}
</script>