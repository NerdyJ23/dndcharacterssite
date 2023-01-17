<template>
	<v-progress-linear class="rounded-lg" height="25" color="error" :value="(health.current_health / health.max_health) * 100">
		{{ health.current_health }} / {{ health.max_health }} &nbsp; <span v-if="health.temporary_health > 0">( +{{ health.temporary_health }})</span>
	</v-progress-linear>
</template>
<script>
export default {
	name: "HealthBar",
	props: {
		health: {
			type: Object,
			required: true
		}
	},
}
</script>