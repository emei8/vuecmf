<?php
namespace app\api\controller;

class Index
{
    public function index()
    {
        $menu = model('menu')->select();
        dump($menu);

        return '';
    }


    public function test(){
        $tree = [];
        $options = [];
        format_tree($tree,'categories');

        dump($tree);
    }

}
