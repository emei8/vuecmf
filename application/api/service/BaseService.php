<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 23:05
 */

namespace app\api\service;


abstract class BaseService
{

    protected $model;

    public function __construct()
    {
        $this->init();
    }

    /**
     * 服务类初始化处理
     */
    public function init(){

    }

}