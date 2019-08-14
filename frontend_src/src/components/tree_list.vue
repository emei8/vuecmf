<template>
    <div style="overflow: auto; flex: auto">
        <i-row class="tool-bar">
            <i-col :xs="24" :sm="12" :md="12" :lg="12" >
                <i-button type="primary"  @click="add">添加主{{ label }}</i-button>

                <i-switch size="large"  v-model="isFoldStatus" style="margin-left: 10px">
                    <span slot="open">折叠</span>
                    <span slot="close">展开</span>
                </i-switch>
            </i-col>
            <i-col :xs="24" :sm="12" :md="12" :lg="12" >
                <i-input
                        placeholder="输入关键字进行过滤"
                        v-model="filterText"  clearable>
                </i-input>
            </i-col>
        </i-row>

        <tree-table
                ref="table"
                :data="treeData"
                :columns="columns"
                :stripe="true"
                :tree-type="true"
                :is-fold="isFoldStatus"
                :expand-key="expandKey"
                :expand-type="false"
                :selectable="false"
        >
            <template  v-slot:status="{ row, index }">
                <i-switch size="large"  v-model="row.status"  :true-value="1" :false-value="2" @on-change="switchStatus(row.id,row.status)">
                    <span slot="open">开启</span>
                    <span slot="close">关闭</span>
                </i-switch>
            </template>

            <template  v-slot:action="{ row, index }" >
                <i-button type="info" icon="ios-add-circle-outline" size="small" @click="addChild(row)">添加下级</i-button>
                <i-button type="success" icon="ios-create-outline" size="small" @click="editNode(row)">编辑</i-button>
                <i-button type="error" icon="ios-remove-circle-outline" size="small" @click="delForm(row)">删除</i-button>
                <slot name="rowAction" :row="row"></slot>
            </template>

        </tree-table>

        <!-- 添加数据表单 -->
        <tree-form  :data-form-title="formDlgTitle"   :model-width="modelWidth" :model-height="modelHeight"  :form-label-width="formLabelWidth" :data-form="dataForm" :rule-validate="ruleValidate"  :fields-data="fields_data" ref-name="addDataForm" ref="addDataDlg"  @on-save-data-form="saveAddDataForm"></tree-form>

        <!-- 修改数据表单 -->
        <tree-form  :data-form-title="formDlgTitle"  :model-width="modelWidth"  :model-height="modelHeight"  :form-label-width="formLabelWidth" :data-form="editDataForm" :rule-validate="ruleValidate"  :fields-data="fields_data" ref-name="editDataForm" ref="editDataDlg"  @on-save-data-form="saveEditDataForm"></tree-form>

    </div>
</template>

<style scoped>
    button{ margin-left: 4px; margin-right: 4px;}
    .tool-bar{ margin-bottom: 10px;}
</style>

