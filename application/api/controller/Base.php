<?php
/**
 * Created by www.vuecmf.com
 * User: 386196596@qq.com
 * Date: 2019/1/8
 * Time: 23:17
 */

namespace app\api\controller;


use think\Controller;
use think\Db;
use think\db\Where;
use think\Exception;
use think\facade\Request;


class Base extends Controller
{
    protected $model;
    protected $validate;
    protected $service;
    public $user;

    protected $beforeActionList = [
        'isLogin' => ['except'=>'login,getModelApiMap']
    ];

    /**
     * 验证登录
     */
    protected function isLogin(){
        try{
            $this->user = model('AdminService','service')->isLogin(Request::get('token'));
        }catch (Exception $e){
            echo json_encode([
                'data' => '',
                'msg' => $e->getMessage(),
                'code' => 1000
            ]);
            exit();
        }
    }


    /**
     * 模型分页列表
     * @return \think\response\Json
     */
    public function index(){
        try{

            $action = input('post.action');
            if($action == 'getField'){
                $data = model('ModelFieldService','service')->getFieldInfo($this->model);

            }else{
                $filter = input('post.');
                $where = [];
                if(!empty($filter['filter'])){
                    foreach ($filter['filter'] as $k => $v){
                        if(empty($v))  continue;
                        if(is_array($v)){
                            if(empty($v[0]) || empty($v[1])) continue;
                            $where[$k] = ['between',[$v[0],$v[1]]];
                        }else{
                            $where[$k] = $v;
                        }
                    }
                }

                if(!empty($filter['keywords']) && !empty($filter['keywords_field'])){
                    $where[implode('|',$filter['keywords_field'])] = ['like','%'.trim($filter['keywords']).'%'];
                }

                $order = $this->model->getPk() .' desc';
                if($this->model->getName() == 'ModelField')  $order = 'sort_num';
                $limit = isset($filter['limit']) ? $filter['limit'] : null;

                $data = $this->model->where(new Where($where))->order($order)->paginate($limit)->jsonSerialize();

                foreach ($data['data'] as &$val){
                    isset($val['password']) && $val['password'] = '';
                }
            }

            return api_return($data);

        }catch (Exception $e){
            return api_return('',$e->getMessage() . $e->getFile() .'  '. $e->getLine(),1001);
        }

    }


    /**
     * 添加数据
     * @throws \Exception
     * @return \think\response\Json
     */
    public function save(){
        Db::startTrans();
        try{
            $data = input('post.');
            $make_data = $data;

            if(!$this->validate->check($data)){
                throw new Exception('异常：'.(is_array($this->validate->getError()) ? implode(',',$this->validate->getError()) : $this->validate->getError()));
            }

            $pk = $this->model->getPk();
            $is_add = true;

            if(isset($data[$pk]) && $data[$pk] != null && $data[$pk] != ''){
                if('AuthItem' == $this->model->getName()){
                    $org_name = $this->model->where($pk,$data[$pk])->value('name');
                    Db::name('auth_item_child')->where('parent',$org_name)->setField('parent',$data['name']);
                    Db::name('auth_item_child')->where('child',$org_name)->setField('child',$data['name']);
                }
                $res = $this->model->update($data);
                $message = '修改成功';
                $is_add = false;
            }else{
                if('AuthItem' == $this->model->getName() && isset($data['parent'])){
                    Db::name('auth_item_child')->insert([
                        'parent' => $data['parent'],
                        'child' => $data['name']
                    ]);
                    unset($data['parent']);
                }
                $res = $this->model->create($data);
                $data = $res->getData();
                $message = '添加成功';
            }

            if(!$res) throw new Exception('无法写入数据库！');

            if('AuthModel' == $this->model->getName()){
                //生成模型相关代码和表
                if(model('AuthModelService','service')->make($data,$is_add)) throw new Exception('生成模型失败！');
            }

            if('ModelField' == $this->model->getName()){
                //更新模型相关表字段
                if(model('ModelFieldService','service')->updateModelField($make_data)) throw new Exception('更新模型字段失败！');
            }

            Db::commit();

            return api_return($data,$message);

        }catch (Exception $e){
            Db::rollback();
            return api_return('','操作失败!'.$e->getMessage() . ' '.$e->getFile().' '.$e->getLine(),1002);
        }

    }


