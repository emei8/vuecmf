<template>
    <i-layout>

        <vc-header></vc-header>

        <i-layout>
            <i-sider hide-trigger  collapsible  :collapsed-width="50" v-model="side_collapse" >
                <div class="collapse-menu" @click.prevent="collapse" >
                    <i-icon type="ios-log-in" v-show="!side_collapse" title="折叠" />
                    <i-icon type="ios-log-out" v-show="side_collapse" title="展开" />
                </div>
                <vc-side-menu :side-collapse="side_collapse" ref="vc_side_menu"></vc-side-menu>
            </i-sider>

            <i-layout class="main-content">
                <div class="main-nav-tabs">
                    <vc-nav-tabs @on-rest-side-menu-select="restSideMenuSelect" ></vc-nav-tabs>
                </div>


                <!-- 主体开始 -->
                <i-content class="main-content-wrapper">
                    <router-view :key="key" />
                </i-content>
                <!-- 主体结束 -->

                <i-footer height="36px">&copy; VueCMF. Powered by <a target="_blank" href="http://www.vuecmf.com/">vuecmf.com</a></i-footer>

            </i-layout>
        </i-layout>
    </i-layout>
</template>

<style lang="scss">
@import '~@/assets/style/admin-base.scss';

/* 主体 */
.ivu-layout-content{
    color: #666;
    font-size: 16px;
    background-color: #FFF;
    padding: 10px;

}
.main-content{ overflow: hidden;}
.main-nav-tabs{ height: 36px;}
.main-content-wrapper{ overflow: auto;}
.ivu-layout-sider{
    background-color: $background_color;
    color:$font_color;
}

.collapse-menu{ 
    width: 100%;
    line-height: 22px;
    height: 24px;
    text-align: center;
    color: #bbb;
    cursor: pointer;
    background-color: #2e3336;
    font-size: 16px;
}
.collapse-menu i{ margin-left: 4px;}

.side_small_width{
  width: $header_height;
}

.side_large_width{
  width:200px;
}

/* 底部 */
.ivu-layout-footer {
    color: #666;
    text-align: center;
    line-height: 30px;
    box-shadow: 0 -2px 2px #ddd;
    z-index: 0;
    font-size: 14px;
    padding: 4px 0;
}

</style>


<script>
  import vcHeader from "./components/header"
  import vcSideMenu from "./components/sideMenu"
  import vcNavTabs from "./components/navTabs"
  import * as utils from "@/utils/screen.js"
  import { mapState, mapGetters } from 'vuex'


export default {
    name: 'layout',
    components: {
        'vc-header': vcHeader,
        'vc-side-menu': vcSideMenu,
        'vc-nav-tabs': vcNavTabs
    },
    data(){
        return {
            
        }
    },
    beforeMount(){
        window.addEventListener('resize',this.resize)
    },
    mounted(){
        this.resize()
        let token = this.$cookie.get('token')
        console.log('token=' + token)
    },
    computed: {
        ...mapState({
            side_collapse: state => state.side_collapse,
        }),
        ...mapGetters({
            notSideCollapse: 'notSideCollapse'
        }),
        //解决不同路由使用相同组件时，页面不更新问题
        key() {
            return this.$route.name !== undefined ? this.$route.name + new Date(): this.$route + new Date()
        }
    },
    methods:{
        //侧边栏展开与折叠
        collapse: function(){
            this.$store.dispatch('toggleSideCollapse',this.notSideCollapse)
        },
        //窗体大小变化时界面相应变化
        resize(){
            if(utils.isPhones() || utils.isTablets()){
                this.$store.dispatch('toggleCollapse',true)
            }else{
                this.$store.dispatch('toggleCollapse',false)
            }
           // utils.setRem()
        },
        restSideMenuSelect(){
            //DOM渲染后的回调
            this.$nextTick(() => {
                this.$refs.vc_side_menu.$refs.side_menu.updateOpened()
                this.$refs.vc_side_menu.$refs.side_menu.updateActiveName()
            })
        }
        

    }
}
</script>