<script>
    import Vue from 'vue'
    import * as utils from '@/utils/screen'
    import TreeTable from 'tree-table-vue'
    import TreeForm from '@/components/tree_form'

    Vue.use(TreeTable)

    export default {
        name: "tree_list",
        components: {
            'tree-form': TreeForm
        },
        props:[
            'label',  //添加数据配置
            'isFold', //树形表格中父级是否默认折叠
            'modelWidth',
            'modelHeight',
            'operateWidth',
            'filterForm',
            'expandKey'
        ],
        data() {
            return {
                fields_data:[],
                columns:[],
                dataForm:{}, //添加表单数据
                editDataForm:{}, //编辑表单数据

                formLabelWidth: 80,
                formDlgTitle: '',
                ruleValidate: {},

                treeData: [],
                filterText: '', //搜索
                isFoldStatus: true,
            }
        },
        beforeMount(){
            window.addEventListener('resize',this.resize)
        },

        mounted(){
            let that = this
            that.loadTableField()
            that.refresh()
            that.resize()
            that.isFoldStatus = that.isFold
        },

        watch: {
            filterText(val) {
                this.filterNode(val)
                this.isFoldStatus = true
            }
        },
        methods: {
            //加载表格字段信息
            loadTableField: function () {
                this.pullData(this.updateTableField,'getField')
            },
            //加载表格字段回调
            updateTableField: function (data) {
                let that = this

                //主体字段
                data.fields.forEach(function (val) {
                    if(val['data_form'] == true){
                        let defalut_val = '';
                        if(val['default'] != undefined) defalut_val = val['default']
                        if(typeof defalut_val == "string") defalut_val = defalut_val.trim()
                        that.$set(that.dataForm,val['prop'],defalut_val)
                        if(val['validate'] != undefined && val['validate'].length != undefined){
                            that.$set(that.ruleValidate,val['prop'],val['validate'])
                        }
                    }

                    let col = {
                        title: val['label'],
                        key: val['prop'],
                        prop: val['prop'],
                        data_type: val['data_type'],
                        width: val['width'],
                        minWidth: val['width'],
                        tips: val['tooltip'],
                        options: val['options'],
                        data_form: val['data_form'],
                        field_id: val['field_id']

                    }

                    if(val['prop'] == 'status'){
                        col['type'] = 'template'
                        col['template'] = 'status'
                    }

                    if(val['model_id'] == val['relation_model_id'] && val['data_type'] == 'select'){
                        //拉取添加/修改表单中的菜单下拉列表选项
                        that.$api.request(that.$route.meta.model,that.$route.meta.permission.tree,'get').then(function(res){
                            if(res.code == 0){
                                col['options'] = res.data
                                col['options'].unshift({ id:0, label:'根' + that.label })
                            }else{
                                that.$Message.error(res.msg);
                            }
                        })
                    }

                    that.fields_data.push(col)
                    if(val['show'] == true){
                        that.columns.push(col)
                    }
                })

                //操作列
                that.columns.push({
                    title: '操作',
                    key: 'id',
                    type: 'template',
                    template: 'action',
                    width: that.operateWidth,
                    data_form: false
                })

            },
            //拉取数据
            pullData: function (callback,action,params,returnData) {
                let that = this

                if(typeof action != "undefined" && action == 'getField'){  //拉取表格字段信息
                    that.$api.request(that.$route.meta.model, that.$route.meta.permission.list, 'post', { action: 'getField'}).then(function (res) {
                        if (res.code == 0) {
                            callback(res.data)
                        } else {
                            that.$Message.error(res.msg);
                        }
                    })

                }else{  //拉取列表数据
                    this.$api.request(that.$route.meta.model, that.$route.meta.permission.list,'post',params).then(function(res){
                        if(res.code == 0){
                            if(res.data != null){
                                that.treeData = JSON.parse(JSON.stringify(res.data))
                            }else{
                                that.treeData = []
                            }

                            if(typeof returnData == 'function'){
                                returnData(that.treeData)
                            }

                        }else{
                            that.$Message.error(res.msg);
                        }
                    })
                }
            },
            refresh(returnData){
                this.pullData(null,null,this.filterForm,returnData)
            },
            resize() {
                if(utils.isPhones()){
                    this.formLabelWidth = 120
                }else if(utils.isTablets()){
                    this.formLabelWidth = 100
                }else{
                    this.formLabelWidth = 80
                }
            },
            //筛选节点
            filterNode(value) {
                this.filterForm['keyword'] = value
                this.pullData(null,null,this.filterForm)
            },
            //开启/关闭菜单
            switchStatus(id,value){
                let that = this
                that.$api.request(that.$route.meta.model,that.$route.meta.permission.status,'post',{id:id,status:value}).then(function(res){
                    if(res.code != 0){
                        that.$Message.error(res.msg)
                    }
                })
            },
            //删除行数据
            delForm(row){
                let that = this
                that.isFoldStatus = false

                if(row.children != undefined && row.children.length > 0) {
                    that.$Message.error('不能删除含有子'+ this.label +'的' + this.label)
                    return false
                }

                that.$api.request(that.$route.meta.model,that.$route.meta.permission.del,'post',row).then(function(res){
                    if(res.code == 0){
                        that.refresh()
                        that.$Message.success(res.msg);
                    }else{
                        that.$Message.error(res.msg);
                    }
                })
            },
            //保存添加数据表单
            saveAddDataForm: function (data) {
                this.save(data)
                this.isFoldStatus = false
            },
            //保存修改的数据表单
            saveEditDataForm: function (data) {
                this.save(data)
                this.isFoldStatus = false
            },
            //添加节点
            add(){
                this.formDlgTitle = "添加" + this.label
                this.$refs['addDataDlg'].dataFormShow = true
                this.isFoldStatus = false
            },
            //添加子节点
            addChild(row) {
                this.$emit('on-add-child',row,this.dataForm)
                this.formDlgTitle = "添加（"+ row.name +"）子" + this.label
                this.$refs['addDataDlg'].dataFormShow = true
            },
            //编辑节点
            editNode(row){
                this.formDlgTitle = "编辑" + this.label
                this.editDataForm = row
                this.$refs['editDataDlg'].dataFormShow = true
                this.isFoldStatus = false
            },
            //保存节点
            save(data){
                let that = this
                that.$api.request(that.$route.meta.model,that.$route.meta.permission.save,'post',data).then(function(res){
                    if(res.code == 0){
                        that.refresh()
                        that.$Message.success(res.msg);
                    }else{
                        that.$Message.error(res.msg);
                    }
                })
            }


        }
    }
</script>
