<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
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
use think\facade\Db;
use think\facade\Event;
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
    protected $eventPrefix;         //事件前缀

    //配置访问权限和表单数据校验中间件
    protected $middleware = [
        Auth::class => ['except' => ['login','logout','getApiMap']], //login, logout, getApiMap不受权限控制
        DataCheck::class => ['only' => ['save','saveAll']],
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
        $this->eventPrefix = $this->request->controller();

        //注册事件
        Event::subscribe('app\\vuecmf\\subscribe\\'. $this->eventPrefix .'Event');

        // 控制器初始化
        $this->initialize();

    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 公共动作
     * @param string $event_name  事件名称
     * @param string $success_msg 成功提示信息
     * @param string $fail_msg    异常提示信息
     * @param callable|null $callback  回调处理
     * @param false $start_trans 是否开启事务
     * @return Json
     */
    protected function common(string $event_name, string $success_msg, string $fail_msg = '', callable $callback = null, bool $start_trans = false): Json
    {
        $start_trans == true && Db::startTrans();
        try{
            //触发事件
            $res = event($this->eventPrefix . $event_name, $this->request);
            $result = end($res);
            is_callable($callback) && $result = $callback($result);

            if($fail_msg != '' && $result === null) throw new Exception($fail_msg);

            $start_trans == true && Db::commit();
            return ajaxSuccess($success_msg, $result);
        }catch (\Throwable $e){
            $start_trans == true && Db::rollback();
            return ajaxFail($e);
        }
    }

    /**
     * 列表
     * @return Json
     * 列表展开功能 数据示例
     * $val['expand_data'] = [
            'table_list' => [
                ['col01' => '列值1', 'col02' => '残值2'],
                ['col01' => '列值11', 'col02' => '残值22'],
                ['col01' => '列值111', 'col02' => '残值222'],
            ],
            'table_fields' => [
                ['prop' => 'col01','label' => '列名1', 'width'=> '100px'],
                ['prop' => 'col02','label' => '列名2', 'width'=> '100px'],
            ]
        ];
     */
    public function index(): Json
    {
        return self::common('Index', '拉取成功');
    }

    /**
     * 保存
     * @return Json
     */
    public function save(): Json
    {
        return self::common('Save', '保存成功', '保存失败', null, true);
    }

    /**
     * 批量保存
     * @return Json
     */
    public function saveAll(): Json
    {
        return self::common('SaveAll', '保存成功', '保存失败', null, true);
    }

    /**
     * 详情
     * @return Json
     */
    public function detail(): Json
    {
        return self::common('Detail', '拉取成功', '拉取失败');
    }

    /**
     * 删除
     * @return Json
     */
    public function delete(): Json
    {
        return self::common('Delete', '删除成功', '',null, true);
    }

    /**
     * 下拉列表
     * @return Json
     */
    public function dropdown(): Json
    {
        return self::common('Dropdown', '拉取成功', '拉取失败');
    }

    /**
     * 树形列表
     * @return Json
     */
    public function tree(): Json
    {
        return self::common('Tree', '拉取成功', '拉取失败');
    }


}
