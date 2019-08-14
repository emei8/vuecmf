import Vue from 'vue'
import 'babel-polyfill'
//import './plugins/axios'
import App from './App.vue'
import store from './store'
import router from './router'
import {get,set,remove} from '@/utils/cookie'
import '@/plugins/directives'
import * as api from '@/api'
import * as helper from '@/utils/helper'
import './plugins/iview.js'

Vue.config.productionTip = false

Vue.prototype.$cookie = {
  get, set, remove
}

Vue.prototype.$api = api
Vue.prototype.$helper = helper

new Vue({
  store,
  router,
  render: h => h(App)
}).$mount('#app')
