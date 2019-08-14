<template>
    <div>
        <tree-list
            ref="menuTree"
            label="菜单"
            @on-add-child="addChild"
            :is-fold="true"
            model-width="80%"
            model-height="90%"
            :operate-width="200"
            :filter-form="filterForm"
            expand-key="title"
        >
        </tree-list>
    </div>
</template>

<style scoped>

</style>

<script>
import Vue from 'vue'
import TreeList from '@/components/tree_list'

export default {
    name: 'menu_tree',
    components: {
        'tree-list': TreeList
    },
    data() {
      return {
          filterForm: {}
      }
    },
    mounted(){
        let that = this
        that.title = that.$route.name
        console.log(that.$route.meta.permission)

    },
    methods: {
       //添加下级
       addChild(row,dataForm){
          dataForm.pid = row.id
       }
    }
}
</script>
