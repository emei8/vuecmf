<template>
  <i-header :style="{height:'42px'}">
    <div class="header-inner">
        <!-- logo start -->
        <div class="header-logo" :class="side_width" >
            <span v-if="is_collapse">
                <i-dropdown class="main-nav" trigger="click" @on-click="handleSelect">
                  <span class="ivu-dropdown-link">
                      <i-icon type="md-menu" />
                  </span>
                  <i-dropdown-menu slot="list">
                      <template v-for="(menu_item, menu_index) in nav_menu">
                        <i-dropdown-item :name="menu_item.path">{{menu_item.title}}</i-dropdown-item>
                      </template>
                  </i-dropdown-menu>
                </i-dropdown>
            </span>
            <span v-else class="logo">YOUNG <i>[ {{ lang }} ]</i></span>
        </div>
        <!-- logo end -->

        <!-- main-menu start -->
        <div v-if="is_collapse" class="menu-nav">YOUNG <i>[ {{ lang }} ]</i></div>
        <i-menu v-else
                :active-name="active_menu_nav"
                class="menu-nav"
                mode="horizontal"
                @on-select="handleSelect"
                theme="light"
                >
            <template v-for="(menu_item, menu_index) in nav_menu">
                <i-menu-item :name="menu_item.id">{{menu_item.title}}</i-menu-item>
            </template>
        </i-menu>
        <!-- main-menu end -->

        <!-- user-info start -->
        <i-dropdown class="user-info"  trigger="click" placement="bottom-end" @on-click="userEvent">
          <span class="ivu-dropdown-link user-face">
            <i-icon type="ios-contact" />
          </span>
          <i-dropdown-menu slot="list">
              <i-dropdown-item>账号：{{ user.username }}</i-dropdown-item>
              <i-dropdown-item>角色：{{ user.role }}</i-dropdown-item>

              <i-dropdown-item divided name="change_backend_en">切换到英语站后台</i-dropdown-item>
              <i-dropdown-item name="change_backend_es">切换到西班牙语站后台</i-dropdown-item>

              <i-dropdown-item divided><a href="/?lang=zh-cn" target="_blank">访问英语站中文版</a></i-dropdown-item>
              <i-dropdown-item><a href="/?lang=zh-es" target="_blank">访问西班牙语站中文版</a></i-dropdown-item>

              <i-dropdown-item divided name="logout"> <i-icon type="md-power" /> 退出系统</i-dropdown-item>
          </i-dropdown-menu>
        </i-dropdown>
        <!-- user-info end -->
    </div>
  </i-header>
</template>

<script>
import { mapState } from 'vuex'


