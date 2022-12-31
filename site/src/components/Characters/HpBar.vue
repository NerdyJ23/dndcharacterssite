<template>
	<v-progress-linear class="rounded-lg" height="25" color="error" :value="(health.Current_Health / health.Max_Health) * 100">
		{{ health.Current_Health }} / {{ health.Max_Health }} &nbsp; <span v-if="health.Temporary_Health > 0">( +{{ health.Temporary_Health }})</span>
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