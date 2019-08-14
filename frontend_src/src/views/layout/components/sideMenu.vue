<template>
<!-- 左边导航菜单开始 -->
    <div>
    
        <i-menu :active-name="active_side_menu" ref="side_menu" theme="dark" width="auto" :class="menuitemClasses" @on-select="select">
            <i-menu-item :name="0"  to="welcome" v-if="active_menu_nav == 0">
                <i-icon type="ios-home" /><span>系统首页</span>
            </i-menu-item>

            <vc-side-menu-item :side-collapse="sideCollapse" :menu-list="side_menu_data" ></vc-side-menu-item>
   

        </i-menu>
 

    </div>
    <!-- 左边导航菜单结束 -->
</template>

<script>
import { mapState } from 'vuex'
import sideMenuItem from './sideMenuItem.vue'

export default {
    name: 'vc-side-menu',
    props:['sideCollapse'],
    components:{
        'vc-side-menu-item': sideMenuItem
    },
    data() {
        return {
            menuList:[
                { id: 1, title: 'aaa', icon: 'ios-home-outline', path:'get_menu_tree'},
                { id: 2, title: 'bbb', icon: 'ios-home-outline',children:[
                    { id: 21, title: 'aaa2222', icon: 'ios-home-outline', path:'article'},
                    { id: 22, title: 'aaa3333', icon: 'ios-home-outline',children:[
                        { id: 31, title: '333333333', icon: 'ios-home-outline', path:'get_menu_tree'},
                        { id: 32, title: '4444444444', icon: 'ios-home-outline', path:'article'},
                    ]},
                    { id: 23, title: 'aaa4444', icon: 'ios-home-outline', path:'get_menu_tree'},
                ]},
                { id: 3, title: 'ccc', icon: 'ios-home-outline', path:'article'},
            ]
        }
    },
    computed: {
        ...mapState({
            side_menu_width: state => state.side_menu_width,
            active_side_menu: state => state.active_side_menu,
            side_menu_data: state => state.side_menu_data ,
            active_menu_nav: state => state.active_menu_nav
        }),

        menuitemClasses () {
            return [
                'menu-item',
                this.sideCollapse ? 'collapsed-menu' : ''
            ]
        }

    },
    mounted(){
        console.log(this.side_menu_data)

    },
    methods: {
        select(name){
            //console.log(name)
            //let title = '首页'

            /*if(name != 'welcome' && this.$refs.sideMenu.$refs[name] != undefined){
                title = this.$refs.sideMenu.$refs[name][0].$el.innerText
            }*/

            console.log(name)
            console.log(this.active_side_menu)

            this.$nextTick(() => {
                this.$refs.side_menu.updateOpened()
                this.$refs.side_menu.updateActiveName()
            })


            /*this.$store.dispatch('setNavTabs',{
                title: title,
                name: this.$route.path.substring(1),
                closable: true
            })*/
        }
    }
}
</script>

<style   lang="scss">
@import '~@/assets/style/admin-base.scss';

/* 左边导航 */
.ivu-menu{ z-index: 1}
.ivu-menu-vertical, .ivu-menu-item{
    background-color: $background_color;
    color:$font_color;
}

.ivu-menu-light.ivu-menu-vertical .ivu-menu-item-active:not(.ivu-menu-submenu){
    background-color: $background_color_hover;
    color:$font_color_hover;
}

.ivu-menu-vertical .ivu-menu-item, .ivu-menu-vertical .ivu-menu-submenu-title{
    padding: 12px 11px;
}

    .menu-item span{
        display: inline-block;
        overflow: hidden;
        width: 120px;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: bottom;
        transition: width .2s ease .2s;
    }
    .menu-item i{
        transform: translateX(0px);
        transition: font-size .2s ease, transform .2s ease;
        vertical-align: middle;
        font-size: 14px;
    }
    .collapsed-menu span{
        width: 0px;
        transition: width .2s ease;
    }
    .collapsed-menu i{
        transform: translateX(5px);
        transition: font-size .2s ease .2s, transform .2s ease .2s;
        vertical-align: middle;
        font-size: 22px;
    }


/*.sider_large_width .ivu-menu-item{
    min-width: 160px;
    padding-right: 10px !important;
}
*/




/*



.el-menu{ border: 0;  }

.el-menu-item .fa{
    margin-right: 5px;
    width: 24px;
    text-align: center;
    font-size: 18px;
    vertical-align: middle;
}

.i-sider, .el-menu-vertical, .el-menu, .el-submenu .el-menu-item, .el-menu .el-submenu,  .i-sider .el-menu-item{
  background-color: $background_color;
}
.el-menu-item:hover, .el-submenu__title:hover, .el-menu-item.is-active,  .el-menu {
  background-color: $background_color_hover;
}
.sider-popper .el-menu-item, .el-submenu__title, .i-sider .el-menu-item, .el-menu-vertical .el-submenu .el-menu-item{
  height: $header_height;
  line-height: $header_height;
}
.el-submenu__title, .el-menu .el-menu-item, .el-tooltip{
    padding-left: 9px !important;
}
.el-menu--inline .el-menu-item, .el-submenu ul li .el-submenu__title {
    padding-left: 36px !important;
    
}

.el-submenu ul li.el-submenu  ul {
    .el-menu-item, .el-submenu__title{
        padding-left: 48px !important;
    }
    li.el-submenu ul{
        .el-menu-item, .el-submenu__title{
            padding-left: 60px !important;
        }
        li.el-submenu ul{
            .el-menu-item, .el-submenu__title{
                padding-left: 72px !important;
            }
        }
    }
}

*/


</style>
