<template>
	<v-list
		class="rounded-0 fill-height d-flex flex-column overflow-x-hidden"
	>
		<v-list-item-group
			v-model="selectedItem"
		>
			<v-list-item to="/">
				<v-list-item-icon>
					<v-icon>mdi-home</v-icon>
				</v-list-item-icon>
				<v-list-item-content v-if="!$vuetify.breakpoint.sm">
					Home
				</v-list-item-content>
			</v-list-item>
			<template v-if="GenericStore.validSession">
				<v-list-item to="/receipt">
					<v-list-item-icon>
						<v-icon>mdi-receipt-text</v-icon>
					</v-list-item-icon>
					<v-list-item-content v-if="!$vuetify.breakpoint.sm">
						Receipts
					</v-list-item-content>
				</v-list-item>
				<v-list-item to="/graph">
					<v-list-item-icon>
						<v-icon>mdi-chart-line-variant</v-icon>
					</v-list-item-icon>
					<v-list-item-content v-if="!$vuetify.breakpoint.sm">
						Graphs
					</v-list-item-content>
				</v-list-item>
			</template>
		</v-list-item-group>
		<v-list-item-group class="mt-auto">
			<v-divider />
			<v-list-item
				v-if="GenericStore.validSession"
				:inactive="true"
				@click="$emit('logout')"
				class="noSelectHighlight"
			>
				<v-list-item-icon>
					<v-icon>mdi-exit-to-app</v-icon>
				</v-list-item-icon>
				<v-list-item-content v-if="!$vuetify.breakpoint.xs">
					Logout
				</v-list-item-content>
			</v-list-item>
			<v-list-item
				v-else
				:inactive="true"
				@click="$emit('login')"
				class="noSelectHighlight"
			>
				<v-list-item-icon>
					<v-icon>mdi-account-circle</v-icon>
				</v-list-item-icon>
				<v-list-item-content v-if="!$vuetify.breakpoint.xs">
					Login
				</v-list-item-content>
			</v-list-item>
		</v-list-item-group>
	</v-list>
</template>
<script>
import { mapState } from "vuex";


export default {
	name: "NavList",
	data() {
		return {
			active: false,
			selectedItem: 0,
		}
	},
	computed: {
		...mapState(["GenericStore"])
	}
}
</script>
<style scoped>
	.expandedDrawer {
		width: 15vw;
	}
	.noSelectHighlight {
		cursor: pointer;
	}
</style>