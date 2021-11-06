<?php
declare (strict_types = 1);

namespace app\vuecmf\middleware;

use think\exception\ValidateException;
use think\Response;

class DataCheck
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        echo "check start <br>";

        try{
            if(!$request->isPost()) throw new Exception('Request method error!');
            $data = $request->post();
            dump($data);
            //$res = validate(User::class)->batch(true)->check($data);

            $rule = [
                'name'  => 'require|max:25',
                'age'   => 'number|between:1,120',
                'email' => 'email',
            ];

            $message = [
                'name.require' => '名称必须',
                'name.max'     => '名称最多不能超过25个字符',
                'age.number'   => '年龄必须是数字',
                'age.between'  => '年龄只能在1-120之间',
                'email'        => '邮箱格式错误',
            ];

            $res = validate($rule, $message)->batch(true)->check($data);

            if($res !== true){
                dump($res);
                return Response::create('Check failed');
            }


            return $next($request);

        }catch (ValidateException $e){
            dump("exception:");
            return json($e->getError(),500);
        }catch (Exception $e){
            return json($e->getMessage(),500);
        }

    }
}
