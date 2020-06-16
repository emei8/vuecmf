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
        ->where('MU.pid',trim($pid,'m'))
        ->where('MU.status',1);

    //可根据菜单权限在where中过滤
    !empty($auth_menu) && $query->whereIn('MU.id',$auth_menu);

    $res = $query->order('sort_num')->select();

    $tree = [];
    if(!empty($res)){
        foreach($res as $val){
            $val['component'] == null && $val['component'] = '';
            $val['path'] == null && $val['path'] = '';

            if(trim($pid,'m') == 0 && trim($top_id,'m') == 0){
                $val['top_id'] = 'm' . $val['id'];
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


            $val[$index_key] = 'm'.$val[$index_key];
            $val['path'] = $val[$index_key];

            $tree[$val[$index_key]] = $val;
            $child = make_menu_tree($val[$index_key],$val['top_id'],$auth_menu,$auth_operate);
            if($child){
                $tree[$val[$index_key]]['children'] = $child;
                reset($child);
                $tree[$val[$index_key]]['active'] = strval(key($child));
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
            if(isset($val['thumb_icon']) && !empty($val['thumb_icon'])){
                $full_url = \think\facade\Request::domain() . '/'.config('upload_path').'/'. $val['thumb_icon'];
                $val['icon_file_info'][] = [
                    'status' => 'finished',
                    'full_url' => $full_url,
                    'url' => $val['icon'],
                    'small_url' => $val['thumb_icon'],
                    'name' => basename($val['thumb_icon'])
                ];
            }

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
            $prefix = str_repeat('┊ ',$level-1);

            $keys = array_keys($res);
            $child = \think\Db::name($table_name)
                ->where($pid_field,$val[$index_key])
                ->count();

            if($child || $key != end($keys)){
                $prefix .= '┊┈ ';
            }else{
                $prefix .= '└─ ';
            }

            $val['label'] = $prefix . $val[$label];

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


/**
 * 下载订单
 * @param $order_sn
 * @return bool
 */
function downloadOrder($order_sn, $order_type,$uid=null){
    if(empty($order_sn)) return false;
    $content = file_get_contents(\think\facade\Request::domain() . '/api/excel?order_sn='.$order_sn."&order_type=".$order_type.'&uid='.$uid);
    $out_filename = '订单('.$order_sn.').xls';
    $filesize = strlen($content);
    header('Accept-Ranges: bytes');
    header('Accept-Length: ' . $filesize);
    header('Content-Transfer-Encoding: binary');
    header('Content-type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $out_filename);
    header('Content-Type: application/octet-stream; name=' . $out_filename);
    echo $content;
}


/**
 * paypal支付
 * @param $order_sn  订单号
 * @param $return_url 支付成功跳转回网站地址
 */
/**
 * paypal支付
 * @param $order_sn  订单号
 * @param $return_url 支付成功跳转回网站地址
 * @param $order_type 订单类型， 1 = 正式订单， 2 = 预定订单
 */
function paypal($order_sn, $return_url, $order_type = 1){
    return model('api/PaypalService','service')->pay($order_sn, $return_url, $order_type);
}

/**
 * 邮件发送
 * @param $toemail 接收人
 * @param string $subject 邮件标题
 * @param string $content 邮件内容(html模板渲染后的内容)
 */
function send_email($email_host, $email_address, $email_password, $toemail, $subject='', $content=''){
    $email_host =  $email_host;//\think\facade\Config::get("email_host");
    $email_address = $email_address; //\think\facade\Config::get("email_address");
    $email_password = $email_password;//\think\facade\Config::get("email_password");
    $email_name = getTips("10077");//\think\facade\Config::get("email_name");

    $mail = new \PHPMailer\PHPMailer();
    $mail->isSMTP();// 使用SMTP服务
    $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
    $mail->Host = $email_host;// 发送方的SMTP服务器地址
    $mail->SMTPAuth = true;// 是否使用身份验证
    $mail->Username = $email_address;//发送方的qq邮箱用户名，就是你申请qq的SMTP服务使用的qq邮箱
    $mail->Password = $email_password;//发送方的邮箱密码，注意用qq邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码
    $mail->SMTPSecure = "";//使用ssl协议方式
    $mail->Port = 25;// qq邮箱的ssl协议方式端口号是465/587

    $mail->setFrom($email_address, $email_name);// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@qq.com），Mailer是当做名字显示
    $mail->addAddress($toemail, $toemail);// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@qq.com)
    $mail->addReplyTo($email_address,"Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
    $mail->isHTML(true);
    $mail->Subject = $subject;// 邮件标题
    $mail->Body = $content;// 邮件正文

    if(!$mail->send()){// 发送邮件
        throw new Exception("Mailer Error: ".$mail->ErrorInfo);
    }else{
        return true;
    }
}

//生成随机验证码
function getRandStr($len)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = "";
    for ($i=0; $i<$len; $i++)
    {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

function getIp(){
    if (isset($_SERVER["HTTP_CLIENT_IP"]) && $_SERVER["HTTP_CLIENT_IP"] && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown")) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } else {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && $_SERVER["HTTP_X_FORWARDED_FOR"] && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown")) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            if (isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown")) {
                $ip = $_SERVER["REMOTE_ADDR"];
            } else {
                if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $ip = "127.0.0.1";
                }
            }
        }
    }
    return ip2long($ip);
}

//统一成功返回值
function success_json($data = [], $code = 1, $msg = '操作成功'){
    echo json_encode(['code' => $code, 'msg'=>$msg, 'data'=>$data],JSON_UNESCAPED_UNICODE);
    exit;
}

//统一错误返回值
function error_json($msg = '操作失败', $code = 0, $data = []){
    echo  json_encode(['code' => $code, 'msg'=>$msg, 'data'=>$data],JSON_UNESCAPED_UNICODE);
    exit;
}

//生成订单号
function order_sn(){
    $osn = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $osn;
}

//百分比函数处理成小数
function discount($str){
    return intval(str_replace("%", "", $str))/100;
}

//页面展示价格用
function price($user_price, $percent = "100%"){
    return sprintf('%.2f', (round($user_price * discount($percent), 2)));
}
function navActive($a,$b,$c='active'){
    if(is_array($b)?in_array($a,$b):($a==$b)){
        return $c;
    }
}
function getFirstImg($img){
    if($img){
        $img=explode(',',$img);
        return '/uploads/'.$img[0];
    }else{
        return '';
    }
}

//获取图片地址
function getImgUrl($img){
    if($img){
        return '/uploads/'.$img;
    }else{
        return '';
    }
}

//根据产品数量获取购买价格,用于计算
function get_purchase_price($info, $num){
    //会员价
    if($num < $info["wholesale_one_start"]){
        return $info["user_price"];
    }
    //批发一价格
    if($num >= $info["wholesale_one_start"] && $num <= $info["wholesale_one_end"]){
        return $info["user_price"] * discount($info["wholesale_one_percent"]);
    }
    //批发二价格
    if($num >= $info["wholesale_two_start"] && $num <= $info["wholesale_two_end"]){
        return $info["user_price"] * discount($info["wholesale_two_percent"]);
    }
    //批发三价格
    if($num >= $info["wholesale_three_start"] && $num <= $info["wholesale_three_end"]){
        return $info["user_price"] * discount($info["wholesale_three_percent"]);
    }
    //批发四价格
    if($num >= $info["wholesale_four_start"] && $num <= $info["wholesale_four_end"]){
        return $info["user_price"] * discount($info["wholesale_four_percent"]);
    }

    //批发五价格
    if($num >= $info["wholesale_five_start"]){
        return $info["user_price"] * discount($info["wholesale_five_percent"]);
    }
}

//公共获取提示信息方法
function getTips($code, $e = ""){
    return lang($code);
//    $config = \think\facade\Config::get("lang.");
//    if(isset($config[$code])){
//        return $config[$code];
//    }else{
//        return $code;
//    }
}

/**
 * 根据当前分类ID获取分类路径
 * @param $cid
 * @param array $cid_path
 * @param string $table_name
 * @return array
 */
function getCidPath($cid, $cid_path = [],$table_name='categories'){
    $pid = \think\Db::name($table_name)->where('id',$cid)->value('pid');
    array_push($cid_path,$cid);
    if($pid != 0){
        return getCidPath($pid,$cid_path);
    }else{
        return $cid_path;
    }
}


/**
 * 根据父类ID获取父类下所有最小分类列表
 *
 * @param [type] $pid
 * @param array $sub_id_arr
 * @param string $table_name
 * @return void
 */
function getSubCid($pid,&$sub_id_arr = [],$table_name='categories'){
    $id_arr = \think\Db::name($table_name)->where('pid',$pid)->column('id');
    array_push($sub_id_arr, $pid);
    if(!empty($id_arr)){
        foreach ($id_arr as $val){
            getSubCid($val,$sub_id_arr);
        }
    }

    return $sub_id_arr;
}


/**
 * 缩略图
 *
 * @param [type] $src
 * @param [type] $des
 * @param [type] $width
 * @return void
 */
function thumbnail($src, $des, $width) {
    ob_start ();//开始截获输出流
    $imageinfos = getimagesize ( $src );
    $ext = strtolower ( pathinfo ( $src, 4 ) );
    if ($imageinfos [2] == 1) {
        $im = imagecreatefromgif ( $src );
    } elseif ($imageinfos [2] == 2) {
        $im = imagecreatefromjpeg ( $src );
    } elseif ($imageinfos [2] == 3) {
        $im = imagecreatefrompng ( $src );
    }
     
    if (isset ( $im )) {
        $height = $imageinfos [1] * $width / $imageinfos [0];
        $dst_img = ImageCreateTrueColor ( $width, $height );
         
        imagesavealpha ( $dst_img, true );
        $trans_colour = imagecolorallocatealpha ( $dst_img, 0, 0, 0, 127 );
        imagefill ( $dst_img, 0, 0, $trans_colour );
         
        imagecopyresampled ( $dst_img, $im, 0, 0, 0, 0, $width, $height, $imageinfos [0], $imageinfos [1] );
         
        header ( 'content-type:image/jpg' );
        imagejpeg ( $dst_img, null, 90 );//输出文件流，90--压缩质量，100表示最高质量。
         
        @imagedestroy ( $im );
        @imagedestroy ( $dst_img );
    } else {
        echo @file_get_contents ( $src );
    }
    $content = ob_get_contents ();//获取输出流
    ob_end_flush ();//输出流到网页,保证第一次请求也有图片数据放回
    @file_put_contents ( $des, $content );//保存文件
}

//过滤特殊符号
function strFilter($str){
    $str = str_replace('`', '', $str);
    $str = str_replace('·', '', $str);
    $str = str_replace('~', '', $str);
    $str = str_replace('!', '', $str);
    $str = str_replace('！', '', $str);
    $str = str_replace('@', '', $str);
    $str = str_replace('#', '', $str);
    $str = str_replace('$', '', $str);
    $str = str_replace('￥', '', $str);
    $str = str_replace('%', '', $str);
    $str = str_replace('^', '', $str);
    $str = str_replace('……', '', $str);
    $str = str_replace('&', '', $str);
    $str = str_replace('*', '', $str);
    $str = str_replace('(', '', $str);
    $str = str_replace(')', '', $str);
    $str = str_replace('（', '', $str);
    $str = str_replace('）', '', $str);
    $str = str_replace('-', '', $str);
    $str = str_replace('_', '', $str);
    $str = str_replace('——', '', $str);
    $str = str_replace('+', '', $str);
    $str = str_replace('=', '', $str);
    $str = str_replace('|', '', $str);
    $str = str_replace('\\', '', $str);
    $str = str_replace('[', '', $str);
    $str = str_replace(']', '', $str);
    $str = str_replace('【', '', $str);
    $str = str_replace('】', '', $str);
    $str = str_replace('{', '', $str);
    $str = str_replace('}', '', $str);
    $str = str_replace(';', '', $str);
    $str = str_replace('；', '', $str);
    $str = str_replace(':', '', $str);
    $str = str_replace('：', '', $str);
    $str = str_replace('\'', '', $str);
    $str = str_replace('"', '', $str);
    $str = str_replace('“', '', $str);
    $str = str_replace('”', '', $str);
    $str = str_replace(',', '', $str);
    $str = str_replace('，', '', $str);
    $str = str_replace('<', '', $str);
    $str = str_replace('>', '', $str);
    $str = str_replace('《', '', $str);
    $str = str_replace('》', '', $str);
    $str = str_replace('.', '', $str);
    $str = str_replace('。', '', $str);
    $str = str_replace('/', '', $str);
    $str = str_replace('、', '', $str);
    $str = str_replace('?', '', $str);
    $str = str_replace('？', '', $str);
    return trim($str);
}
