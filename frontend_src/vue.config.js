// vue 配置
/*const px2rem = require('postcss-px2rem')

const postcss = px2rem({
    remUnit: 16   //基准大小 baseSize，需要和rem.js中相同
})*/

module.exports = {
    publicPath : './',
    outputDir : '../public/admin',  //前端打包发布后的文件存放目录
    indexPath : 'index.html',
    assetsDir : 'static',
    lintOnSave : true, //eslint-loader 会将 lint 错误输出为编译警告
    runtimeCompiler : true,
    productionSourceMap : true,
    devServer: { //api代理
        proxy: {
            '/api': {
                target: 'http://www.vuecmf.local',
                ws: true,
                changeOrigin: true
            }
        }
    },
    configureWebpack: {
        module: {
            rules: [{
                  test: /\.css$/,
                  use: [ {
                      loader: 'postcss-loader'
                  },{
                    loader: 'sass-loader'
                  }]
            },{
                test: /\.vue$/,
                use: [
                    {
                        loader: 'iview-loader',
                        options: {
                            prefix: true
                        }
                    }
                ]
            }]
        }
    },
    css: {
        loaderOptions: {
            postcss: {
                plugins: [
                   /* postcss*/
                ]
            }
        }
    }


}
