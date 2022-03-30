<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\model;


/**
 * 菜单模型
 * Class Menu
 * @package app\vuecmf\model
 */
class Menu extends Base
{

    /**
     * 获取导航菜单列表
     * @param int $pid
     * @return array
     */
    public function nav(int $pid = 0, &$menuList = [], $id_path = [], $path_name = []):array
    {
        $res = self::where('pid', $pid)
            ->where('status', 10)
            ->order('sort_num')
            ->column('concat("m",id) mid, id, pid, title, icon, model_id');

        foreach ($res as &$val){
            $menuList[$val['mid']] = [];

            if(empty($id_path)){
                $val['id_path'] = [$val['mid']];
                $val['path_name'] = [$val['title']];
            }else{
                $val['id_path'] =  $id_path;
                $val['path_name'] = $path_name;
                array_push($val['id_path'], $val['mid']);
                array_push($val['path_name'], $val['title']);
            }

            $child = $this->nav($val['id'], $menuList[$val['mid']], $val['id_path'], $val['path_name']);

            if(!empty($child)){
                $val['children'] = $child;
            }else{
                $model_cfg = ModelConfig::alias('MC')
                    ->join('model_action MA', 'MC.default_action_id = MA.id','LEFT')
                    ->where('MC.id', $val['model_id'])
                    ->field('MC.table_name, MA.action_type default_action_type, MC.component_tpl, MC.search_field_id, MC.is_tree')
                    ->where('MC.status', 10)
                    ->where('MA.status', 10)
                    ->find();

                if(!empty($model_cfg)){
                    $model_cfg = $model_cfg->getData();
                    $val = array_merge($val, $model_cfg);
                }
            }

            $menuList[$val['mid']] = $val;

        }
        return $menuList;
    }


}
