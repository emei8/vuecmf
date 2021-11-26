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
use think\App;
use think\Exception;
use think\response\Json;

/**
 * 控制器基类
 * Class Base
 * @package app\vuecmf\controller
 */
abstract class Base
{

    protected $request;             //Request实例
    protected $app;                 //应用实例
    protected $eventPrefix = '';    //事件前缀

    //配置访问权限和表单数据校验中间件
    protected $middleware = [
        Auth::class => ['except' => ['login','logout']], //login, logout不受权限控制
        DataCheck::class => ['only' => ['save']]
    ];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}


    /**
     * 列表
     * @return Json
     */
    public function index(): Json
    {
        try{
            $data = $this->request->post('data');
            $data['model_name'] = $this->request->model_name;
            $data['model_id'] = $this->request->model_id;
            $res = event($this->eventPrefix . 'Index', $data);
            return ajaxSuccess('拉取成功', $res[0]);
        }catch (\Exception $e){
            return ajaxFail($e->getMessage());
        }
    }

    /**
     * 保存
     * @return Json
     */
    public function save(): Json
    {
        try{
            $data = $this->request->post('data');
            $res = event($this->eventPrefix . 'Save', $data);
            if(!$res[0]) throw new Exception('保存失败');
            return ajaxSuccess('保存成功');
        }catch (\Exception $e){
            return ajaxFail($e->getMessage());
        }
    }


}
