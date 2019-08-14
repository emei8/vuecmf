<?php
/**
 * Created by www.vuecmf.com
 * User: 386196596@qq.com
 * Date: 2019/06/23
 * Time: 22:59
 */

namespace app\api\controller;

use think\facade\Request;

class SingleList extends Base
{

    protected function initialize(){
        $this->model = model('SingleList');
        if(Request::action() == 'save'){
            $this->validate = new \app\api\validate\SingleList();
        }
    }

}