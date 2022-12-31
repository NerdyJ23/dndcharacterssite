<template>
	<v-card style="width:100%">
		<v-card-text>
			<span>
				<template v-for="a in char.classes">
					Lv. {{ a.Level }} {{ a.Class }}
				</template>
			</span>
			<span></span>
			<CharacterPortraitWithName :id="char.id" :name="char.full_name"/>
			&nbsp;
			<span>{{ char.alignment }} {{ char.background }}</span>
			<span>{{ char.race }}</span>
			<HpBar :health="char.health" class="my-2"/>
			<ExpBar :exp="char.exp"/>
		</v-card-text>
	</v-card>
</template>
<script>
import HpBar from './HpBar.vue';
import ExpBar from './ExpBar.vue';
import CharacterPortraitWithName from "./CharacterPortraitWithName.vue";

export default {
	name: "CharacterPageInfo",
	components: {
		HpBar,
		ExpBar,
		CharacterPortraitWithName
	},
	props: {
		char: {
			type: Object,
			required: true
		},
		loading: {
			type: Boolean,
			required: false,
			default: true
		}
	},
	computed: {
		charLevel() {
			let total = 0;
			for (const a of this.char.classes) {
				total += a.Level;
			}
			return total;
		}
	}
}
</script>