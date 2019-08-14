<?php
/**
 * Created by www.vuecmf.com
 * User: emei8 <386196596@qq.com>
 * Date: 2019/3/3
 * Time: 22:51
 */

namespace app\api\model;


use think\Db;
use think\Exception;
use think\Model;

class Base extends Model
{
    protected $autoWriteTimestamp = 'update_time';

}