export default {
    name: 'vc-header',
    data() {
        return {
            lang: '英语站',
            nav_menu: {},
            user:{}
        }
    },
    computed: {
        ...mapState({
            is_collapse: state => state.is_collapse,
            side_width: state => state.side_width,
            active_menu_nav: state => state.active_menu_nav
        })
    },
    mounted() {
        let that = this
        that.user = JSON.parse(that.$cookie.get('user'));
        this.$api.request('auth_menu_model','get_nav_menu','get').then(function(res){

            if(res.code == 0){
               that.nav_menu = res.data.nav_menu
               that.$store.dispatch('setNavMenu', that.nav_menu)
            }else if(res.code == 1000){
                that.$router.push({ path:'/login' })
            }else{
                that.$Message.error(res.msg);

            }

        })

        that.$api.axios_request('/welcome/lang','get',{}).then(function (res) {
            if (res.code == 0) {
                that.lang = res.data.lang
            } else {
                that.$Message.error(res.msg);
            }
        })

    },
    methods: {
        userEvent(name){
            let that = this
            if(name == 'logout'){
                that.$api.request('admin_model','logout','post').then(function (data) {
                    if(data.code == 0){
                        that.$cookie.remove('token');
                        that.$cookie.remove('user');
                        that.$cookie.remove('server');
                        that.$Message.success(data.msg)
                        window.sessionStorage.clear()
                        that.$store.dispatch('setNavTabsData', {
                            currentNavTab: 'welcome',
                            navTabs: [{title: '系统首页',name: 'welcome',closable: false}]
                        })

                        that.$router.push({ path:'/login' })
                    }else{
                        that.$Message.error(data.msg)
                    }
                });
            }else if(name == 'change_backend_en'){
                that.$api.axios_request('/welcome/lang','get',{lang:'en-us'}).then(function (res) {
                    if (res.code == 0) {
                        that.lang = res.data.lang
                    } else {
                        that.$Message.error(res.msg);
                    }
                })
            }else if(name == 'change_backend_es'){
                that.$api.axios_request('/welcome/lang','get',{lang:'es-mx'}).then(function (res) {
                    if (res.code == 0) {
                        that.lang = res.data.lang
                    } else {
                        that.$Message.error(res.msg);
                    }
                })
            }
        },
        getMenuRouter(side_menu,old_menu_data,side_menu_router){
            if(side_menu != undefined){
                for(let index in side_menu){
                    let item = side_menu[index]

                    console.log(item)

                    if(item['children'] != undefined){
                        this.getMenuRouter(item['children'],old_menu_data,side_menu_router)
                    }else{
                        if(item.component == '') return

                        let router_item = {
                            path: item.path,
                            component: item.component,
                            name: item.title,
                            meta: {
                                    permission: item.permission,
                                    model: item.model_name,
                                    filter_field: item.filter_field,
                                    title: item.title,
                                    icon: item.icon,
                                    noCache: true,
                                    topId: item.top_id,
                                    id: item.id
                                }
                        }

                        if(old_menu_data != undefined){
                            let flag = true
                            old_menu_data.forEach((old_item, old_index) => {
                                if(old_item.path == item.path || old_item.name == item.title){
                                    flag = false
                                }

                            })

                            if(flag){
                                side_menu_router.push(router_item)
                            }

                        }else{
                            side_menu_router.push(router_item)
                        }


                    }
                }
            }

        },
        handleSelect(key) {
            let that = this
            let side_menu = this.nav_menu[key]
            if(side_menu == undefined) side_menu = [];
            side_menu['active_header'] = key

            that.$store.dispatch('setSideMenuData', side_menu)

            let side_menu_router = []
            let old_menu_data = JSON.parse(window.sessionStorage.getItem('side_menu_router'))

            this.getMenuRouter(side_menu['children'],old_menu_data,side_menu_router)

            side_menu_router = this.$helper.mergeObj(old_menu_data,side_menu_router)


            if(side_menu_router.length > 0){
                window.sessionStorage.setItem('side_menu_router',JSON.stringify(side_menu_router)) //防止刷新时动态添加路由丢失，为此存入本地会话缓存
                let old_router = that.$router.options.routes[0].children
                let new_router = []
                side_menu_router.forEach((router_item, router_index) => {
                    let flag = true
                    old_router.forEach((old_router_item, old_router_index) => {
                        if(router_item.path == old_router_item.path || router_item.name == old_router_item.name){
                            flag = false
                        }
                    })

                    if(flag){
                        new_router.push({
                            path: router_item.path,
                            component: () => import('@/views/' + router_item.component) ,
                            name: router_item.name,
                            meta: router_item.meta
                        })
                    }
                })

                if(new_router.length > 0){
                    that.$router.options.routes[0].children = new_router
                    that.$router.addRoutes(that.$router.options.routes)
                }

            }

        }
    }
}
</script>

<style lang="scss">

@import '~@/assets/style/admin-base.scss';

/* 头部 */
.ivu-layout-header{
    box-shadow: 0 2px 2px #ddd;
    padding:0;
    width: 100%;
}

.header-inner{
    height: $header_height;
    text-align: center;
    display: flex;
    align-items: center;
}

.header-logo{ line-height: $header_height; color: $font_color; }

.ivu-layout-header, .ivu-menu-horizontal.ivu-menu-light, .ivu-menu-light.ivu-menu-horizontal .ivu-menu-item{
  background-color: $background_color_hover;
  color: $font_color;
}

.ivu-menu-light.ivu-menu-horizontal .ivu-menu-item-active{
    color: $font_color_hover
}
.ivu-menu-horizontal.ivu-menu-light:after{ height: 0;}

.user-face{ font-size: 32px;}
.user-face .ivu-icon{  padding-right: 3px;}
.menu-nav{ flex-grow: 1; text-align: center; font-size: 18px; height: $header_height; line-height: $header_height; }
.header-logo{ flex-grow: 0; }
.logo{ font-size: 18px;}



.ivu-dropdown-link{
    color: $font_color;
    cursor: pointer;
}


.header-inner .ivu-select-dropdown{ top:$header_height !important; }


.user-info{
 width: 46px;
 color: $font_color;
 position: relative;
}

.user-info .ivu-select-dropdown{
    position: absolute;
    top: 50px !important;
    left:auto;
    right: 15px;
    width:180px; text-align: left;
}

.ivu-menu-vertical .ivu-menu-item, .ivu-menu-vertical .ivu-menu-submenu-title{
    padding-top: 8px !important;
    padding-bottom: 8px !important;
}

.logo i, .menu-nav i{
    font-style: normal; font-size: 12px; font-weight: 300;
}

</style>
