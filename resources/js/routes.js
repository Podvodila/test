import Vue from 'vue';
import VueRouter from 'vue-router';
import Articles from "./components/Articles";
import Article from "./components/Article";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/',              component: Articles, name: 'Articles' },
        { path: '/article/:slug', component: Article,  name: 'Article' },
    ],
});

export default router;
