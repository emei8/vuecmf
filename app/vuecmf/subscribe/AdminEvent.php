<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\subscribe;

use app\vuecmf\model\Admin;
use think\Exception;

class AdminEvent
{

    protected $eventPrefix = 'Admin';

    /**
     * 新增/更新
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function onSave($data){
        if(empty($data['username']) || empty($data['email']) || empty($data['mobile'])) throw new Exception('用户名、邮箱和手机都不能为空！');
        if(!empty($data['id'])){
            $exist = Admin::where('id', '<>', $data['id'])
                ->whereRaw('username = :username OR email = :email OR mobile = :mobile', [
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'mobile' => $data['mobile']
                ])
                ->value('id');
            if($exist) throw new Exception('已存在相同的用户名、邮箱或手机！');

            Admin::update($data);

        }else{
            $exist = Admin::where('username', $data['username'])
                ->whereOr('email', $data['email'])
                ->whereOr('mobile', $data['mobile'])
                ->value('id');
            if($exist) throw new Exception('已存在相同的用户名、邮箱或手机！');

            Admin::create($data);

        }

        return true;
    }

}
