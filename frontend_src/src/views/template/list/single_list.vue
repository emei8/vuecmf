<template>
    <div>
        <vuecmf-table
                ref="vcTable"
                :server="serverUrl"
                :import-server="importUrl"
                :upload-file-server="uploadUrl"
                :upload-file-max-size="uploadFileMaxSize"
                page="page"
                :limit="20"
                @on-add="save"
                @on-edit="save"
                @on-del="del"
                :show-del-btn="show_del_btn"
                :show-edit-btn="show_edit_btn"
                :show-add-btn="show_add_btn"
                :operate-width="150"
                :form-label-width="100"
                :editor-config="editorConfig"
                :width="width"
                :height="height"
                model-width="80%"
                model-height="90%"
                :form-tabs="formTabs"
        >
            <template v-slot:headerAction="">
                <!--<i-button v-has="'show_add_single_btn'"  type="primary"><i-icon type="md-add-circle" /> 测试权限</i-button>-->
            </template>
        </vuecmf-table>
    </div>
</template>
<script>
    import VuecmfTable from 'vuecmf-table/src/lib/vuecmf-table/table.vue'
    import * as utils from "@/utils/screen.js"
    import { mapState } from 'vuex'
    import '@/plugins/axios'

    export default {
        name: 'list',
        components:{
            'vuecmf-table': VuecmfTable
        },
        data() {
            return {
                formTabs: [], //表单标签页
                height: 300,
                width: 800,
                serverUrl: '',
                uploadUrl: '', //表单上传文件地址
                importUrl: '', //表单导入数据地址
                uploadFileMaxSize: 5120, //最大可上传文件大小 KB
                show_add_btn: true,
                show_edit_btn: true,
                show_del_btn: true,
                serverAxios: axios,
                editorConfig: {
                    // 你的UEditor资源存放的路径,相对于打包后的index.html
                    UEDITOR_HOME_URL: 'NEditor/',  //   /public/NEditor/
                    // 编辑器不自动被内容撑高
                    autoHeightEnabled: false,
                    // 初始容器高度
                    initialFrameHeight: 300,
                    // 初始容器宽度
                    initialFrameWidth: '100%',
                    // 关闭自动保存
                    enableAutoSave: false,
                    zIndex: 9000
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
            this.uploadUrl = this.$api.getUrl(this.$route.meta.model,this.$route.meta.permission.upload)
            this.importUrl = this.$api.getUrl(this.$route.meta.model,this.$route.meta.permission.import)
            console.log('list=' + this.serverUrl)
        },
        mounted(){

            let token = this.$cookie.get('token')
            console.log('token = ' + token)
            this.title = this.$route.name
            console.log(this.$route.meta.permission)

            let model_name = this.$route.meta.model.replace('_model','')
            this.show_add_btn = this.$helper.getBtnAuth('show_add_' + model_name + '_btn',this.$route.meta.permission)

            if(typeof this.$route.meta.permission.save == "undefined"){
                this.show_edit_btn = false
            }

            if(typeof this.$route.meta.permission.del == "undefined"){
                this.show_del_btn = false
            }

            utils.resizeMain(this, this.side_collapse)

            if('single_list' == model_name){
                this.formTabs[0] = []
                this.formTabs[0]['tab_name'] = '基本信息';
                this.formTabs[0]['tab_fields'] = ['title','tag','status'];
                this.formTabs[1] = []
                this.formTabs[1]['tab_name'] = '内容';
                this.formTabs[1]['tab_fields'] = ['detail'];
            }else if('collect' == model_name){
                this.show_edit_btn = false
                this.show_del_btn = false
            }
            
            

        },
        /*updated(){
            utils.resizeMain(this, this.side_collapse)
        },*/
        /*beforeRouteLeave (to, from, next){
            next({name: to.name})
        },
        watch: {
            '$route' (to, from) {
                this.title = this.$route.name
                console.log(this.$route.name)
                this.routeParams = this.$route.fullPath
            }
        },*/
        methods: {
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
