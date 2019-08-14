<template>
    <div>
        <template v-for="(item,index) in menuList">
            <i-dropdown placement="right-start" v-if=" item.children != undefined ">
                <i-dropdown-item>{{item.title}}<i-icon type="ios-arrow-forward" /></i-dropdown-item>
                <i-dropdown-menu slot="list">
                    <vc-side-dropdown-menu :menu-list="item.children"></vc-side-dropdown-menu>
                </i-dropdown-menu>
            </i-dropdown>
            <i-dropdown-item :name="item.path" :ref="item.path" v-else>{{item.title}}</i-dropdown-item>
        </template>
    </div>
</template>
<style scoped>
.ivu-icon-ios-arrow-forward{ font-size: 14px;}
</style>

<script>
export default {
    name: 'vc-side-dropdown-menu',
    props:['menuList']
}
</script>
