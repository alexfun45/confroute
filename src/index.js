import '@babel/polyfill'
import 'core-js/modules/es.object.values'
import Vue from 'vue'
import App from './App'
import router from './router'
import Vuex from 'vuex'
//import { mapState } from 'vuex';
import {bus} from './utils/bus'
import { ipUtil } from "./utils/ipUtil";
import store from './store'
import Axios from 'axios'
import VueCookies from 'vue-cookies'
import Element from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import enLang from 'element-ui/lib/locale/lang/en'
import './icons' // icon
//import { RefreshToken } from '@/utils/auth'
import request from '@/utils/request'
Vue.use(Vuex)
Vue.use(Element, {
  size: 'medium', // set element-ui default size
  locale: enLang
})
Vue.prototype.$http = Axios;
Vue.prototype.$bus = bus;
Vue.use(VueCookies)
Vue.prototype.$cookies = VueCookies;
Vue.prototype.$request = request;

const test = new ipUtil();
let checkIP = "192.168.2.255"; // IP to check and generate the range of IP addresses
let subnetMask = "255.255.255.0"; // Subnet Mask, you can also write "128.0.0.0/1"
//let totalIPs2Generate = 1024; // Total number of IPs to generate
let flag = test.isIpInSubnet(checkIP, subnetMask);
console.log("Is IP ", checkIP, "within the subnet mask ", subnetMask, " range? ", flag);

var app = new Vue({
        el: '#app',
        router,
        bus,
        store,
        created(){
			store.dispatch("getConfig");      
        },
        data: {
			title: "Hello"        
        },
        render: h => h(App)
     }).$mount('#app');
     
