<template>
	<v-card style="height: 100%">
		<v-img height="90vh" contain class="home-page-image" :style="`opacity: ${slide.current == key.dnd ? 1 : 0}; position: absolute !important;`" :src="dnd" :key="key.dnd" />
		<v-img height="90vh" contain class="home-page-image" :style="`opacity: ${slide.current == key.eclipsePhase ? 1 : 0}; position: absolute !important;`" :src="eclipsePhase" :key="key.eclipsePhase" />
		<v-img height="90vh" contain class="home-page-image" :style="`opacity: ${slide.current == key.callOfCthulu ? 1 : 0}; position: absolute !important;`" :src="callOfCthulu" :key="key.callOfCthulu"/>
	</v-card>
</template>
<script>
import CallOfCthulu from "../../assets/images/home/call-of-cthulu.jpg";
import DnD from "../../assets/images/home/dnd-splash.jpg";
import EclipsePhase from "../../assets/images/home/eclipse-phase.jpg";
import TransitionFade from "../Utility/Transitions/TransitionFade.vue";

export default {
	name: "SiteBlurb",
	components: {
		TransitionFade
	},
    data() {
        return {
            slide: {
                current: 0,
                max: 2
            },
            key: {
                dnd: 0,
                callOfCthulu: 1,
                eclipsePhase: 2
            }
        };
    },
    mounted() {
        this.queueNextSlide();
    },
    computed: {
        dnd() {
            return DnD;
        },
        callOfCthulu() {
            return CallOfCthulu;
        },
        eclipsePhase() {
            return EclipsePhase;
        }
    },
    methods: {
        queueNextSlide() {
            setTimeout(() => this.changeSlide(), 3500);
        },
        changeSlide() {
            if (this.slide.current == this.slide.max) {
                this.slide.current = 0;
            }
            else {
                this.slide.current++;
            }
			this.queueNextSlide();
        }
    },
}
</script>
<style>
.home-page-image {
	transition: 2s all ease;
}
</style>