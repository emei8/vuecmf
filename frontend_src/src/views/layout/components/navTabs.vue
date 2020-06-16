<template>
    <!-- 标签页开始 -->
    <div class="nav-tabs-container">
        <div class="nav-tabs">
            <div class="nav-tabs-inner">
                <i-tabs v-model="currentNavTab" type="card"  @on-tab-remove="removeTab" @on-click="clickTab" >
                        <i-tab-pane
                                v-for="(item, index) in navTabs"
                                :key="item.name"
                                :label="item.title"
                                :name="item.name"
                                :closable="item.closable"
                        >
                        </i-tab-pane>
                </i-tabs>
            </div>

        </div>

        <div class="nav-tabs-btn">
            <i-dropdown @on-click="manageNavTabs" trigger="click">
                <div class="tab-btn more">
                    <i-icon type="ios-more" />
                </div>
                <i-dropdown-menu slot="list">
                    <i-dropdown-item name="close-left">关闭左边</i-dropdown-item>
                    <i-dropdown-item name="close-right">关闭右边</i-dropdown-item>
                    <i-dropdown-item name="close-current">关闭当前</i-dropdown-item>
                    <i-dropdown-item name="close-no-current">关闭非当前</i-dropdown-item>
                    <i-dropdown-item name="close-all">关闭全部</i-dropdown-item>
                </i-dropdown-menu>
            </i-dropdown>

            <div class="tab-btn" @click="refresh" title="刷新"><i-icon type="md-refresh" /></div>
            <div class="tab-btn" @click="fullScreen" title="全屏（按ESC键退出全屏）"><i-icon type="ios-desktop-outline" /></div>
        </div>


    </div>
    <!-- 标签页结束 -->
</template>

<script>
import { mapState } from 'vuex'
import Vue from 'vue'

export default {
    name: 'vc-nav-tabs',
    inject: ['reload'], //注入父组件provide中的reload函数
    data(){
        return {

        }
    },
    computed:{
        currentNavTab:{
            get: function () {
                return this.$store.state.navTabsData.currentNavTab
            },
            set: function(newCurrentNavTab) {
                this.$store.state.navTabsData.currentNavTab = newCurrentNavTab
            }
        },
        navTabs: {
            get: function () {
                return this.$store.state.navTabsData.navTabs
            },
            set: function (newNavTabs) {
                this.$store.state.navTabsData.navTabs = newNavTabs
            }
        }

    },
    methods: {
        manageNavTabs(command) {
            let tabs = this.navTabs;
            let activeName = this.currentNavTab;
            console.log(tabs)
            console.log(activeName)
            let current_index = 0; //当前标签页索引
            tabs.forEach((tab, index) => {
                if(tab.name === activeName){
                    current_index = index
                }
            });
            let is_remove = false
            let navTabs = []
            switch (command){
                case 'close-left':
                    navTabs = tabs.filter((tab,index) => (index >= current_index || tab.name == 'welcome'));
                    break;
                case 'close-right':
                    navTabs = tabs.filter((tab,index) => (index <= current_index || tab.name == 'welcome'));
                    break;
                case 'close-current':
                    this.removeTab(activeName)
                    is_remove = true
                    break;
                case 'close-no-current':
                    navTabs = tabs.filter(tab => (tab.name === activeName || tab.name == 'welcome'));
                    break;
                default:
                    activeName = 'welcome';
                    navTabs = [{title: '系统首页',name: 'welcome',closable: false}]

            }

            if(!is_remove){
                this.$store.dispatch('setNavTabsData',{
                    currentNavTab: activeName,
                    navTabs: navTabs
                })
                this.$router.push({ path: activeName })
            }

            this.$emit('on-rest-side-menu-select')

        },

        clickTab(currentTabName){
            //this.$store.dispatch('setNavTabs',currentTab)
            console.log('$router = ', this.$router)
            this.$router.push({ path:currentTabName })

            this.$emit('on-rest-side-menu-select')

        },
        removeTab(targetName) {
            let tabs = this.navTabs;
            let activeName = this.currentNavTab;

            if (activeName === targetName) {
                tabs.forEach((tab, index) => {
                    if (tab.name === targetName) {
                        let nextTab = tabs[index + 1] || tabs[index - 1];
                        if (nextTab) {
                            activeName = nextTab.name;
                        }
                    }
                });
            }

            this.$router.push({ path:activeName })

            let navTabs = tabs.filter(tab => tab.name !== targetName);

            this.$store.dispatch('setNavTabsData',{
                    currentNavTab: activeName,
                    navTabs: navTabs
            })

            this.$emit('on-rest-side-menu-select')

        },
        refresh(){
           this.reload()
        },
        fullScreen(){
            let el = document.documentElement;
            let rfs = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;
            if(typeof rfs != "undefined" && rfs) {
                rfs.call(el);
            }
            return;
        }
    }
}
</script>

<style lang="scss">
@import '~@/assets/style/admin-base.scss';

/* 导航标签页 */
.nav-tabs{ height: 32px !important;}
.nav-tabs-container{
    display: flex;
    height: 36px;
    padding-top: 4px;
}

.ivu-tabs-nav-container{
    font-size: 12px !important;
}

.nav-tabs{
    flex-grow: 1;
    overflow: hidden;
    background-color: #FFF;
    height: $nav_tabs_height;
}

.nav-tabs-inner{
    height: 33px;
    border-bottom: 1px solid #CCC;
    background-color: #F2F2F2;
}

.tab-btn{
    flex-grow: 1;
    color: #666;
    cursor: pointer;
    line-height:30px;
    font-size: 20px;
}

.more{ padding: 0 5px; }

.ivu-tabs-tab{
    height: $nav_tabs_height;
    line-height: 30px;
}

.ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-tab{
    line-height: 24px;
}

.nav-tabs-btn{
    flex: 0 0 96px;
    border: 1px solid #CCC;
    background-color: #FFF;
    border-top-left-radius: 4px;
    text-align: center;
    line-height: 32px;
    display: flex;
    height: 32px;
}

#tab-welcome{ border-left: 0; border-top-left-radius: 0;}

</style>
