<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * API公共返回结果
 * @param $data
 * @param string $msg
 * @param int $error_code
 * @return \think\response\Json
 */
function api_return($data,$msg = '', $error_code = 0) {
    $response = [
        'data' => $data,
        'msg' => $msg,
        'code' => $error_code
    ];

    return json($response);
}


/**
 * 生成导航权限菜单
 * @param int $pid
 * @param int $top_id
 * @param array $auth_menu  授权的菜单ID
 * @param array $auth_operate 授权的操作项ID
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function make_menu_tree($pid = 0,$top_id = 0,$auth_menu = null,$auth_operate = null){
    $index_key = \think\Db::name('auth_menu')->getPk();

    $query = \think\Db::name('auth_menu')->alias('MU')
            ->field('MU.*,ML.component AS component,MO.operate_name AS path,ML.model_name,ML.filter_field,MU.id as top_id')
            ->join('auth_model ML','MU.model_id = ML.id','LEFT')
            ->join('model_operate MO','MO.id = ML.main_operate_id','LEFT')
            ->where('MU.pid',$pid)
            ->where('MU.status',1);

    //可根据菜单权限在where中过滤
    !empty($auth_menu) && $query->whereIn('MU.id',$auth_menu);

    $res = $query->select();

    $tree = [];
    if(!empty($res)){
        foreach($res as $val){
            $val['component'] == null && $val['component'] = '';
            $val['path'] == null && $val['path'] = '';

            if($pid == 0 && $top_id == 0){
                $val['top_id'] = $val['id'];
            }else{
                $val['top_id'] = $top_id;
            }

            //操作权限
            $val['permission'] = [];
            if($val['model_id']){
                $operate_query = \think\Db::name('model_operate')
                    ->where('model_id',$val['model_id'])
                    ->where('status',1);

                //操作权限过滤
                !empty($auth_operate) && $operate_query->whereIn('id',$auth_operate);

                $operate = $operate_query->column('operate_name','operate_type');

               !empty($operate) && $val['permission'] = $operate;
            }

            $pid == 0 && $val['path'] = strval($val[$index_key]);

            $tree[$val[$index_key]] = $val;
            $child = make_menu_tree($val[$index_key],$val['top_id'],$auth_menu,$auth_operate);
            if($child){
                $tree[$val[$index_key]]['children'] = $child;
                reset($child);
                $tree[$val[$index_key]]['active'] = key($child);
                unset($child);
            }


        }
        return $tree;
    }

}



/**
 * 生成树管理列表
 * @param $table_name
 * @param int $pid
 * @param string $fields
 * @param array $post
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function make_tree($table_name, $pid = 0, $fields = '*',$post = [],$current_auth_name = '',$menu_data = []){
    $index_key = \think\Db::name($table_name)->getPk();
    $query = \think\Db::name($table_name)
        ->field($fields)
        ->where('pid',$pid);

    if(!empty($post)){
        foreach ($post as $key => $val)
        $query->where($key,$val[0],$val[1]);
    }

    $res = $query->select();

    //分配的菜单
    if(!empty($current_auth_name)){
        $menu_data = \think\Db::name('auth_item_menu')
            ->where('item_name',$current_auth_name)
            ->where('status',1)
            ->column('menu_id');
    }

    $tree = [];
    if(!empty($res)){
        foreach($res as $val){
            $val['disabled'] = $val['status'] == 1 ? false : true;
            $val['expand'] = true;
            $val['_isChecked'] = in_array($val['id'],$menu_data) ? true : false;
            $child = make_tree($table_name, $val[$index_key], $fields,$post,$current_auth_name,$menu_data);
            if($child){
                $val['children'] = $child;
                unset($child);
            }
            $tree[] = $val;

        }
        return $tree;
    }
}


/**
 * 格式化目录树，下拉菜单目录树专用
 * @param $tree
 * @param $table_name
 * @param string $label
 * @param string $pid_field
 * @param int $pid
 * @param string $fields
 * @param int $level
 * @return array
 */
