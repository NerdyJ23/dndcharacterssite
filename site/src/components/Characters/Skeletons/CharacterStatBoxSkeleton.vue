<template>
	<v-card>
		<v-card-text class="pb-0 pt-2">
			<v-skeleton-loader class="ma-auto" type="text" loading />
			<v-divider class="mb-2"></v-divider>
			<v-skeleton-loader type="text" loading/>
			<v-skeleton-loader class="ma-auto" type="text" loading style="width: 20%" />
		</v-card-text>
	</v-card>
</template>
<script>
	export default {
		data() {
			return {
				types: {
					statbox: "text@2, heading"
				}
			}
		}
	}
</script>