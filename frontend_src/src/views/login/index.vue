<template>
    <div class="login-container">
        <div class="login" id="loginForm">
            <div class="login-top">
                <h1>欢迎使用</h1>
                <i-form :model="loginForm" ref="loginForm"  :rules="ruleLoginForm" >
                    <i-form-item prop="username">
                        <i-input prefix="ios-contact-outline"  type="text" size="large" v-model="loginForm.username" placeholder="账号或邮箱或手机号" />
                    </i-form-item>
                    <i-form-item prop="password">
                        <i-input prefix="ios-lock-outline" type="password" size="large" v-model="loginForm.password" placeholder="密码" />
                    </i-form-item>
                    <i-form-item>
                        <i-button type="success" size="large" shape="circle"  long  @click="handleSubmit('loginForm')">登 录</i-button>
                    </i-form-item>
                </i-form>

            </div>
            <div class="login-bottom">
                <!--<a href="javascript:void(0)" @click="forget">忘记密码</a>-->
                测试账号：test   密码：123456
            </div>
        </div>
        <div class="copyright">
            <p>Copyright &copy; 2019 <a target="_blank" href="http://www.vuecmf.com/">vuecmf.com</a> All rights reserved. Powered by <a target="_blank" href="http://www.vuecmf.com/">vuecmf.com</a>
            </p>
        </div>
        <canvas id="starNight"></canvas>
    </div>


</template>

<style scoped>
    .ivu-btn-success{ background-color: #41b883 !important;}
    .login-container{ width: 100%; position: relative;}
    #starNight {
        position: absolute;
        background: #000;
        overflow: hidden;
        z-index: -1; width: 100%; height: 100%;
    }

    .login {
        width: 32%;
        left: 34%;
        top: 20%;
        position: absolute;
    }

    .login-top {
        background: #ececec;
        border-radius: 25px 25px 0px 0px;
        padding: 40px 60px;
    }

    .login-top h1 {
        text-align: center;
        font-size: 27px;
        font-weight: 500;
        color: #41b883;
        margin: 0px 0px 24px 0px;
    }

    .login-bottom {
        background: #41b883;
        padding: 15px 60px;
        border-radius: 0px 0px 25px 25px;
        text-align: right;
        border-top: 2px solid #35495e;
    }

    .login-bottom a{ color: #35495e; font-size: 14px;}

    .copyright {
        position: absolute;
        padding: 20px 0;
        text-align: center;
        bottom: 15px;
        width: 100%;
    }
    .copyright p {
        font-size: 15px;
        font-weight: 400;
        color: #fff;
    }
    .copyright p a{
        color: #41b883;
    }
    .copyright p a:hover{
        color: #fff;
        transition: 0.5s all;
    }

</style>

<script>
    export default {
        name: 'login',
        data() {
            return {
                //登录相关
                loginForm:{
                    username: '',
                    password: ''
                },
                ruleLoginForm: {
                    username: [
                        { required: true, message: '请输入登录名', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: '请输入密码', trigger: 'blur' },
                        { type: 'string', min: 6, message: '密码长度不得小于六位', trigger: 'blur' }
                    ]
                },

                //背景相关
                animate: true,
                centerX:0,
                centerY:0,
                stars:[],
                numStars: 1800,
                canvas: '',
                warp:0,
                canvasContent:'',
                radius: 0,
                focalLength:0
            };
        },
        mounted(){
            this.canvas = document.getElementById("starNight");
            this.canvas.width = window.innerWidth;
            this.canvas.height = window.innerHeight;
            this.canvasContent = this.canvas.getContext("2d");
            this.radius = '0.'+ Math.floor(Math.random() * 4) + 1;
            this.focalLength = this.canvas.width * 2;

            this.initializeStars();
            window.requestAnimationFrame(this.executeFrame);

        },
        methods: {
            forget: function(){
                this.$Message.warning('请联系管理员！')
            },
            //登录操作
            handleSubmit: function(name) {
                let that = this
                that.$refs[name].validate((valid) => {
                    if (valid) {
                        that.$api.request('admin_model','login','post',that.loginForm).then(function(res){
                            if(res.code == 0){
                                that.$cookie.set('token',res.data.token);
                                that.$cookie.set('user',res.data.user);
                                that.$cookie.set('server',res.data.server);
                                that.$Message.success(res.msg)
                                that.$router.push({ path:'welcome' })
                            }else{
                                that.$Message.error(res.msg);
                            }

                        })
                    } else {
                        that.$Message.error('登录失败!');
                    }
                })
            },

            executeFrame: function(){
                if(this.animate) window.requestAnimationFrame(this.executeFrame);
                this.moveStars();
                this.drawStars();
            },
            initializeStars: function (){
                this.centerX = this.canvas.width / 2;
                this.centerY = this.canvas.height / 2;

                for(let i = 0; i < this.numStars; i++){
                    let star = {
                        x: Math.random() * this.canvas.width,
                        y: Math.random() * this.canvas.height,
                        z: Math.random() * this.canvas.width,
                        o: '0.'+Math.floor(Math.random() * 99) + 1
                    };
                    this.stars.push(star);
                }
            },
            moveStars: function(){
                for(let i = 0; i < this.numStars; i++){
                    let star = this.stars[i];
                    star.z--;

                    if(star.z <= 0){
                        star.z = this.canvas.width;
                    }
                }
            },
            drawStars: function (){
                let pixelX, pixelY, pixelRadius;


                if(this.warp==0){
                    this.canvasContent.fillStyle = "rgba(5,5,5,1)";
                    this.canvasContent.fillRect(0,0, this.canvas.width, this.canvas.height);

                }
                this.canvasContent.fillStyle = "rgba(255, 255, 255, " + this.radius + ")";

                for(let i = 0; i < this.numStars; i++){
                    let star = this.stars[i];

                    pixelX = (star.x - this.centerX) * (this.focalLength / star.z);
                    pixelX += this.centerX;
                    pixelY = (star.y - this.centerY) * (this.focalLength / star.z);
                    pixelY += this.centerY;
                    pixelRadius = 0.3 * (this.focalLength / star.z);

                    //this.canvasContent.fillRect(pixelX, pixelY, pixelRadius, pixelRadius);
                    this.canvasContent.fillStyle = "rgba(255, 255, 255, " + star.o + ")";

                    this.canvasContent.beginPath();
                    this.canvasContent.arc(pixelX, pixelY,pixelRadius,0,2*Math.PI);
                    this.canvasContent.fill();

                }
            }
        }
    }
</script>
