<template>
    <div>
        <vuecmf-table
                ref="vcTable"
                :server="serverUrl"
                page="page"
                :limit="20"
                @on-add="save"
                @on-edit="save"
                @on-del="del"
                :show-del-btn="true"
                :show-edit-btn="true"
                :show-add-btn="true"
                :operate-width="240"
                :form-label-width="100"
                :editor-config="editorConfig"
                :width="width"
                :height="height"
                :form-tabs="formTabs"
                model-width="80%"
                model-height="90%"
        >
            <template v-slot:rowAction="row">
                <i-button type="primary" icon="ios-create-outline" size="small" @click="assignRole(row)">分配角色</i-button>
            </template>
        </vuecmf-table>

        <!-- 角色弹窗  -->
        <tree-modal
                ref="roleTreeTable"
                :tree-data="roleTree"
                :columns="roleColumns"
                :show-dlg="roleDlg"
                :title="'分配（' + currentUser + '）角色'"
                :is-loaded="is_role_loaded"
                :is-fold="roleFold"
                @on-ok="saveRole"
                @on-cancel=" roleDlg = false "
        ></tree-modal>

    </div>
</template>
<script>
    import VuecmfTable from 'vuecmf-table/src/lib/vuecmf-table/table.vue'
    import * as utils from "@/utils/screen.js"
    import { mapState } from 'vuex'
    import TreeModal from '@/components/tree_modal'
    import '@/plugins/axios'

    export default {
        name: 'list',
        components:{
            'vuecmf-table': VuecmfTable,
            'tree-modal': TreeModal
        },
        data() {
            return {
                formTabs: [], //表单标签页
                roleTree:[],
                roleColumns: [],
                currentUser: '',
                roleDlg: false,
                roleFold: false,
                is_role_loaded: false,


                height: 300,
                width: 800,
                serverUrl: '',
                serverAxios: axios,
                editorConfig: {
                    // 你的UEditor资源存放的路径,相对于打包后的index.html
                    UEDITOR_HOME_URL: 'NEditor/',
                    // 编辑器不自动被内容撑高
                    autoHeightEnabled: false,
                    // 初始容器高度
                    initialFrameHeight: 300,
                    // 初始容器宽度
                    initialFrameWidth: '100%',
                    // 关闭自动保存
                    enableAutoSave: false
                }
            };
        },
        computed: {
            ...mapState({
                side_collapse: state => state.side_collapse,
            }),
        },
        watch:{
            side_collapse: function(value){
                utils.resizeMain(this, value)
            }
        },
        created(){
            this.serverUrl = this.$api.getUrl(this.$route.meta.model,this.$route.meta.permission.list)
            console.log('list=' + this.serverUrl)
        },
        mounted(){

            let token = this.$cookie.get('token')
            console.log('token = ' + token)
            this.title = this.$route.name
            console.log(this.$route.meta.permission)


            utils.resizeMain(this, this.side_collapse)

        },
        methods: {
            saveRole: function () {
                let that = this
                let selectData = that.$refs['roleTreeTable'].$refs['treeModal'].getCheckedProp('name')

                that.$api.request(that.$route.meta.model, that.$route.meta.permission.set_role, 'post', {
                    current_user: that.currentUser,
                    role_list: selectData
                }).then(function (res) {
                    if (res.code == 0) {
                        that.$Message.success(res.msg)
                        that.roleDlg = false
                    } else {
                        that.$Message.error(res.msg);
                    }
                })
            },
            assignRole: function(scope){
                let that = this
                that.is_role_loaded = false
                that.currentUser = scope.row.username

                that.roleColumns = [
                    { key: 'name',title: '角色名称', width: '300'}
                ]
                that.roleDlg = true

                that.$api.request('auth_item_model', 'get_auth_item_list', 'post', {
                    current_user: that.currentUser
                }).then(function (res) {
                    if (res.code == 0) {
                        that.roleTree = res.data
                        that.is_role_loaded = true
                    } else {
                        that.$Message.error(res.msg);
                    }
                })


            },
            save: function (form_data) {
                let that = this
                if(typeof that.$route.meta.permission.save == "undefined"){
                    that.$Message.error('对不起！您没有此保存数据权限。');
                }else {
                    that.$api.request(that.$route.meta.model, that.$route.meta.permission.save, 'post', form_data).then(function (res) {
                        if (res.code == 0) {
                            that.$Message.success(res.msg)
                            that.$refs.vcTable.refresh()
                        } else {
                            that.$Message.error(res.msg);
                        }

                    })
                }
            },
            del: function (form_data) {
                let that = this
                if(typeof that.$route.meta.permission.del == "undefined"){
                    that.$Message.error('对不起！您没有此删除权限。');
                }else{
                    that.$api.request(that.$route.meta.model,that.$route.meta.permission.del,'post',form_data).then(function(res){
                        if(res.code == 0){
                            that.$Message.success(res.msg)
                            that.$refs.vcTable.refresh()
                        }else{
                            that.$Message.error(res.msg);
                        }

                    })
                }

            },
        }
    }
</script>
