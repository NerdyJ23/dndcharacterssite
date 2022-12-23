import Vue from 'vue'
import VueRouter from 'vue-router';

import App from './App.vue'

import Home from './pages/Home';
import NotFoundPage from './pages/ErrorPages/NotFoundPage';
import vuetify from './plugins/vuetify'
import store from './store'
// import '@babel/polyfill'

Vue.config.productionTip = false;
Vue.use(VueRouter);

const routes = [
	{path: '/', component: Home},

	{path: '*', component: NotFoundPage},
];

const router = new VueRouter({
	mode: 'history',
	routes,
})

new Vue({
    render: h => h(App),
    vuetify,
    store,
    router
}).$mount('#app')
