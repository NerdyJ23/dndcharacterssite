<template>
	<v-container>
		<CharacterPortrait :id="id" style="max-width: 20%"/>
	</v-container>
</template>
<script>
import CharacterPortrait from './CharacterPortrait.vue';

export default {
	name: "CharacterEditPortrait",
	props: {
		id: {
			type: String,
			required: true
		}
	},
	components: {
		CharacterPortrait
	}
}
</script>