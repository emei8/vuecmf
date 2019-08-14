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
        echo 'test';
    }

}
