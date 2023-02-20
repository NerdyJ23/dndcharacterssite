<template>
	<v-img :src="badge" class="game-badge" style="fill: red"></v-img>
</template>
<script>

import icon from "../../../assets/images/characters/dungeons-and-dragons.png";
export default {
	name: "DndIcon",
	computed: {
		badge() {
			return icon;
		}
	}
}
</script>