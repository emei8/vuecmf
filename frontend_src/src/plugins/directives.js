import Vue from 'vue'

//权限指令
Vue.directive('has',{
    update: function(el, binding, vnode, oldVnode) {

        let permissionList = vnode.context.$route.meta.permission
        let isExist = false

        if(permissionList == '' || permissionList == undefined || permissionList == null){
            isExist = false
        }else{
            for(let index in permissionList){
                if(binding.value == permissionList[index] || binding.value == index){
                    isExist = true
                }
            }
        }

        if(!isExist && el.parentNode != null){
            el.parentNode.removeChild(el)
        }

    }
})