function format_tree(&$tree,$table_name, $label = 'title',$pid_field = 'pid', $pid = 0, $fields = '*',$level = 1){
    $index_key = \think\Db::name($table_name)->getPk();
    $res = \think\Db::name($table_name)
        ->field($fields)
        ->where($pid_field,$pid)
        ->select();

    if(!empty($res)){
        foreach($res as $key => $val){
            $val['level'] = $level;
            $prefix = str_repeat('┈',$level);
            $val['label'] = $prefix . $val[$label];

            $child = \think\Db::name($table_name)
                ->where($pid_field,$val[$index_key])
                ->count();
            $keys = array_keys($res);
            if($child || $key != end($keys)){
                $val['label'] = '├'.$val['label'];
            }else{
                $val['label'] = '└'.$val['label'];
            }

            $tree[] = $val;
            format_tree($tree,$table_name, $label, $pid_field, $val[$index_key], $fields,$level + 1);


        }
        return $tree;
    }


}

/**
 * 根据模型名获取对应表名
 * @param string $model_name 模型名(模型管理表中模型名称)
 * @return mixed
 */
function getModelTableName($model_name){
    return substr_replace($model_name,'',-6,6);
}

/**
 * 生成登录令牌
 * @param $username
 * @param $ip
 * @return bool|string
 */
function makeToken($username, $ip){
    return  strtolower(md5($username . $ip . date('Y-m-d') . 'vuecmf'));
}

/**
 * 生成角色或权限列表树
 * @param string $main_table  目录主数据表
 * @param string $relation_table  目录关系表名
 * @param int $type  类型 10=角色，20=权限
 * @param array $post  搜索表单
 * @param string $parent  父级目录名称
 * @return array|PDOStatement|string|\think\Collection
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\ModelNotFoundException
 * @throws \think\exception\DbException
 */
function make_auth_tree($main_table,$relation_table,$type,$post = [],$parent = '',$dir_name = [],$current_role_name = '',$auth_data = [],$current_user_id = false){
    if(empty($parent)){
        $query = \think\Db::name($main_table)
            ->where('type',$type);

        if(!empty($post)){
            foreach ($post as $key => $val)
                $query->where($key,$val[0],$val[1]);
        }

        $data = $query->select();

        foreach ($data as $item){
            array_push($dir_name,$item['name']);
        }
        unset($data);

        $child = \think\Db::name($relation_table)
            ->whereIn('child',$dir_name)
            ->whereIn('parent',$dir_name)
            ->column('child');

        $query2 = \think\Db::name($main_table)
            ->whereNotIn('name',$child)
            ->where('type',$type);

        if(!empty($post)){
            foreach ($post as $key => $val)
                $query2->where($key,$val[0],$val[1]);
        }

        $tree_data = $query2->select();

        //分配的权限
        if(!empty($current_role_name)){
            $auth_data = \think\Db::name($relation_table)
                ->whereIn('child',$dir_name)
                ->where('parent',$current_role_name)
                ->column('child');
        }else if(!empty($current_user_id)){
            $auth_data = \think\Db::name('auth_assignment')
                ->where('user_id',$current_user_id)
                ->column('item_name');
        }


        foreach ($tree_data as &$val){
            $menu_id_arr = \think\Db::name('auth_item_menu')->where('item_name',$val['name'])->where('status',1)->column('menu_id');
            $val['menu'] = empty($menu_id_arr) ? null : $menu_id_arr;
            $val['disabled'] = $val['status'] == 1 ? false : true;
            $val['expand'] = true;
            $val['_isChecked'] = in_array($val['name'],$auth_data) ? true : false;
            $child_tree = make_auth_tree($main_table,$relation_table,$type,$post,$val['name'],$dir_name,$current_role_name,$auth_data,$current_user_id);
            if(!empty($child_tree)){
                $val['children'] = $child_tree;
            }
        }

    }else{
        $child_data = \think\Db::name($relation_table)
            ->where('parent',$parent)
            ->whereIn('child',$dir_name)
            ->column('child');
        $tree_data = \think\Db::name($main_table)
            ->whereIn('name',$child_data)
            ->select();
        foreach ($tree_data as &$val){
            $child_tree = make_auth_tree($main_table,$relation_table,$type,$post,$val['name'],$dir_name,$current_role_name,$auth_data);
            $val['disabled'] = $val['status'] == 1 ? false : true;
            $val['expand'] = true;
            $val['_isChecked'] = in_array($val['name'],$auth_data) ? true : false;
            if(!empty($child_tree)){
                $val['children'] = $child_tree;
            }
        }

    }


    return $tree_data;
}
