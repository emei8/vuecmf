/**
 * cookie操作
 */
export function set(name,value,expire) {
    if(expire == undefined || expire == '' || expire == 0)  expire = 86400 //默认为24小时
    let date = new Date()
    if(typeof value == "object") value = JSON.stringify(value)
    date.setSeconds(date.getSeconds() + expire)
    document.cookie = name + "=" + escape(value) + "; expires=" + date.toGMTString()
}
 
export function get(name){
    if (document.cookie.length>0){
        let start=document.cookie.indexOf(name + "=")
        if (start!=-1){ 
            start=start + name.length+1 
            let end=document.cookie.indexOf(";",start)
            if (end==-1) end=document.cookie.length
                return unescape(document.cookie.substring(start,end))
            } 
        }
    return ""
}
 
export function remove(name){
    set(name, "", -1)
}