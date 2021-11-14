<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\controller;

use app\vuecmf\middleware\Auth;
use app\vuecmf\middleware\DataCheck;
use tauthz\facade\Enforcer;
use think\facade\Session;
use think\Request;
use app\vuecmf\model\Admin as AdminModel;


/**
 * 管理员操作相关控制器
 * Class Admin
 * @package app\vuecmf\controller
 */
class Admin extends Base
{
    //login, logout不受权限控制
    protected $middleware = [
        //Auth::class => ['except' => ['login','logout']],
        DataCheck::class
    ];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function login()
    {
        Session::set('vuecmf_login_user', 'lily');

        return 'login success';
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function logout()
    {
        //
    }


    public function index(){

    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        /*AdminModel::create([

        ]);*/
        $res = input('data.test');
        echo $res;

        // adds permissions to a user
        //Enforcer::addPermissionForUser('eve', 'articles', 'read');

        // adds permissions to a rule
        //Enforcer::addPolicy('writer', 'admin','login', 'user');

        //Enforcer::addRoleForUser('lily','reader','admin');

        $p1 = Enforcer::getPermissionsForUser('lily');
        $p2 = Enforcer::getImplicitPermissionsForUser('lily','admin');
        dump($p1);
        dump(['lily per',$p2]);

        $res = Enforcer::enforce('lily', 'admin', 'login', 'user');
        dump($res);
        dump(Enforcer::getRolesForUser('lily','admin'));

        dump(Enforcer::getPolicy());

        event('CheckLogin', ['aaa','ccc']);

        echo 'action save';
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function detail($id)
    {
        echo 'action read ' . $id;
    }


    public function dropdown(){

    }


    public function setRole(){
    }


    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
