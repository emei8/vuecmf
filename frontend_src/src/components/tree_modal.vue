<template>

    <i-modal
            :styles="{top: '20px',height: '90%'}"
            v-model="showDlg"
            :title="title"
            :mask-closable="false"
            :closable="false"
            >
        <template v-if="isLoaded == false">
            <i-spin fix>
                <i-icon type="ios-loading" size=24 class="demo-spin-icon-load"></i-icon>
                <div>Loading</div>
            </i-spin>
        </template>
        <template v-else>
            <tree-table
                    ref="treeModal"
                    :data="treeData"
                    :columns="columns"
                    :stripe="true"
                    :tree-type="true"
                    :is-fold="isTreeFold"
                    :expand-type="false"
                    :selectable="true"
            >
            </tree-table>
        </template>

        <div slot="footer">
            <i-row>
                <i-col span="12" style="text-align: left">
                    <i-switch size="large"  v-model="isTreeFold"  :true-value="true" :false-value="false" >
                        <span slot="open">折叠</span>
                        <span slot="close">展开</span>
                    </i-switch>
                </i-col>
                <i-col span="12">
                    <i-button type="text"  @click="cancel">取消</i-button>
                    <i-button type="primary"  @click="ok">保存</i-button>
                </i-col>
            </i-row>
        </div>

    </i-modal>

</template>

<script>
    import Vue from 'vue'
    import TreeTable from 'tree-table-vue'

    Vue.use(TreeTable)

    export default {
        name: "tree_modal",
        props:[
            'showDlg',
            'title',
            'isLoaded',
            'treeData',
            'columns',
            'isFold',
            'refTreeName'
        ],
        data(){
          return {
              isTreeFold: true
          }
        },
        mounted(){
            this.isTreeFold = this.isFold
        },
        methods: {
            ok: function () {
                this.$emit('on-ok');
            },
            cancel: function () {
                this.$emit('on-cancel');
            }

        }
    }
</script>

<style scoped>

</style>
