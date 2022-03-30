<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\subscribe;

use app\vuecmf\model\ModelConfig;
use app\vuecmf\model\ModelField;
use app\vuecmf\model\ModelForm;
use think\Request;

/**
 * 模型表单事件
 * Class ModelFormEvent
 * @package app\vuecmf\subscribe
 */
class ModelFormEvent extends BaseEvent
{

    /**
     * 下拉列表
     * @param Request $request
     * @return mixed
     */
    public function onDropdown(Request $request){
        $data = $request->post('data',[]);

        if(isset($data['model_id']) && empty($data['model_id'])) return [];

        return ModelForm::alias('A')
            ->join('model_field F', 'F.id = A.model_field_id and F.status = 10','LEFT')
            ->join('field_option FP', 'FP.option_value = A.type and FP.status = 10', 'LEFT')
            ->where('A.model_id', $data['model_id'])
            ->where('A.status', 10)
            ->where('F.status', 10)
            ->where('FP.status', 10)
            ->column('concat(F.field_name,"(",F.label,")-",FP.option_label) label', 'A.id');

    }

}
