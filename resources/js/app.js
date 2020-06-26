require('./bootstrap');
import Vue from 'vue';

import VueAxios from 'vue-axios';

import Routes from './routes'
import App from './views/App'

Vue.router = Routes;

Vue.use(VueAxios, window.axios);

Vue.axios.defaults.withCredentials = true;

window.Vue = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
});
