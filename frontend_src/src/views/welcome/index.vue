<template>
    <div class="welcome-container">
        <i-row type="flex" justify="space-between" :gutter="10">
            <i-col span="8">
                <i-card>
                    <p slot="title"><i-icon type="md-clipboard" /> 系统信息</p>

                    <table>
                        <tr><td width="50%">VueCMF版本</td><td>{{ server.version }}</td></tr>
                        <tr><td>服务器操作系统</td><td>{{ server.os }}</td></tr>
                        <tr><td>运行环境</td><td>{{ server.software }}</td></tr>
                        <tr><td>MYSQL版本</td><td>{{ server.mysql }}</td></tr>
                        <tr><td>上传限制</td><td>{{ server.upload_max_size }}</td></tr>
                        <tr><td>登录用户</td><td>{{ user.username }}</td></tr>
                        <tr><td>最后登录时间</td><td>{{ user.last_login_time }}</td></tr>
                        <tr><td>最后登录IP</td><td>{{ user.last_login_ip }}</td></tr>
                    </table>

                </i-card>
            </i-col>
            <i-col span="8">
                <i-card>
                    <p slot="title"><i-icon type="md-clipboard" /> 内容统计</p>
                    <p>暂无信息 </p>
                </i-card>
            </i-col>
            <i-col span="8">
                <i-card>
                    <p slot="title"><i-icon type="md-clipboard" /> 最新消息</p>
                    <p>暂无信息 </p>
                </i-card>
            </i-col>
        </i-row>




    </div>
</template>

<style scoped>
    table{ width: 100%;}
    tr,td{ border-bottom: 1px solid #e9e9e9}
    td{ padding: 8px 0}
</style>

<script>
export default {
    name: 'welcome',
    data() {
        return {
            server: {},
            user:{}
        };
    },
    mounted(){
        this.server = JSON.parse(this.$cookie.get('server'))
        this.user = JSON.parse(this.$cookie.get('user'))
        this.user.last_login_ip = this.$helper.int2ip(this.user.last_login_ip)
        this.user.last_login_time = this.$helper.date('Y-m-d H:i:s',this.user.last_login_time)
    },
    methods: {

    }
}
</script>
