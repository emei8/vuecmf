<?php
/**
 * Created by www.vuecmf.com
 * User: emei8 <386196596@qq.com>
 * Date: 2019/4/4
 * Time: 22:20
 */

namespace app\api\controller;


use think\facade\Request;

class ModelOperate extends Base
{

    protected function initialize(){
        $this->model = model('ModelOperate');
        if(Request::action() == 'save'){
            $this->validate = new \app\api\validate\ModelOperate();
        }
    }


}