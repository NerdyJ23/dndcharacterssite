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
						<v-col cols="12">
							<template v-for="a in classes">
									Lv. {{ a.level }} {{ a.name }}
							</template>
						</v-col>
						<v-col cols="12">{{ background == "" ? "&nbsp;" : background }}</v-col>
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
		background: {
			type: String,
			required: false,
			default: ""
		},
		id: {
			type: String,
			required: true
		},
		exp: {
			type: Number,
			required: false,
			default: 0
		},
		classes: {
			type: Array,
			required: true
		}
	}
}
</script>