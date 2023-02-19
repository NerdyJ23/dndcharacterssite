<template>
	<v-card>
		<v-img class="home-page-image" :style="`display: ${slide.current == key.dnd ? 'flex' : 'none'}` " :src="dnd" :key="key.dnd" />
		<v-img class="home-page-image" :style="`display: ${slide.current == key.eclipsePhase ? 'flex' : 'none'}` " :src="eclipsePhase" :key="key.eclipsePhase" />
		<v-img class="home-page-image" :style="`display: ${slide.current == key.callOfCthulu ? 'flex' : 'none'}` " :src="callOfCthulu" :key="key.callOfCthulu"/>
		<v-card-text>
			Create Characters from any series or genre you want!
			Import Stat value templates from your favourite series.
		</v-card-text>
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
            setTimeout(() => this.changeSlide(), 800);
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
	transition: 0.8s all ease;
}
</style>