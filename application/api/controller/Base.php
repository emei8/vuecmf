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
use think\facade\Env;
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
            $this->user = model('AdminService','service')->isLogin(trim(Request::get('token'),'\t\r\n?'));
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
                $relation_id_str = ''; //已关联产品ID

                $filter = input('post.');
                $where = [];
                if(!empty($filter['filter'])){
                    foreach ($filter['filter'] as $k => $v){
                        if(empty($v))  continue;
                        if(is_array($v)){
                            if(empty($v[0]) || empty($v[1])) continue;
                            $where[$k] = ['between',[$v[0],$v[1]]];
                        }else if($k == 'relation_id'){
                            //排除已关联过的产品
                            $relation_id_str = $this->model->where('id',$v)->value('relation_product_id');
                            $where['id'] = ['not in', explode(',', $relation_id_str)];
                        }else if($k == 'categories_id'){
                            $cid = getSubCid($v);
                            $where[$k] = ['in',$cid];
                        }else{
                            $where[$k] = $v;
                        }
                    }
                }

                if(!empty($filter['keywords']) && !empty($filter['keywords_field'])){
                    $where[implode('|',$filter['keywords_field'])] = ['like','%'.trim($filter['keywords']).'%'];
                }

                $order = $this->model->getPk() .' desc';
                $model_name = $this->model->getName();
                if($model_name == 'ModelField')  $order = 'sort_num';
                $limit = isset($filter['limit']) ? $filter['limit'] : null;

                $page_parame = [];
                $page_parame['list_rows'] = $limit;

                if(!empty($filter['page'])){
                    $page_parame['page'] = $filter['page'];
                }

                

                $data = $this->model->where(new Where($where))->order($order)->paginate($limit)->jsonSerialize();
                $field_info = model('ModelFieldService','service')->getFieldInfo($this->model);

                if(!empty($filter['filter']) && !empty($filter['filter']['relation_id'])){
                    //已关联产品列表
                    $relation_data = $this->model->whereIn('id',explode(',',$relation_id_str))->select();
                    if(!empty($relation_data)){
                        $relation_data = $relation_data->toArray();
                        foreach ($relation_data as &$pro){
                            if(!empty($pro['imgs'])){
                                $arr = explode(',',$pro['thumb_imgs']); //列表中使用缩略图显示
                                $org_arr = explode(',', $pro['imgs']); //原始图片
                                $middle_arr = []; //缩略中图
                                isset($pro['middle_imgs']) && $middle_arr = explode(',',$pro['middle_imgs']);
                                
                                foreach ($arr as  $kk => $url){
                                    $full_url = Request::domain() . '/'.config('upload_path').'/' . $url;
                                    $pro['imgs_file_info'][] = [
                                        'status' => 'finished',
                                        'full_url' => !empty($url) ? $full_url : '',
                                        'url' => !empty($org_arr[$kk]) ? $org_arr[$kk] : '',
                                        'small_url' => $url,
                                        'middle_url' => !empty($middle_arr[$kk]) ? $middle_arr[$kk] : '',
                                        'name' => basename($url)
                                    ];
                                }
                            }
                        }

                        $data['relation_data'] = $relation_data;
                    }

                    

                }

                foreach ($data['data'] as &$val){
                    isset($val['password']) && $val['password'] = '';
                    foreach ($field_info['fields'] as $item){
                        if(in_array($item['data_type'],['image','img','file'])){
                            $val[$item['prop'].'_file_info'] = [];
                            if(!empty($val[$item['prop']])){
                                $arr = explode(',',$val['thumb_'. $item['prop']]); //列表中使用缩略图显示
                                $org_arr = explode(',', $val[$item['prop']]); //原始图片
                                $middle_arr = []; //缩略中图
                                isset($val['middle_'. $item['prop']]) && $middle_arr = explode(',',$val['middle_'. $item['prop']]);
                                
                                foreach ($arr as  $kk => $url){
                                    $full_url = Request::domain() . '/'.config('upload_path').'/' . $url;
                                    $val[$item['prop'].'_file_info'][] = [
                                        'percentage' => 100,
                                        'showProgress' => true,
                                        'size' => 0,
                                        'status' => 'finished',
                                        'full_url' => !empty($url) ? $full_url : '',
                                        'url' => !empty($org_arr[$kk]) ? $org_arr[$kk] : '',
                                        'small_url' => $url,
                                        'middle_url' => !empty($middle_arr[$kk]) ? $middle_arr[$kk] : '',
                                        'name' => basename($url)
                                    ];
                                }
                            }

                        }if($item['data_type'] == 'checkbox'){
                            $val[$item['prop']] = explode(',', $val[$item['prop']]);
                        }else if(in_array($item['data_type'],['date','datetime']) && $item['field_type'] == 'int'){
                            if(empty($val[$item['prop']])){
                                $val[$item['prop']] = '';
                            }else if(is_numeric($val[$item['prop']])){
                                $format_str = $item['data_type'] == 'date' ? 'Y-m-d' : 'Y-m-d H:i:s';
                                $val[$item['prop']] = date($format_str, $val[$item['prop']]);
                            }
                        }else if($item['data_type'] == 'ip'){
                            $val[$item['prop']] = long2ip(intval($val[$item['prop']]));
                        }else if($item['prop'] == 'relation_field_id'){
                            //关联模型取出关联字段列表
                            $val[$item['prop'].'_options'] = model('ModelFieldService','service')->getRelationFieldOptions($val['relation_model_id']);
                        }
                    }

                    if($model_name == 'OrderList' || $model_name == 'PreordainOrder'){
                        $val['order_time'] = date('m/d/Y', strtotime($val['create_time']));
                        $val['order_price'] = number_format($val['final_total_price'],2);

                        //查询订单详情表字段信息
                        $order_fields = Db::name('model_field')
                            ->field('label,field_name,form_type')
                            ->whereNotIn('field_name',['id','status','product_id','order_sn'])
                            ->where('model_id','18')->where('status',1)->select();
                        $tableFields = [];
                        foreach ($order_fields as $field_row){
                            $width = $field_row['field_name'] == 'product_name' ? 260 : 100;
                            $field_row['field_name'] == 'imgs' && $width = 80;
                            array_push($tableFields,[
                                'label' => $field_row['label'],
                                'prop' => $field_row['field_name'],
                                'data_type' => $field_row['form_type'],
                                'width' => $width
                            ]);
                        }

                        if($model_name == 'OrderList'){
                            //查询订单明细数据
                            $order_products = Db::name('order_product')
                                ->where('order_sn', $val['order_sn'])
                                ->select();
                        }else{
                            $order_products = [];
                            $order_products[0] = [];
                            foreach ($tableFields as $fd){
                                $order_products[0][$fd['prop']] = $val[$fd['prop']];
                            }

                        }

                        foreach ($order_products as &$pro){
                            $pro['imgs'] = Request::domain() . '/'.config('upload_path').'/' . $pro['imgs'];
                            $pro['warehouse_id'] = Db::name('warehouse')->where('id',$pro['warehouse_id'])->value('warehouse_name');
                        }
                        

                        $val['expandData'] = [
                            'type' => 'table',
                            'tableFields' => $tableFields,
                            'tableList' => $order_products
                        ];
                    }
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

            if(!empty($data['expected_arrival'])){
                $data['expected_arrival'] = strtotime($data['expected_arrival']);
            }

            if(isset($data[$pk]) && $data[$pk] != null && $data[$pk] != ''){
                if('AuthItem' == $this->model->getName()){
                    $org_name = $this->model->where($pk,$data[$pk])->value('name');
                    Db::name('auth_item_child')->where('parent',$org_name)->setField('parent',$data['name']);
                    Db::name('auth_item_child')->where('child',$org_name)->setField('child',$data['name']);
                }

                if(isset($data['create_time']))  unset($data['create_time']);
                isset($data['update_time']) && $data['update_time'] = time();

                $res = $this->model->update($data);

                if('Product' == $this->model->getName() && count($data) != 2){
                    if($data['status'] == 1){
                        //将产品分类上的数字加1
                        $cid_path = getCidPath($data['categories_id']);
                        Db::name('categories')->whereIn('id',$cid_path)->setInc('product_num');
                    }else{
                        //将产品分类上的数字减去1
                        $cid_path = getCidPath($data['categories_id']);
                        Db::name('categories')->whereIn('id',$cid_path)->setDec('product_num');
                    }

                }else if('OrderList' == $this->model->getName()){
                    if($data['order_status'] == 20){
                        //扣减库存
                        $order_product = Db::name('order_product')->where('order_sn', $data['order_sn'])->select();
                        foreach ($order_product as $pro){
                            Db::name('product')->where('id',$pro['product_id'])->setDec('inventory', $pro['quantity']);
                        }
                    }
                    
                }else if('PreordainOrder' == $this->model->getName()){
                    if($data['order_status'] == 20){
                        //扣减库存
                        Db::name('product')->where('id',$data['product_id'])->setDec('inventory', $data['quantity']);
                    }
                }


                $message = '操作成功';
                $is_add = false;
            }else{
                if('AuthItem' == $this->model->getName() && isset($data['parent'])){
                    Db::name('auth_item_child')->insert([
                        'parent' => $data['parent'],
                        'child' => $data['name']
                    ]);
                    unset($data['parent']);
                }

                isset($data['create_time']) && $data['create_time'] = time();
                isset($data['update_time']) && $data['update_time'] = time();

                $res = $this->model->create($data);

                $data = $res->getData();

                if('Product' == $this->model->getName()){
                    //将产品分类上的数字加1
                    $cid_path = getCidPath($data['categories_id']);
                    Db::name('categories')->whereIn('id',$cid_path)->setInc('product_num');
                }


                $message = '添加成功';
            }

            if(!$res) throw new Exception('无法写入数据库！');

            if('AuthModel' == $this->model->getName()){
                //生成模型相关代码和表
                if(model('AuthModelService','service')->make($data,$is_add)) throw new Exception('生成模型失败！');
            }else if('ModelField' == $this->model->getName()){
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

            if('Product' == $this->model->getName()){
                //将产品分类上的数字减去1
                $cid_path = getCidPath($data['categories_id']);
                Db::name('categories')->whereIn('id',$cid_path)->setDec('product_num');
            }

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

            $id_arr = explode(',', $post['id']);

            switch ($post['type']){
                case 'hot':
                case 'cancel_hot':
                    $status_field = 'is_hot';
                    $status_val = $post['type'] == 'hot' ? 10 : 20;
                    break;
                case 'new':
                case 'cancel_new':
                    $status_field = 'is_new';
                    $status_val = $post['type'] == 'new' ? 10 : 20;
                    break;
                case 'sale':
                case 'cancel_sale':
                    $status_field = 'is_sale';
                    $status_val = $post['type'] == 'sale' ? 10 : 20;
                    break;
                case 'clear':
                case 'cancel_clear':
                    $status_field = 'is_clear';
                    $status_val = $post['type'] == 'clear' ? 10 : 20;
                    break;
                case 'top':
                case 'cancel_top':
                    $status_field = 'is_top';
                    $status_val = $post['type'] == 'top' ? 10 : 20;
                    break;
                case 'preordain':
                case 'cancel_preordain':
                    $status_field = 'is_preordain';
                    $status_val = $post['type'] == 'preordain' ? 10 : 20;
                    break;
                default:
                    $status_field = 'status';
                    $status_val = $post['status'];

            }


            if(count($id_arr) == 1){
                $res = $this->model->where('id',$post['id'])->setField($status_field,$status_val);
            }else{
                $res = $this->model->whereIn('id',$id_arr)->setField($status_field,$status_val);
            }

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
            $is_tree = input('post.tree');

            if($is_tree){
                $tree = [];
                $tree_list = [];

                $table_name = $this->model->getTable();
                $table_name = str_replace(config('database.prefix'), '', $table_name);

                format_tree($tree,$table_name);
                foreach ($tree as $v){
                    $tree_list['c'.$v['id']] = $v['label'];
                }
                unset($tree);
                return api_return($tree_list);

            }else if($action == 'getField'){
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
                    $tree_data = make_tree($table_name, 0, '*', $post,$current_auth_name);
                    return api_return($tree_data);
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


    /**
     * 列表导入数据
     * @return \think\response\Json
     */
    public function importData(){
        try{
            $data = input('post.data');
            $data = json_decode($data,true);

            $rs = $this->model->insertAll($data);
            if(!$rs) throw new Exception('写入数据失败');
            return api_return('','导入成功');
        }catch (Exception $e){
            return api_return('','导入失败!'.$e->getMessage(),1004);
        }

    }


    /**
     * 上传文件
     * @return \think\response\Json
     */
    public function upload(){
        try{
            $file = request()->file('file');
            $upload_path = config('upload_path');
            $upload_base_url = Request::domain() . '/'. $upload_path .'/';
            $arr = [];

            if($file){
                //获取模型名称
                $table_name = strtolower($this->model->getName());
                $model_name = $table_name . '_model';
                $model_id = Db::name('auth_model')->where('model_name',$model_name)->value('id');
                $img_cfg_model = Db::name('image_config')->where('model_id',$model_id)->where('status',1);
                //针对广告模型单独处理
                $tag = input('get.tag');
                if($table_name == 'ad' && !empty($tag)){
                    $img_cfg_model = $img_cfg_model->where('tag',$tag);
                }
                
                $img_cfg = $img_cfg_model->find();

                if(!empty($img_cfg)){
                    $max_size = 1024 * 1024 * 5; //最大5M
                    $info = $file->validate(['size'=>$max_size,'ext'=>'jpg,jpeg,png,gif'])->move(Env::get('root_path') . 'public/'.config('upload_path'));
                }else{
                    $info = $file->move(Env::get('root_path') . 'public/'.config('upload_path'));
                }

                if($info){
                    $file_url = str_replace('\\','/',$info->getSaveName());
                    $arr = [
                        'code' => 200,
                        'full_url' => $upload_base_url . $file_url,
                        'url' => $file_url,
                        'name' => $info->getFilename(),
                        'size' => $info->getSize(),
                        'state' => 'SUCCESS'  //FAIL
                    ];

                    $tmp_file_arr = explode('.', $arr['name']);

                    $small_filename = '';
                    $middle_filename = '';

                    //图片缩略图及水印处理
                    if(!empty($img_cfg)){
                        $image = \think\Image::open('./'. $upload_path .'/'. $file_url);

                        //大图缩略处理, 并替换原始文件
                        if(!empty($img_cfg['big_width']) && !empty($img_cfg['big_height'])){
                            $image->thumb($img_cfg['big_width'],$img_cfg['big_height'],\think\Image::THUMB_CENTER)->save('./'. $upload_path .'/'. $file_url,null,100);
                        }

                        //中图缩略处理
                        if(!empty($img_cfg['middle_width']) && !empty($img_cfg['middle_height'])){
                            $middle_filename = date('Ymd') . '/' . md5('middle_' . $tmp_file_arr[0]) . '.' . $tmp_file_arr[1];
                            $image->thumb($img_cfg['middle_width'],$img_cfg['middle_height'],\think\Image::THUMB_CENTER)->save('./'. $upload_path .'/'. $middle_filename,null,100);
                        }

                        //小图缩略处理
                        if(!empty($img_cfg['small_width']) && !empty($img_cfg['small_height'])){
                            $small_filename = date('Ymd') . '/' . md5('small_' . $tmp_file_arr[0]) . '.' . $tmp_file_arr[1];
                            $image->thumb($img_cfg['small_width'],$img_cfg['small_height'],\think\Image::THUMB_CENTER)->save('./'. $upload_path .'/'. $small_filename);
                        }

                        //水印处理
                        if(!empty($img_cfg['water']) && !empty($img_cfg['water_pos'])){
                            $image->water('./' . $upload_path .'/'. $img_cfg['water'],$img_cfg['water_pos'])->save('./'. $upload_path .'/'. $file_url);
                        }

                    }

                    $arr['small_filename'] = $small_filename;
                    $arr['middle_filename'] = $middle_filename;

                    if(!empty($arr['small_filename'])){
                        $arr['full_url'] = $upload_base_url . $arr['small_filename'];
                    }

                    //写入文件上传日志
                    $file_info = $info->getInfo();
                    Db::name('mediafile')->insert([
                        'thumb_filename' => $small_filename,
                        'filename' => $file_url,
                        'type' => $file_info['type'],
                        'dir' => $upload_path,
                        'alt' => $file_info['name'],
                        'size' => $file_info['size'],
                        'model_id' => $model_id,
                        'created_at' => time(),
                        'use_num' => 1
                    ]);

                }else{
                    $arr = [
                        'message' => $file->getError(),
                        'state' => 'FAIL'  //FAIL
                    ];
                }
            }

            return json($arr);
        }catch (Exception $e){
            return api_return('','上传失败!'.$e->getMessage() . ' ' . $e->getFile() .'  '. $e->getLine(),1003);
        }
    }






}
