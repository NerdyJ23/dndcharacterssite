<template>
	<v-dialog
		v-model="display"
		:width="width"
	>
		<v-simple-table>
			<template v-slot:default>
				<thead>
					<tr>
						<th>Template</th>
						<th>Stats</th>
					</tr>
				</thead>
				<tbody>
					<tr
						v-for="item in CharacterStore.statTemplates"
						:key="item.id"
						style="cursor: pointer"
						@click="emitTemplate(item.list)"
					>
						<td>{{ item.name }}</td>
						<td>{{ item.list.join(", ") }}</td>
					</tr>
				</tbody>
			</template>
		</v-simple-table>
	</v-dialog>
</template>
<script>
import { mapState } from 'vuex';
	export default {
		name: "CharacterStatsTemplateDialog",
		props: {
			width: {
				type: String,
				required: false,
				default: "70%"
			}
		},
		data() {
			return {
				display: false
			}
		},
		methods: {
			show() {
				this.display = true;
			},
			hide() {
				this.display = false;
			},
			toggle() {
				this.display = !this.display;
			},
			emitTemplate(list) {
				this.$emit("selected", list);
				this.hide();
			}
		},
		computed: {
			...mapState(["CharacterStore"])
		}
	}
</script>