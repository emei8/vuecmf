<template>
    <div class="welcome-container" >
        <h1>欢迎使用</h1>
        <p>这是列表页</p>
        <p>{{title}}</p>
        <i-button v-has="'get_model'" >添加</i-button>
    </div>
</template>

<style scoped>

</style>

<script>
export default {
    name: 'list1',
    data() {
        return {
            title: ''
        };
    },
    mounted(){
        let token = this.$cookie.get('token')
        
        console.log('token = ' + token)
        this.title = this.$route.name
        console.log(this.$route.meta.permission)

    },
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

    }
}
</script>
