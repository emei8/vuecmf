// vue 配置
/*const px2rem = require('postcss-px2rem')

const postcss = px2rem({
    remUnit: 16   //基准大小 baseSize，需要和rem.js中相同
})*/
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
    publicPath : './',
    outputDir : 'web',
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
		// 打包去掉console 必须引入TerserPlugin
        optimization: {
            minimizer: [new TerserPlugin({
                terserOptions: {
                    compress: {
                        drop_console: true
                    }
                }
            })]
        },
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
