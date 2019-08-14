<template>
    <div>
        <template v-if="!sideCollapse">
            <template v-for="(item,index) in menuList">
                <i-submenu :name="item.id" v-if=" item.children != undefined ">
                    <template slot="title">
                        <i-icon :type="item.icon" v-if=" item.icon != undefined " />
                        {{item.title}}
                    </template>
                    <vc-side-menu-item :side-collapse="sideCollapse" :menu-list="item.children"></vc-side-menu-item>
                </i-submenu>
                <i-menu-item  :name="item.id"  :to="{ path:item.path }" v-else><i-icon :type="item.icon" v-if=" item.icon != undefined " />{{item.title}}</i-menu-item>
            </template>
        </template>
        <template v-else>
            <template v-for="(item,index) in menuList">
                <i-dropdown placement="right-start" v-if=" item.children != undefined " @on-click="toPath" >
                    <i-menu-item :name="item.id"><i-icon :type="item.icon" v-if=" item.icon != undefined " /></i-menu-item>
                    <i-dropdown-menu slot="list">
                        <vc-side-dropdown-menu :menu-list="item.children"></vc-side-dropdown-menu>
                    </i-dropdown-menu>
                </i-dropdown>
                <i-tooltip :content="item.title" placement="right" v-else>
                    <i-menu-item :name="item.id" :to="{ path:item.path }" ><i-icon :type="item.icon" v-if=" item.icon != undefined " /></i-menu-item>
                </i-tooltip>
            </template>
        </template>
    </div>
</template>
<script>
import sideDropdownMenu from './sideDropdownMenu.vue'

export default {
    name: 'vc-side-menu-item',
    props:['sideCollapse','menuList'],
    components:{
        'vc-side-dropdown-menu':sideDropdownMenu
    },
    data() {
        return {
        }
    },
    methods:{
        toPath(path){
            this.$router.push({ path:path })
        }
    }
}
</script>

