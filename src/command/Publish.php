<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2019~2022 http://www.vuecmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/emei8/vuecmf/blob/master/LICENSE )
// +----------------------------------------------------------------------
// | Author: emei8 <2278667823@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\vuecmf\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 发布配置文件、迁移文件指令
 */
class Publish extends Command
{
    /**
     * 配置指令
     */
    protected function configure()
    {
        $this->setName('vuecmf:publish')->setDescription('Publish Vuecmf');
    }

    /**
     * 执行指令
     * @param Input  $input
     * @param Output $output
     * @return null|int
     * @throws LogicException
     * @see setCode()
     */
    protected function execute(Input $input, Output $output)
    {
        $destination = $this->app->getRootPath() . '/database/migrations/';
        if(!is_dir($destination)){
            mkdir($destination, 0755, true);
        }
        $source = __DIR__.'/../../database/migrations/';
        $handle = dir($source);
        
        while($entry=$handle->read()) {   
            if(($entry!=".")&&($entry!="..")){   
                if(is_file($source.$entry)){
                    copy($source.$entry, $destination.$entry);   
                }
            }
        }

        if (!file_exists(config_path().'tauthz-rbac-model.conf')) {
            copy(__DIR__.'/../../config/tauthz-rbac-model.conf', config_path().'tauthz-rbac-model.conf');
        }

        if (!file_exists(config_path().'tauthz.php')) {
            copy(__DIR__.'/../../config/tauthz.php', config_path().'tauthz.php');
        }

        $destination = app_path() . '/vuecmf/';
        if(!is_dir($destination)){
            mkdir($destination, 0755, true);
        }

        if (!file_exists($destination.'common.php')) {
            copy(__DIR__.'/../common.php', $destination.'common.php');
        }

        if (!file_exists($destination.'event.php')) {
            copy(__DIR__.'/../event.php', $destination.'event.php');
        }

        if (!file_exists($destination.'middleware.php')) {
            copy(__DIR__.'/../middleware.php', $destination.'middleware.php');
        }


        $controller_dir = $destination . 'controller/';
        if(!is_dir($controller_dir)){
            mkdir($controller_dir, 0755, true);
        }

        if (!file_exists($controller_dir.'Index.php')) {
            copy(__DIR__.'/../controller/Index.php', $controller_dir.'Index.php');
        }

    }
}

