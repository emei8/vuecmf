<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2020~2021 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\middleware;

use app\vuecmf\model\facade\ModelFormRules as ModelFormRulesService;
use think\Exception;
use think\exception\ValidateException;
use think\Response;

/**
 * 数据校验
 * Class DataCheck
 * @package app\vuecmf\middleware
 */
class DataCheck
{
    /**
     * 处理请求: 表单数据校验
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        try{
            if(!$request->isPost()) throw new Exception('Request method error!');

            //根据模型从model_form_rules表中取出数据校验规则
            $rule_list = ModelFormRulesService::getRuleListByModel($request->model_id);

            $rule = []; //校验规则
            $message = []; //错误提示信息
            foreach ($rule_list as $val){
                $rule_value = $val->rule_type . (!empty($val->rule_value) ? ':' . $val->rule_value : '');
                $rule[$val->field_name] = isset($rule[$val->field_name]) ? $rule[$val->field_name] . '|' . $rule_value : $rule_value;
                $message[$val->field_name . '.' . $val->rule_type] = $val->error_tips;
            }

            //开始校验表单数据
            $data = $request->post();
            validate($rule, $message)->batch(true)->check($data['data']);

            return $next($request);

        }catch (ValidateException $e){
            return ajaxFail($e->getMessage(), 1001);
        }catch (\Exception $e){
            return ajaxFail($e->getMessage(), 1002);
        }

    }
}
