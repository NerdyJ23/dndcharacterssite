<template>
    <v-checkbox
        v-model="proficient"
        :label="label"
    ></v-checkbox>
</template>
<script>
export default {
    name: "CharacterSkillItem",
    props: {
        label: {
            type: String,
            required: true,
        },
        id: {
            type: String,
            required: true
        },
        bonus: {
            type: Number,
            required: false,
            default: 0
        },
        proficient: {
            type: Boolean,
            required: false,
            default: false
        },
        statValue: {
            type: Number,
            required: true
        }
    },
    methods: {
        modifier() {
			return Math.floor((this.value - 10) / 2);
		},
		prefix() {
			if (0 >= this.modifier) {
				return;
			}
			return;
        }
    }
}
</script>