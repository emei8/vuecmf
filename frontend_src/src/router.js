import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
import * as cookie from '@/utils/cookie'

Vue.use(Router)

/**
 * 重写路由的push方法
 */
const routerPush = Router.prototype.push
Router.prototype.push = function push(location) {
    return routerPush.call(this, location).catch(error=> error)
}

function generateRoutesFromMenu (menu = [], routes = []) {
    if(menu == undefined) return routes
    menu = JSON.parse(menu)

    for (let i = 0, l = menu.length; i < l; i++) {
        let item = menu[i]
        if (item.path) {
            routes.push({
                path: item.path,
                component: () => import('@/views/' + item.component) ,
                name: item.name,
                meta: item.meta
            })
        }
    }
    return routes
}

const router = new Router({
    routes: [
        {
            path: '/',
            component: () => import('@/views/layout/index'),
            redirect: 'welcome',
            children: [
                {
                    path: 'welcome',
                    component: () => import('@/views/welcome/index'),
                    name: '系统首页',
                    meta: { title: 'welcome! - Powered by vuecmf.com', icon: 'welcome', noCache: true,topId:0, id:0}
                },
                ...generateRoutesFromMenu(window.sessionStorage.getItem('side_menu_router'))
            ]
        },
        {
            path: '/login',
            component: () => import('@/views/login/index'),
            name: '登录系统',
            meta: { title: '登录系统 - Powered by vuecmf.com', icon: 'welcome', noCache: true,topId:0, id:0}
        },
        {
            path: '*',
            redirect: 'welcome'
        }

    ]
})


router.beforeEach((to, from, next) => {

    document.title = to.meta.title

    console.log(to)

    if(to.path != '/login' && (cookie.get('token') == '' || cookie.get('token') == undefined || cookie.get('token') == null)){
        alert('还没有登录或登录超时,请先登录！')
        next({path:'/login'});
    }else{
        next()
    }

})

router.afterEach((to, from, next) => {
    //store.state.active_side_menu = store.state.navTabsData.currentNavTab = to.path.substr(1)

    console.log(to.meta)

    let topId = to.meta.topId
    let id = to.meta.id

    if(topId == 0){
        store.state.active_menu_nav = 0
        store.state.active_side_menu = 0
        store.state.side_menu_data = {}
    }else{
        let side_menu = store.state.nav_menu[topId]
        side_menu['active_header'] = topId
        side_menu['active'] = id
        store.dispatch('setSideMenuData', side_menu)
    }

    if(to.path != '/login'){
        store.dispatch('setNavTabs',{
            title: to.name,
            name: to.path.substring(1),
            closable: true
        })
    }


})


export default router



