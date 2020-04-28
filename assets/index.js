import Vue from 'vue';

import App from './components/App';
import Login from './components/Auth/Login';
import PostList from './components/Posts/PostList';
import PostItem from './components/Posts/PostItem';
import FillInBalance from './components/Wallet/FillInBalance';
import PurchaseLikes from './components/Wallet/PurchaseLikes';

import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'main_page',
            component: PostList,
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/post/:id',
            name: 'post.show',
            component: PostItem,
        },
        {
            path: '/fill',
            name: 'fill.balance',
            component: FillInBalance,
        },
        {
            path: '/purchase',
            name: 'purchase.likes',
            component: PurchaseLikes,
        },
    ],
    mode: 'history'
});

Vue.router = router;
App.router = Vue.router;

new Vue(App).$mount('#app');