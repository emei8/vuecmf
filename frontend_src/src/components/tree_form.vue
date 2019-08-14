<template>
    <div>
        <!-- 数据表单 -->
        <i-modal
                :title="dataFormTitle"
                v-model="dataFormShow"
                :width="modelWidth"
                class-name="vertical-center-modal"
                :styles="{top: '20px',height: modelHeight,zIndex:100}"
                draggable
        >
            <div slot="header">
                <h3>{{ dataFormTitle }}</h3>
            </div>
            <i-form :label-width="formLabelWidth" :model="dataForm"  :ref="refName"  :rules="ruleValidate">
                <template v-for="(item,index) in fieldsData">
                    <template v-if="item.data_form == true">
                        <template v-if="item.data_type == 'hidden'">
                            <input type="hidden" :key="dataForm[item.prop]" v-model="dataForm[item.prop]" >
                        </template>
                        <template v-else>
                            <i-row :key="index">
                                <i-form-item :label="item.title"  :prop="item.prop" >
                                    <i-select  style="width:200px"  v-model="dataForm[item.prop]" filterable  placeholder="请选择" v-if=" item.data_type == 'select' ">
                                        <template v-for="(option_item,option_index) in item.options">
                                            <i-option  v-if=" typeof option_item == 'object' "
                                                      :key="option_index"
                                                      :value="option_item.id">{{ option_item.label }}</i-option>
                                            <i-option v-else
                                                      :key="option_index"
                                                      :value="parseInt(option_index)">{{ option_item }}</i-option>
                                        </template>

                                    </i-select>

                                    <i-date-picker
                                            v-else-if=" item.data_type == 'date' "
                                            v-model="dataForm[item.prop]"
                                            type="date"
                                            format="yyyy-MM-dd"
                                            placeholder=""
                                            @on-change="(datetime) =>{ changeDatetime(datetime,item.prop)}"
                                            @on-open-change="dataFormShow = true"
                                    >
                                    </i-date-picker>

                                    <i-date-picker
                                            v-else-if=" item.data_type == 'datetime' "
                                            v-model="dataForm[item.prop]"
                                            type="datetime"
                                            format="yyyy-MM-dd HH:mm:ss"
                                            placeholder=""
                                            @on-change="(datetime) =>{ changeDatetime(datetime,item.prop)}"
                                            @on-open-change="dataFormShow = true"
                                    >
                                    </i-date-picker>

                                    <i-radio-group  v-else-if=" item.data_type == 'radio' "  v-model="dataForm[item.prop]">
                                        <template v-for="(option_item,option_index) in item.options">
                                            <i-radio :key="parseInt(option_index)" :label="parseInt(option_index)" v-if=" /^\d+$/.test(option_index) "><span>{{ option_item }}</span></i-radio>
                                            <i-radio :key="parseInt(option_index)" :label="parseFloat(option_index)" v-else-if=" /^\d+\.+\d+$/.test(option_index) "><span>{{ option_item }}</span></i-radio>
                                            <i-radio :key="parseInt(option_index)" :label="option_index" v-else><span>{{ option_item }}</span></i-radio>
                                        </template>
                                    </i-radio-group>

                                    <i-input v-model="dataForm[item.prop]" type="textarea" :rows="4" :placeholder="'请输入' + item.title" v-else-if=" item.data_type == 'textarea' "></i-input>

                                    <i-input v-model="dataForm[item.prop]" :placeholder="'请输入' + item.title" v-else ></i-input>
                                </i-form-item>

                            </i-row>
                        </template>

                    </template>


                </template>
            </i-form>
            <div slot="footer" class="dialog-footer">
                <i-button  type="default" @click="resetDataForm">重置</i-button>
                <i-button type="primary" @click="saveDataForm()">保存</i-button>
            </div>

        </i-modal>

    </div>
</template>

<style>
    .ivu-modal-content{ height: 100% !important;}
    .ivu-modal-body{ height: calc(100% - 120px) !important;}
    .ivu-modal-body{ overflow: auto ;}
    .ivu-input-wrapper{ width: 98% !important;}
</style>


<script>
    export default {
        name:'tree-form',
        props:['dataFormTitle','modelWidth','modelHeight','formLabelWidth','dataForm','ruleValidate','fieldsData','refName'],
        data(){
            return {
                dataFormShow: false
            }
        },
        updated:function () {
            let that = this
            that.fieldsData.forEach(function (val) {
                if(val.data_type == 'date' ||  val.data_type == 'datetime'){
                    //处理iview的DatePicker时间带T带Z格式问题
                    that.dataForm[val.prop] = that.getFormatDate(that.dataForm[val.prop])
                }
            })

        },
        mounted: function () {
        },
        methods:{
            //处理iview的DatePicker时间带T带Z格式问题
            getFormatDate: function(dateStr){
                let dateJson = new Date(dateStr).toJSON()
                let dateRes = new Date(+ new Date(dateJson) + 8 * 3600 * 1000).toISOString().replace(/T/g,' ').replace(/\.[\d]{3}Z/,'')
                return dateRes
            },
            //重置数据表单
            resetDataForm: function () {
                this.$refs[this.refName].resetFields()
            },
            //保存数据表单
            saveDataForm: function () {
                this.$emit('on-save-data-form',this.dataForm)
            },
            //处理日历控件数据
            changeDatetime: function (datetime, prop) {
                this.dataForm[prop] = datetime
                this.dataFormShow = true
            },
        }
    }
</script>

