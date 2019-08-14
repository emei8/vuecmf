import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    model_api_map:{}, //菜单后端链接映射
    side_collapse: false, //侧边栏展开与折叠
    is_collapse: false, //头部logo及导航菜单的展开与折叠
    side_width: 'side_large_width', //头部logo宽度
    side_menu_width: 'side_menu_large_width', //侧边菜单栏宽度
    active_menu_nav: 0, //头部导航菜单默认选中
    active_side_menu: 0, //侧边菜单默认选中
    side_menu_data: {},  //侧边菜单
    nav_menu: {}, //菜单列表
    //主体部分标签页
    navTabsData:{
      currentNavTab: 'welcome',
      navTabs:[{
        title: '系统首页',
        name: 'welcome',
        closable: false
      }]
    }
  },
  mutations: {
    setModelApiMap(state,model_api_map){
      state.model_api_map = model_api_map
    },
    toggleSideCollapse(state,side_collapse) {
      state.side_collapse = side_collapse
      if(!side_collapse){
          state.side_menu_width = 'side_large_width'
      }else {
          state.side_menu_width = 'side_small_width'
      }
    },
    toggleIsCollapse(state,is_collapse) {
      state.is_collapse = is_collapse
      if(!is_collapse){
          state.side_width = 'side_large_width'
      }else {
          state.side_width = 'side_small_width'
      }
    },
    setSideMenuData(state, menu) {
      state.side_menu_data = menu['children']
      state.active_side_menu = menu['active']
      state.active_menu_nav = menu['active_header']
    },
    setNavTabs(state, newTab){
      state.navTabsData.currentNavTab = newTab.name

      let flag = true
      state.navTabsData.navTabs.forEach((tab, index) => {
        if(tab.name == newTab.name)  flag = false
      });
      if(flag){
        state.navTabsData.navTabs.push(newTab)
      }

    },
    setNavTabsData(state, tabsData){
        state.navTabsData = tabsData
    },
    setNavMenu(state, navMenu){
        state.nav_menu = navMenu
    }
  },
  actions: {
    setModelApiMap({ commit },model_api_map) {
      commit('setModelApiMap',model_api_map)
    },
    toggleCollapse({ commit },collapse) {
        commit('toggleIsCollapse',collapse)
        commit('toggleSideCollapse',collapse)
    },
    toggleSideCollapse({ commit },collapse) {
        commit('toggleSideCollapse',collapse)
    },
    setSideMenuData({ commit },menu) {
      commit('setSideMenuData',menu)
    },
    setNavTabs({ commit },newTab) {
      commit('setNavTabs',newTab)
    },
    setNavTabsData({ commit },tabsData) {
      commit('setNavTabsData',tabsData)
    },
    setNavMenu({ commit },navMenu) {
        commit('setNavMenu',navMenu)
    }
  },
  getters: {
    notSideCollapse: state => {
      return !state.side_collapse
    }
  }
})
