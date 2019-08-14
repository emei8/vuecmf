<?php
/**
 * Created by www.vuecmf.com
 * User: emei8 <386196596@qq.com>
 * Date: 2019/4/4
 * Time: 22:20
 */

namespace app\api\controller;


use think\Db;
use think\Exception;
use think\facade\Request;

class Admin extends Base
{

    protected function initialize(){
        $this->model = model('Admin');
        if(Request::action() == 'save'){
            $this->validate = new \app\api\validate\Admin();
        }
    }

    /**
     * 登录后台
     * @return \think\response\Json
     */
    public function login(){
        if(Request::isPost()){
            try{
                $post = input('post.');
                $loginInfo = model('AdminService','service')->checkLogin($post);
                return api_return($loginInfo,'登录成功！');
            }catch (Exception $e){
                return api_return('',$e->getMessage(),1006);
            }
        }
    }

    /**
     * 退出后台
     * @return \think\response\Json
     */
    public function logout(){
        if(Request::isPost()){
            try{
                //清除token
                model('AdminService','service')->clearLogin(Request::get('token'));
                return api_return('','成功退出系统！');
            }catch (Exception $e){
                return api_return('',$e->getMessage(),1007);
            }
        }
    }


    /**
     * 分配角色
     * @return \think\response\Json
     */
    public function setRole(){
        Db::startTrans();
        try{
            $post = input('post.');

            if(empty($post['current_user'])) throw new Exception('用户名称不能为空');

            $user_id = Db::name('admin')->where('username',$post['current_user'])->value('id');
            if(empty($user_id)) throw new Exception('用户名不存在！');

            Db::name('auth_assignment')->where('user_id',$user_id)->delete();

            $data = [];
            foreach ($post['role_list'] as $child){
                array_push($data,[
                    'user_id' => $user_id,
                    'item_name' => $child,
                    'created_at' => time()
                ]);
            }

            Db::name('auth_assignment')->insertAll($data);

            Db::commit();

            return api_return('','分配成功！');

        }catch (Exception $e){
            Db::rollback();
            return api_return('',$e->getMessage(),1001);
        }
    }


}
