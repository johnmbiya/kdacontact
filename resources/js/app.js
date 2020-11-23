
require('./bootstrap');

window.Vue = require('vue');

import App from './components/App.vue'

import VueRouter from 'vue-router'
Vue.use(VueRouter)

// form 
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//Import Sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast

import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '15px',
  transition: {
    speed: '0.4s',
    opacity: '0.6s',
    termination: 300
  },
})

import axios from 'axios';
axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest',
  'X-CSRF-TOKEN': window.csrf_token
};

import { routes } from './routes'

const router =  new VueRouter({
    routes,
    mode: 'history'
})


const app = new Vue({
    el: '#app',
    render : h => h(App),
    router
});
