<template>
    <div>
        <i-tabs type="card" @on-click="changeTab">
            <i-tab-pane name="role" label="角色">
                <tree-list
                        ref="roleTree"
                        label="角色"
                        @on-add-child="addChild"
                        :is-fold="true"
                        model-width="80%"
                        model-height="90%"
                        :operate-width="300"
                        :filter-form="filterRoleForm"
                        expand-key="name"
                >
                    <template   v-slot:rowAction="{ row, index }" >
                        <i-button type="success" icon="ios-create-outline" size="small" @click="assignAuth(row)">分配权限</i-button>
                    </template>
                </tree-list>

            </i-tab-pane>
            <i-tab-pane name="auth" label="权限">
                <tree-list
                        ref="authTree"
                        label="权限"
                        @on-add-child="addChild"
                        :is-fold="true"
                        model-width="80%"
                        model-height="90%"
                        :operate-width="300"
                        :filter-form="filterAuthForm"
                        expand-key="name"
                >
                    <template  v-slot:rowAction="{ row, index }">
                        <i-button type="success" icon="ios-create-outline" size="small" @click="assignMenu(row)">分配菜单</i-button>
                        <i-button type="primary" icon="ios-create-outline" size="small" @click="assignOperate(row)" v-if=" row.menu != null " >分配操作</i-button>
                    </template>
                </tree-list>

            </i-tab-pane>

        </i-tabs>

        <!-- 权限弹窗  -->
        <tree-modal
                ref="authTreeTable"
                :tree-data="authTree"
                :columns="authColumns"
                :show-dlg="authDlg"
                :title="'分配（' + currentRole + '）权限'"
                :is-loaded="is_auth_loaded"
                :is-fold="authFold"
                @on-ok="saveAuth"
                @on-cancel=" authDlg = false "
        ></tree-modal>

        <!-- 菜单弹窗  -->
        <tree-modal
                ref="menuTreeTable"
                :tree-data="menuTree"
                :columns="menuColumns"
                :show-dlg="menuDlg"
                :title="'分配（' + currentAuth + '）菜单'"
                :is-loaded="is_menu_loaded"
                :is-fold="menuFold"
                @on-ok="saveMenu"
                @on-cancel=" menuDlg = false "
        ></tree-modal>

        <!-- 操作项弹窗 -->
        <i-modal
                :styles="{top: '20px',height: '90%'}"
                v-model="operateDlg"
                :title="'分配（' + operateTitle + '）操作项'"
                :mask-closable="false"
                :closable="false"
        >
            <template v-if="is_operate_loaded == false">
                <i-spin fix>
                    <i-icon type="ios-loading" size=24 class="demo-spin-icon-load"></i-icon>
                    <div>Loading</div>
                </i-spin>
            </template>
            <template v-else>
                <template v-for="item,index in menu_operate">
                    <div  class="menu_item"><i-checkbox  :indeterminate="indeterminate[index]"  v-model="menuCheckAll[index]" @click.prevent.native="checkAll(index)">{{ item.title }}</i-checkbox></div>
                    <i-checkbox-group v-model="operateList[index]" @on-change="checkAllGroupChange(operateList[index],index,item.operate.length)"  v-if="item.operate.length > 0" >
                        <i-row>
                            <i-col span="6" v-for=" item2,index2 in item.operate"><i-checkbox :label="item2.id">{{ item2.label }}</i-checkbox></i-col>
                        </i-row>

                    </i-checkbox-group>
                </template>
            </template>

            <div slot="footer">
                <i-button type="text"  @click=" operateDlg = false ">取消</i-button>
                <i-button type="primary"  @click="saveOperate">保存</i-button>
            </div>

        </i-modal>


    </div>
</template>

