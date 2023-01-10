<template>
	<v-card style="width:100%" class="fill-height">
		<v-card-text class="fill-height">
			<v-row>
				<v-col cols="3">
					<CharacterPortrait :id="char.id" :name="char.full_name"/>
				</v-col>
				<v-col cols="7">
					<v-card outlined style="height:100%">
						<v-card-title class="dnd-title">
							{{ char.full_name }}
						</v-card-title>
						<v-card-text  class="d-flex flex-column">
							<span>
								<span class="font-weight-bold">Class: </span>
								<template v-for="a in char.classes">
									Lv. {{ a.Level }} {{ a.Class }}
								</template>
							</span>
							<span>
								<span class="font-weight-bold">Alignment: </span>{{ char.alignment }}
							</span>
							<span>
								<span class="font-weight-bold">Background: </span> {{ char.background }}
							</span>
							<span>
								<span class="font-weight-bold">Race: </span>
								{{ char.race }}
							</span>
						</v-card-text>
					</v-card>
				</v-col>
			</v-row>
			<br />
			<div>
				<span class="font-weight-bold">Hp:</span>
				<HpBar :health="char.health" class="my-2"/>
			</div>
			<div>
				<span class="font-weight-bold">Exp:</span>
				<ExpBar :exp="char.exp"/>
			</div>
		</v-card-text>
	</v-card>
</template>
<script>
import HpBar from './HpBar.vue';
import ExpBar from './ExpBar.vue';
import CharacterPortrait from "./CharacterPortrait.vue";

export default {
	name: "CharacterPageInfo",
	components: {
		HpBar,
		ExpBar,
		CharacterPortrait
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