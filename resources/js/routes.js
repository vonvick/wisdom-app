import Vue from 'vue';
import VueRouter from 'vue-router';

import LoginComponent from './components/Pages/Login';

Vue.use(VueRouter);

const routes = [
    {
        path: '/login',
        component: LoginComponent,
        name: 'Login'
    }
];

export default new VueRouter({
   routes,
   mode: 'history',
});
