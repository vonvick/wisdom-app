import Vue from 'vue';
import VueRouter from 'vue-router';

import meta from './meta.json';

import LoginComponent from '../views/Login';
import UserPage from '../views/App/index';
import HomePage from '../views/App/HomePage';

Vue.use(VueRouter);

const index = [
  {
    path: '/',
    redirect: '/app',
    meta: meta['/']
  },
  {
    path: '/app',
    component: UserPage,
    meta: meta['/app'],
    children: [
      {
        path: '',
        component: HomePage,
        name: 'HomePage'
      },
    ],
  },
  {
    path: '/login',
    component: LoginComponent,
    name: 'Login',
    meta: meta['/login']
  },
];

export default new VueRouter({
  routes: index,
  mode: 'history',
});
