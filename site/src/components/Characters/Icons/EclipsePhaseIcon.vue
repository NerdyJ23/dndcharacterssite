<template>
	<v-img :src="badge" class="game-badge"></v-img>
</template>
<script>

import icon from "../../../assets/images/characters/eclipse-phase2-logo.jpg";
export default {
	name: "DndIcon",
	computed: {
		badge() {
			return icon;
		}
	}
}
</script>