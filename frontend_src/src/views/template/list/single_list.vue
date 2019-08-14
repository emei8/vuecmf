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
                :show-add-btn="show_add_btn"
                :operate-width="150"
                :form-label-width="100"
                :editor-config="editorConfig"
                :width="width"
                :height="height"
                model-width="80%"
                model-height="90%"
        >
            <template v-slot:headerAction="">
                <i-button v-has="'show_add_single_btn'"  type="primary"><i-icon type="md-add-circle" /> 测试权限</i-button>
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
                height: 300,
                width: 800,
                serverUrl: '',
                show_add_btn: true,
                serverAxios: axios,
                editorConfig: {
                    // 你的UEditor资源存放的路径,相对于打包后的index.html
                    UEDITOR_HOME_URL: '/public/NEditor/',
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

            this.show_add_btn = this.$helper.getBtnAuth('show_add_single_btn',this.$route.meta.permission)


            utils.resizeMain(this, this.side_collapse)

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
