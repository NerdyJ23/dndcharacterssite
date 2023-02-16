<template>
	<v-card outlined :to="`/characters/${id}`" height="100%">
		<v-card-text>
			<v-row>
				<v-col cols="4">
					<CharacterPortrait :id="id" />
				</v-col>

				<v-col cols="8" class="text-center">
					<v-row>
						<v-col cols="12" class="dnd-title dnd-title-bold">{{ label }}</v-col>
						<v-col cols="12" >{{ race }}</v-col>
						<v-col cols="12">{{ desc }}</v-col>
						<v-col cols="12">
							<ExpBar :exp="exp"/>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>
<script>
import ExpBar from "./ExpBar";
import CharacterPortrait from "./CharacterPortrait.vue";

export default {
	name: "CharacterPreview",
	components: {
		ExpBar,
		CharacterPortrait
	},
	props: {
		label: {
			type: String,
			required: true
		},
		race: {
			type: String,
			required: true
		},
		desc: {
			type: String,
			required: false,
			default: "A character"
		},
		id: {
			type: String,
			required: true
		},
		exp: {
			type: Number,
			required: false,
			default: 0
		}
	}
}
</script>