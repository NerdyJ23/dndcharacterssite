<template>
	<CharacterStatBox stat="Strength" :value="111" />
</template>
<script>
import CharacterStatBox from './CharacterStatBox';
export default {
	name: "CharacterPageStats",
	components: {
		CharacterStatBox
	}

}
</script>