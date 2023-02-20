<template>
	<v-card class="fill-height">
		<v-card-text v-if="char !== undefined && char !== null" class="fill-height">
			<v-row>
				<v-col cols="3">
					<CharacterPortrait :id="char.id" :name="char.full_name"/>
				</v-col>
				<v-col cols="7">
					<v-card outlined style="height:100%">
						<v-card-title class="dnd-title">
							<div class="d-flex flex-row">
								<span>{{ char.full_name }}</span>
							</div>
						</v-card-title>
						<v-card-text class="d-flex flex-column">
							<span>
								<span class="font-weight-bold">Class: </span>
								<template v-for="a in char.classes">
									Lv. {{ a.level }} {{ a.name }}
								</template>
							</span>
							<span>
								<span class="font-weight-bold">Race: </span>
								{{ char.race }}
							</span>
							<span>
								<span class="font-weight-bold">Alignment: </span>{{ char.alignment }}
							</span>
							<span>
								<span class="font-weight-bold">Background: </span> {{ char.background.name }}
							</span>
							<span class="font-italic">"{{ char.background.description }}"</span>
						</v-card-text>
					</v-card>
				</v-col>
				<v-col>
					<v-btn v-if="char.canEdit" @click="$emit('toggleEditing')">Edit</v-btn>
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