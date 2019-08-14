<?php
/**
 * Created by www.vuecmf.com
 * User: 386196596@qq.com
 * Date: 2019/06/30
 * Time: 23:11
 */

namespace app\api\controller;

use think\Db;
use think\Exception;
use think\facade\Request;

class AuthItem extends Base
{

    protected function initialize(){
        $this->model = model('AuthItem');
        if(Request::action() == 'save'){
            $this->validate = new \app\api\validate\AuthItem();
        }
    }

    /**
     * 分配权限
     */
    public function setAuth(){

        Db::startTrans();
        try{
            $post = input('post.');

            if(empty($post['current_role'])) throw new Exception('角色名称不能为空');

            Db::name('auth_item_child')->where('parent',$post['current_role'])->delete();

            $data = [];
            foreach ($post['auth_list'] as $child){
                array_push($data,[
                    'parent' => $post['current_role'],
                    'child' => $child
                ]);
            }

            Db::name('auth_item_child')->insertAll($data);

            Db::commit();

            return api_return('','分配成功！');

        }catch (Exception $e){
            Db::rollback();
            return api_return('',$e->getMessage(),1001);
        }
    }


    /**
     * 分配权限
     */
    public function setMenu(){

        Db::startTrans();
        try{
            $post = input('post.');

            if(empty($post['current_auth'])) throw new Exception('权限组名称不能为空');

            Db::name('auth_item_menu')->where('item_name',$post['current_auth'])->delete();

            $data = [];
            foreach ($post['menu_list'] as $menu_id){
                array_push($data,[
                    'item_name' => $post['current_auth'],
                    'menu_id' => $menu_id
                ]);
            }

            Db::name('auth_item_menu')->insertAll($data);

            Db::commit();

            return api_return('','分配成功！');

        }catch (Exception $e){
            Db::rollback();
            return api_return('',$e->getMessage(),1001);
        }
    }

    /**
     * 分配操作项
     */
    public function setOperate(){
        Db::startTrans();
        try{
            $post = input('post.');
            if(empty($post['current_auth'])) throw new Exception('权限组名称不能为空');
            if(empty($post['operate_list'])) throw new Exception('请选择操作项');

            //先取出权限组与菜单关系ID
            $menu_id_arr = array_keys($post['operate_list']);
            $id_data = Db::name('auth_item_menu')
                ->where('item_name',$post['current_auth'])
                ->whereIn('menu_id',$menu_id_arr)
                ->column('menu_id','id');

            //清除原有的
            Db::name('auth_item_menu_operate')->whereIn('item_menu_id',array_keys($id_data))->delete();

            $data = [];
            foreach ($id_data as $id => $menu_id){
                if(isset($post['operate_list'][$menu_id]) && !empty($post['operate_list'][$menu_id])){
                    foreach ($post['operate_list'][$menu_id] as $operate_id){
                        array_push($data,[
                            'item_menu_id' => $id,
                            'operate_id' => $operate_id
                        ]);
                    }
                }
            }

            Db::name('auth_item_menu_operate')->insertAll($data);

            Db::commit();

            return api_return('','分配成功！');

        }catch (Exception $e){
            Db::rollback();
            return api_return('',$e->getMessage(),1001);
        }
    }


    /**
     * 获取菜单下操作项
     * @return \think\response\Json
     */
    public function getOperate(){
        try{
            $menu = input('post.menu');
            $current_auth = input('post.current_auth');
            if(empty($menu)) throw new Exception('没有分配菜单');
            if(empty($current_auth)) throw new Exception('权限组名称不能为空');

            //取出菜单关联模型
            $menu_model = Db::name('auth_menu')->whereIn('id',$menu)->column('model_id,title','id');
            $model_id_arr = [];
            foreach ($menu_model as $item){
                array_push($model_id_arr,$item['model_id']);
            }
            $model_id_arr = array_unique($model_id_arr);

            //取出模型操作项
            $operate_list = Db::name('model_operate')->field('id,label,model_id')->whereIn('model_id',$model_id_arr)->where('status',1)->select();

            foreach ($menu_model as $menu_id => &$val){
                $val['operate'] = [];
                $val['select_operate'] = [];
                foreach ($operate_list as $val2 ){
                    if($val['model_id'] == $val2['model_id']){
                        array_push($val['operate'],$val2);
                    }
                }
                //已分配的操作项
                $item_menu_id_arr = Db::name('auth_item_menu_operate')->alias('IM')
                    ->join('auth_item_menu AI','IM.item_menu_id = AI.id')
                    ->where('AI.item_name',$current_auth)
                    ->where('AI.menu_id',$menu_id)
                    ->where('AI.status',1)
                    ->where('IM.status',1)
                    ->column('operate_id');
                !empty($item_menu_id_arr) && $val['select_operate'] = $item_menu_id_arr;


            }

            unset($operate_list);

            return api_return($menu_model);

        }catch (Exception $e){
            return api_return('',$e->getMessage() . ' '.$e->getFile().' '.$e->getLine(),1001);
        }
    }


}
