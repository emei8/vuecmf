<template>
  <div id="app">
    <router-view v-if="isRouterAlive" />
  </div>
</template>

<script>
export default {
  name: 'app',
  provide(){
    return {
      reload: this.reload
    }
  },
  data(){
    return {
      isRouterAlive: true
    }
  },
  created() {
      //在页面加载时更新store
      if (sessionStorage.getItem("store") ) {
          this.$store.replaceState(Object.assign({}, this.$store.state,JSON.parse(sessionStorage.getItem("store"))))
      } 

      //监听页面刷新，刷新时将store存入sessionStorage
      window.addEventListener("beforeunload",()=>{
          sessionStorage.setItem("store",JSON.stringify(this.$store.state))
      })

  },
  methods:{
    //重载页面
    reload(){
        sessionStorage.clear()

        this.isRouterAlive = false
        this.$nextTick(function(){
          this.isRouterAlive = true
        })
    }
  }

}
</script>

<style lang="scss" scoped>
  #app {
    display: flex;
    position: absolute;
    top: 0px;
    left: 0px;
    right: 0px;
    bottom: 0px;

  }
</style>


