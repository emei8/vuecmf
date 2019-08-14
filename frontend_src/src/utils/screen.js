const PHONES_WIDTH = 576  //手机最小宽度
const TABLETS_WIDTH = 768  //平板最小宽度


// rem 基准大小
const baseSize = 16

//是否为手机端
export function isPhones(){
    let rect = document.body.getBoundingClientRect()
    return rect.width <= PHONES_WIDTH
}

//是否为平板
export function isTablets(){
    let rect = document.body.getBoundingClientRect()
    return rect.width > PHONES_WIDTH && rect.width <= TABLETS_WIDTH
}

//设置根节点字体大小
export function setRem() {
      // 当前页面宽度相对于 750 宽的缩放比例，可根据自己需要修改。
      let scale = document.documentElement.clientWidth / 750
      // 设置页面根节点字体大小
      document.documentElement.style.fontSize = baseSize * Math.min(scale, 2) + 'px'

}

//重绘主工作区
export function resizeMain(vueObj,side_collapse,is_category) {
    let side_width = 220
    if(side_collapse == true){
        side_width = 70
    }
    let current_width = document.documentElement.clientWidth - side_width
    let current_height = document.documentElement.clientHeight - 240
    if(is_category == true){
        current_width = current_width - 160
    }
    vueObj.height = current_height
    vueObj.width = current_width
}