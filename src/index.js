import Vue from 'vue'
import App from './App'
import router from './router'
import Vuex from 'vuex'
import { mapState } from 'vuex';
import {bus} from './utils/bus'
import store from './store'
import Axios from 'axios'
import VueCookies from 'vue-cookies'
import $ from 'jquery'
//import { Button, Select, Dialog, MessageBox, Loading, Form, FormItem, Input, Tooltip, Dropdown, DropdownMenu, DropdownItem} from 'element-ui'
import Element from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import enLang from 'element-ui/lib/locale/lang/en'
import './icons' // icon
import { RefreshToken } from '@/utils/auth'
import request from '@/utils/request'
Vue.use(Vuex)
//Vue.component(Button.name, Button);
//Vue.component(Select.name, Select);
/*Vue.use(Dialog);
Vue.use(Form);
Vue.use(FormItem);
Vue.use(Input);
Vue.use(Tooltip);
Vue.use(Dropdown, DropdownMenu, DropdownItem);*/
Vue.use(Element, {
  size: 'medium', // set element-ui default size
  locale: enLang // 如果使用中文，无需设置，请删除
})
//Vue.use(Loading.directive);
//Vue.prototype.$loading = Loading.service;
//Vue.prototype.$msgbox = MessageBox;
//Vue.prototype.$confirm = MessageBox.confirm;
Vue.prototype.$http = Axios;
Vue.prototype.$ = $;
Vue.prototype.$bus = bus;
Vue.use(VueCookies)
Vue.prototype.$cookies = VueCookies;
Vue.prototype.$request = request;
var app = new Vue({
        el: '#app',
        router,
        bus,
        store,
        data: {
			title: "Hello"        
        },
        render: h => h(App)
     }).$mount('#app');
     
