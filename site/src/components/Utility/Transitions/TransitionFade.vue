<template>
	<TransitionGroup
		name="fade"
		@enter="$emit('enter')"
		@leave="$emit('leave')"
	>
	<slot></slot>
	</TransitionGroup>
</template>
<script>
	export default {
		name: "TransitionFade"
	}
</script>
<style>
.fade-enter-active,
.fade-leave-active {
	transition: all 2s ease;
}

.fade-enter-from,
.fade-leave-to {
	opacity: 0;
}
</style>