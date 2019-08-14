<?php
/**
 * 管理员服务
 * Created by www.vuecmf.com
 * User: emei8 <386196596@qq.com>
 * Date: 2019/4/20
 * Time: 19:25
 */

namespace app\api\service;


use think\Db;
use think\Exception;
use think\facade\Request;
use think\view\driver\Think;

class AdminService extends BaseService
{

    /**
     * 验证登录
     */
    public function checkLogin($post){
        if(empty($post) || empty($post['username']) || empty($post['password'])) throw new Exception('登录名和密码都不能为空!');
        //检测账号是否存在
        $flag = true;
        $user = Db::name('admin')
            ->where('username|email|mobile',$post['username'])
            ->where('status',1)
            ->field('id,password')
            ->find();

        empty($user) && $flag = false;
        !password_verify($post['password'],$user['password']) && $flag = false;

        if(!$flag) throw new Exception('错误的登录名或密码！请检查是否输入有误。');

        $login_time = time();
        $login_ip = Request::ip(1);

        $token = makeToken($post['username'], $login_ip);

        $rs = Db::name('admin')
            ->where('id',$user['id'])
            ->update([
                'last_login_time' => $login_time,
                'last_login_ip' => $login_ip,
                'token' => $token
            ]);
        if(!$rs) throw new Exception('登录出现异常！请稍后重试。');

        $mysql = Db::name('admin')->query('select version() as v;');

        return [
            'token' => $token,
            'user' => [
                'username' => $post['username'],
                'role' => '管理员',
                'last_login_time' => $login_time,
                'last_login_ip' => $login_ip,
            ],
            'server'=> [
                'version' => '1.1.0',
                'os' => PHP_OS,
                'software'=> $_SERVER['SERVER_SOFTWARE'],
                'mysql' => $mysql[0]['v'],
                'upload_max_size' => ini_get('upload_max_filesize')
            ]

        ];

    }


    /**
     * 检测是否登录
     * @param $token
     * @return bool
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function isLogin($token){
        if(empty($token)) throw new Exception('登录已退出！请重新登录。');
        $flag = true;
        $user = Db::name('admin')
            ->where('token',$token)
            ->where('status',1)
            ->field('id,username')
            ->find();

        empty($user) && $flag = false;
        strtolower($token) != makeToken($user['username'],Request::ip(1)) && $flag = false;

        if(!$flag) throw new Exception('登录已退出！请重新登录。');

        $role = Db::name('auth_assignment')->where('user_id',$user['id'])->column('item_name');
        $user['role'] = $role;

        return $user;
    }


    /**
     * 清除登录
     * @param $token
     * @return bool
     * @throws Exception
     */
    public function clearLogin($token){
        if(empty($token)) throw new Exception('登录已退出！');
        $rs = Db::name('admin')
            ->where('token',$token)
            ->where('status',1)
            ->setField('token','');
        if(!$rs) throw new Exception('退出出现异常！');
        return true;
    }


}
