<template>
	<v-card outlined>
		<v-card-text>
			<v-row>
				<v-col cols="4" class="d-flex justify-center align-center">
					<v-skeleton-loader type="image" loading width="100%"></v-skeleton-loader>
				</v-col>

				<v-col cols="8" class="text-center">
					<v-row>
						<v-col cols="12"><v-skeleton-loader boilerplate type="heading"></v-skeleton-loader></v-col>
						<v-col cols="12"><v-skeleton-loader boilerplate type="text"></v-skeleton-loader></v-col>
						<v-col cols="12"><v-skeleton-loader boilerplate type="text"></v-skeleton-loader></v-col>
						<v-col cols="12">
							<v-skeleton-loader type="actions"></v-skeleton-loader>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card-text>
		<v-skeleton-loader></v-skeleton-loader>
	</v-card>
</template>
<script>
	export default {
		name: "CharacterPreviewSkeleton",
	}
</script>