    /**
     * 删除菜单
     * @return \think\response\Json
     * @throws \Exception
     */
    public function del(){
        Db::startTrans();
        try{
            $data = input('post.');
            unset($data['_index']);
            unset($data['_rowKey']);

            if(empty($data)) throw new Exception('异常：参数缺失！');

            $pk = $this->model->getPk();

            $res = $this->model->where($pk,$data[$pk])->delete();

            if(!$res) throw new Exception('删除失败！');

            if('AuthModel' == $this->model->getName()){
                //清除模型相关代码和表
                if(model('AuthModelService','service')->clearModel($data)) throw new Exception('删除模型失败！');
            }

            if('ModelField' == $this->model->getName()){
                //删除模型相关表字段
                if(model('ModelFieldService','service')->delModelField($data)) throw new Exception('删除模型字段失败！');
            }

            Db::commit();
            return api_return($data,'删除成功！');

        }catch (Exception $e){
            Db::rollback();
            return api_return('','操作失败!'.$e->getMessage(),1003);
        }
    }


    /**
     * 设置状态
     * @return \think\response\Json
     */
    public function setStatus(){
        try{
            $post = input('post.');

            if(empty($post['id'])) throw new Exception('参数ID获取失败！');

            $res = $this->model->where('id',$post['id'])->setField('status',$post['status']);

            if(!$res) throw new Exception('操作失败！');

            return api_return($res,'操作成功！');
        }catch (Exception $e){
            return api_return('','操作失败!'.$e->getMessage(),1004);
        }
    }


    /**
     * 查看详情
     * @return \think\response\Json
     */
    public function getDetail(){
        try{
            $post = input('post.');

            if(empty($post['id'])) throw new Exception('参数ID获取失败！');

            $res = $this->model->where('id',$post['id'])->find();

            if(!$res) throw new Exception('拉取失败！');

            return api_return($res,'拉取成功！');
        }catch (Exception $e){
            return api_return('','拉取失败!'.$e->getMessage(),1005);
        }
    }


    /**
     * 目录管理列表
     * @return \think\response\Json
     */
    public function tree(){
        try{
            $action = input('post.action');
            if($action == 'getField'){
                $data = model('ModelFieldService','service')->getFieldInfo($this->model);
                return api_return($data);

            }else {
                $table_name = $this->model->getTable();
                $table_name = str_replace(config('database.prefix'), '', $table_name);

                $post = [];
                $keyword = input('post.keyword');

                if($table_name == 'auth_item'){
                    $type = input('post.type',10);
                    $current_role_name = input('post.current_role_name');
                    !empty($keyword) && $post['name'] = ['like','%'.$keyword.'%'];

                    $current_user = input('post.current_user');
                    $current_user_id  = false;
                    if(!empty($current_user)) $current_user_id = Db::name('admin')->where('username',$current_user)->value('id');

                    return api_return(make_auth_tree('auth_item','auth_item_child',$type,$post,'',[],$current_role_name,[],$current_user_id));
                }else{
                    !empty($keyword) && $post['title'] = ['like','%'.$keyword.'%'];
                    $current_auth_name = input('post.current_auth');
                    return api_return(make_tree($table_name, 0, '*', $post,$current_auth_name));
                }

            }
        }catch (Exception $e){
            return api_return('','拉取列表失败!'.$e->getMessage(),1003);
        }

    }



    /**
     * 获取格式化目录
     * @return \think\response\Json
     */
    public function format(){
        try{
            $table_name = $this->model->getTable();
            $table_name = str_replace(config('database.prefix'),'',$table_name);
            $tree = [];
            format_tree($tree,$table_name);
            return api_return($tree,'拉取成功');
        }catch (Exception $e){
            return api_return('','拉取格式化目录失败!'.$e->getMessage(),1002);
        }

    }




}
