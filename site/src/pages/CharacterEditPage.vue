<template>
	<v-card class="dnd-edit-character">
		<overlay-loader :loading="loading"></overlay-loader>
		<v-card-title>{{ state }} Character</v-card-title>
		<v-card-text>
			<v-row>
				<v-col>
					<v-list class="rounded-0 fill-height d-flex flex-column overflow-x-hidden">
						<v-list-item-group active-class="tab-active">
							<v-list-item @click="selectedTab = tabs.info">
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-book-account</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Info</v-list-item-content>
							</v-list-item>
							<v-list-item @click="selectedTab = tabs.portrait">
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-image-frame</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Portrait</v-list-item-content>
							</v-list-item>
							<v-list-item @click="selectedTab = tabs.stats">
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-arm-flex</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Stats</v-list-item-content>
							</v-list-item>
							<v-list-item @click="selectedTab = tabs.classes">
								<v-list-item-icon>
									<v-icon class="pr-2">mdi-school</v-icon>
								</v-list-item-icon>
								<v-list-item-content>Classes</v-list-item-content>
							</v-list-item>
							<v-list-item @click="selectedTab = tabs.settings">
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
					<CharacterEditInfo v-show="selectedTab==tabs.info" :char="char" :loading="loading"/>
					<CharacterEditStats v-show="selectedTab==tabs.stats" :stats="char.stats" :loading="loading" @delete="item => toDelete.stats.push(item)"/>
					<CharacterEditClasses v-show="selectedTab==tabs.classes" :classes="char.classes" :loading="loading" @delete="item => toDelete.classes.push(item)"/>
					<CharacterEditSettings v-show="selectedTab==tabs.settings" :char="char" :loading="loading" />
					<CharacterEditPortrait v-show="selectedTab==tabs.portrait" :id="char.id" ref="characterPortrait"/>
				</v-col>
			</v-row>
			<v-row class="sticky-bar">
				<v-col cols="1"></v-col>
				<v-col class="d-flex justify-center">
					<v-card elevation="0" :color="dirty ? 'yellow' : ''">
						<v-card-text v-if="dirty">
							Warning: You have unsaved changes
						</v-card-text>
						<v-card-actions>
							<v-btn class="mx-auto" @click="save" color="primary">Save</v-btn>
						</v-card-actions>
					</v-card>
				</v-col>
			</v-row>
		</v-card-text>
		<error-message-bar :show="error.show" :message="error.message" />
	</v-card>
</template>
<script>
import characterApi from '@/services/characterApi';

import CharacterEditStats from '@/components/Characters/CharacterEditStats.vue';
import CharacterEditInfo from '@/components/Characters/CharacterEditInfo.vue';
import CharacterEditSettings from '@/components/Characters/CharacterEditSettings.vue';
import CharacterEditClasses from '@/components/Characters/CharacterEditClasses.vue';
import CharacterEditPortrait from '@/components/Characters/CharacterEditPortrait.vue';

import OverlayLoader from '@/components/Utility/OverlayLoader.vue';
import ErrorMessageBar from '@/components/Utility/ErrorMessageBar.vue';
import { mapState } from 'vuex';

export default {
	name: "CharacterEditPage",
	components: {
    CharacterEditStats,
	CharacterEditInfo,
	CharacterEditSettings,
	CharacterEditClasses,
	CharacterEditPortrait,
	OverlayLoader,
	ErrorMessageBar
},
	props: {
		char: {
			type: Object,
			required: false,
			default: () => {
				return {
					id: "",
					first_name: "",
					nickname: "",
					last_name: "",

					race: "",
					alignment: "",
					exp: 0,
					background: {
						name: "",
						description: ""
					},

					health: {
						max_health: 0,
						current_health: 0,
						temporary_health: 0
					},
					stats: [],
					classes: []
				}
			}
		},
		state: {
			type: String,
			required: false,
			default: "Edit"
		}
	},
	data() {
		return {
			selectedTab: 0,
			tabs: {
				info: 0,
				stats: 1,
				classes: 2,
				inventory: 3,
				settings: 3,
				portrait: 4
			},
			dirty: false,
			loading: false,
			error: {
				show: false,
				message: ""
			},
			toDelete: {
				stats: [],
				skills: [],
				classes: [],
			}
		}
	},
	computed: {
		...mapState(["GenericStore"])
	},
	inject: ["needSession"],

	mounted() {
		this.needSession();
	},
	methods: {
		async save() {
			if (this.char.stats.length == 0 || this.char.classes.length == 0) {
				return;
			}

			this.loading = true;
			let response = null;

			if (this.state == 'Edit') {
				this.char.toDelete = this.toDelete;
				response = await characterApi.editCharacter(this.char);
			} else {
				response = await characterApi.createCharacter(this.char);
			}

			//Has to be after in case of creating character
			await this.$refs.characterPortrait.uploadImage();
			if (response.status === 201) {
				this.$router.push(`/characters/${response.data.id}`);
			} else if (response.status === 204) {
				this.$emit("saved");
				this.loading = false;
			} else {
				this.error.show = true;
				this.error.message = response.data.errorMessage;
				this.loading = false;
				console.error("fucak");
			}
		},
	},
	watch: {
		char: {
			handler() {
				this.dirty = true;
			},
			deep: true
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