<style scoped>
    .zk-table__cell-inner .ivu-btn-small{ margin-left: 0 !important;margin-right: 8px !important;}
    .menu_item { border-bottom: 1px solid #e8eaec; font-size: 16px; font-weight: 600; margin-top: 10px; margin-bottom: 5px; padding-bottom: 3px; }
    .ivu-checkbox-group{ padding-left: 15px;}
</style>

<script>
//import Vue from 'vue'
import TreeList from '@/components/tree_list'
import TreeModal from '@/components/tree_modal'

export default {
    name: 'role_tree',
    components: {
        'tree-list': TreeList,
        'tree-modal': TreeModal
    },
    data() {
      return {
          indeterminate: [],
          menuCheckAll: [], //是否全选菜单下操作项
          operateList: [], //已分配操作
          menu_operate: [], //分配操作列表
          is_operate_loaded: false,
          operateTitle: '',
          operateDlg: false,

          authFold: true,
          is_auth_loaded: false,
          authColumns: [],
          authData: [], //选中权限
          authTree: [], //权限目录树
          authDlg: false,

          menuFold: true,
          is_menu_loaded: false,
          menuColumns: [],
          menuData: [], //选中菜单
          menuTree: [], //菜单目录树
          menuDlg: false,

          currentRole: '',
          currentAuth: '',

          filterRoleForm: {},
          filterAuthForm: {}
      }
    },
    created(){
        this.filterRoleForm = {
            type: 10
        }

        this.filterAuthForm = {
            type: 20
        }
    },
    watch: {

    },
    mounted(){
        let that = this
        that.title = that.$route.name

    },
    methods: {
        checkAll: function(index){
            let that = this

            if (that.indeterminate[index]) {
                that.$set(that.menuCheckAll,index,false)
            } else {
                that.$set(that.menuCheckAll,index,!that.menuCheckAll[index])
            }

            that.$set(that.indeterminate,index,false)

            if (that.menuCheckAll[index]) {
                let operate_item = []
                that.menu_operate[index].operate.forEach(function (item) {
                    operate_item.push(item.id)
                })
                that.$set(that.operateList,index,operate_item)
            } else {
                that.$set(that.operateList,index,[])
            }
        },
        checkAllGroupChange (data,index,length) {
            if (data.length === length) {
                this.indeterminate[index] = false;
                this.menuCheckAll[index] = true;
            } else if (data.length > 0) {
                this.indeterminate[index] = true;
                this.menuCheckAll[index] = false;
            } else {
                this.indeterminate[index] = false;
                this.menuCheckAll[index] = false;
            }
        },
        changeTab: function(name){
            if(name == 'auth'){
                this.$refs['authTree'].dataForm.type = 20
            }else{
                this.$refs['roleTree'].dataForm.type = 10
            }
        },
        //分配权限
        assignAuth(row){
            let that = this
            that.is_auth_loaded = false
            that.currentRole = row.name
            that.filterAuthForm['current_role_name'] = that.currentRole
            that.authColumns = [
                { key: 'name',title: '权限组', width: '300'},
            ]
            that.authDlg = true

            that.$refs['authTree'].refresh(function (treeData) {
                that.authTree = treeData
                that.is_auth_loaded = true
            })

        },
        //分配菜单
        assignMenu(row){
            let that = this
            that.is_menu_loaded = false
            that.currentAuth = row.name

            that.menuColumns = [

                { key: 'title',title: '菜单名称', width: '300'}

            ]
            that.menuDlg = true

            that.$api.request('auth_menu_model', 'get_menu_tree', 'post', {
                current_auth: that.currentAuth
            }).then(function (res) {
                if (res.code == 0) {
                    that.menuTree = res.data
                    that.is_menu_loaded = true
                } else {
                    that.$Message.error(res.msg);
                }
            })

        },
        //分配操作项
        assignOperate(row){
            let that = this
            that.operateDlg = true
            that.operateTitle = row.name
            that.is_operate_loaded = false

            that.$api.request(that.$route.meta.model, that.$route.meta.permission.get_operate, 'post', {
                menu: row.menu.join(','),
                current_auth: that.operateTitle,
            }).then(function (res) {
                if (res.code == 0) {
                    that.menu_operate = res.data

                    for (let index  in that.menu_operate) {
                        if(that.menu_operate[index].select_operate.length == that.menu_operate[index].operate.length){
                            that.$set(that.menuCheckAll,index,true)
                            that.$set(that.indeterminate,index,false)
                        }else{
                            that.$set(that.menuCheckAll,index,false)
                            that.$set(that.indeterminate,index,false)
                        }

                        that.$set(that.operateList,index,that.menu_operate[index].select_operate)

                    }

                    that.is_operate_loaded = true
                } else {
                    that.$Message.error(res.msg);
                }
            })
        },
        //添加下级
        addChild(row,dataForm){
            dataForm.parent = row.name
        },
        //分配权限
        saveAuth: function () {
            let that = this
            let selectData = that.$refs['authTreeTable'].$refs['treeModal'].getCheckedProp('name')

            that.$api.request(that.$route.meta.model, that.$route.meta.permission.set_auth, 'post', {
                current_role: that.currentRole,
                auth_list: selectData
            }).then(function (res) {
                if (res.code == 0) {
                    that.$Message.success(res.msg)
                    that.authDlg = false
                    that.$refs['roleTree'].refresh()
                } else {
                    that.$Message.error(res.msg);
                }
            })
        },
        //分配菜单
        saveMenu(){
            let that = this
            let selectData = that.$refs['menuTreeTable'].$refs['treeModal'].getCheckedProp('id')

            that.$api.request(that.$route.meta.model, that.$route.meta.permission.set_menu, 'post', {
                current_auth: that.currentAuth,
                menu_list: selectData
            }).then(function (res) {
                if (res.code == 0) {
                    that.$Message.success(res.msg)
                    that.menuDlg = false
                    that.$refs['authTree'].refresh()
                } else {
                    that.$Message.error(res.msg);
                }
            })
        },
        //分配操作项
        saveOperate(){
            let that = this
            let operate_list = {}
            that.operateList.forEach(function (item,index) {
                if(item != null) operate_list[index] = item
            })

            that.$api.request(that.$route.meta.model, that.$route.meta.permission.set_operate, 'post', {
                current_auth: that.operateTitle,
                operate_list: operate_list
            }).then(function (res) {
                if (res.code == 0) {
                    that.$Message.success(res.msg)
                    that.operateDlg = false
                    that.$refs['authTree'].refresh()
                } else {
                    that.$Message.error(res.msg);
                }
            })

        }

    }
}
</script>
