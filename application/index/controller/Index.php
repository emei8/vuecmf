<?php
namespace app\index\controller;

use think\Db;

class Index
{
    public function index()
    {

        return '';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }


    public function ts(){

        $data = $this->tree();

        dump($data);

    }

    protected function tree($parent = ''){



        if(empty($parent)){
            $data = Db::name('auth_item')
                ->where('type',10)
                ->select();

            $dir_name = [];

            foreach ($data as &$item){
                $item['disabled'] = $item['status'] == 1 ? false : true;
                $item['expand'] = true;
                array_push($dir_name,$item['name']);
            }

            $child = Db::name('auth_item_child')
                ->whereIn('child',$dir_name)
                ->column('child');

            $tree_data = Db::name('auth_item')
                ->whereNotIn('name',$child)
                ->where('type',10)
                ->select();

            foreach ($tree_data as &$val){
                $child_tree = $this->tree($val['name']);
                if(!empty($child_tree)){
                    $val['children'] = $child_tree;
                }
            }

        }else{
            $child_data = Db::name('auth_item_child')
                ->where('parent',$parent)
                ->column('child');
            $tree_data = Db::name('auth_item')
                ->whereIn('name',$child_data)
                ->select();
        }


        return $tree_data;


    }

}
