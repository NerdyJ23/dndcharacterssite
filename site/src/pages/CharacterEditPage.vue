<template>
	<v-card class="dnd-edit-character">
		<v-card-title>Edit Character</v-card-title>
		<v-card-text>
			<v-row>
				<v-col>
					<v-list class="rounded-0 fill-height d-flex flex-column overflow-x-hidden">
						<v-list-item-group v-model="selectedTab" active-class="tab-active">
							<v-list-item>
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-book-account</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Info</v-list-item-content>
							</v-list-item>
							<v-list-item>
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-arm-flex</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Stats</v-list-item-content>
							</v-list-item>
							<v-list-item>
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-school</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Classes</v-list-item-content>
							</v-list-item>
							<v-list-item>
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-cog</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Settings</v-list-item-content>
							</v-list-item>
						</v-list-item-group>
					</v-list>
				</v-col>
				<v-col cols="10" class="d-flex flex-row">
					<v-divider class="mr-3" vertical />
					<CharacterEditInfo v-show="selectedTab==tabs.info" :char="char" />
					<CharacterEditStats v-show="selectedTab==tabs.stats" :stats="char.stats" />
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>
<script>
import CharacterEditStats from '@/components/Characters/CharacterEditStats.vue';
import CharacterEditInfo from '@/components/Characters/CharacterEditInfo.vue';

export default {
	name: "CharacterEditPage",
	components: {
    CharacterEditStats,
	CharacterEditInfo
},
	props: {
		char: {
			type: Object,
			required: true
		}
	},
	data() {
		return {
			selectedTab: 0,
			tabs: {
				info: 0,
				stats: 1,
				classes: 2,
				inventory: 3
			}
		}
	}
}
</script>
<style lang="scss" scoped>
.dnd-edit-character {
	.tab-active {
		color: var(--v-secondary-base);
		background-color: var(--v-primary-lighten3);
	}
	.v-tab {
		justify-content: start;
	}
}
</style>