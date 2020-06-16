<template>
    <i-spin fix v-if=" disabled == false " size="large">
        <i-icon type="ios-loading" size=18 class="demo-spin-icon-load"></i-icon>
        <div>Loading</div>
    </i-spin>
</template>

<style scoped>
    .demo-spin-icon-load{
        animation: ani-demo-spin 1s linear infinite;
    }
</style>

<script>
export default {
    name: 'vc-loading',
    props:['disabled'],
    data(){
        return { 
            
        }
    }
}
</script>