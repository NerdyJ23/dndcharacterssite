<template>
	<v-img :src="badge" class="game-badge"></v-img>
</template>
<script>

import icon from "../../../assets/images/characters/call-of-cthulu.png";
export default {
	name: "CallOfCthuluIcon",
	computed: {
		badge() {
			return icon;
		}
	}
}
</script>