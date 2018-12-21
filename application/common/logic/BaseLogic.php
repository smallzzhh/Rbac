<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2018/11/13
 * Time: 下午2:42
 */

namespace app\common\logic;

use app\common\traits\Api;
use think\Controller;
use think\Exception;

abstract class BaseLogic extends Controller
{
    use Api;
    public function logAdminAdd($uid,$content){
        
    }
    //简单验证手机号
    public function is_number($num){
        $num = is_numeric($num) ? $num : 1; //验证是否字符串
        $num = strlen($num) === 11 ? $num : '';
        if(empty($num)){
            return false;
        }
        if(substr($num,0,1) != 1){
            return false;
        }
        $char = substr($num,1,1);
        $strarr = array(3,4,5,6,7,8,9);

        if(in_array($char,$strarr)){
            return true;
        }
        return false;
    }

    /**
     * base64图片转PNG图片
     * @param $img
     * @return bool|string
     */
    protected function base64ToPng($img)
    {
        preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result);
        $imgs = base64_decode(str_replace($result[0], '', $img));
        $path = __DIR__ . './../../../public';
        $img_path = '/img/' . time() . '.png';
        try {
            $res = file_put_contents($path . $img_path, $imgs);
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
        if ($res) {
            //return 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
            return $img_path;
        }
        return false;
    }

    /**
     * 图片文件转base64
     * @param $file
     * @return string
     */
    protected function fileToBase64($file)
    {
        $base64_image = '';
        $image_info = getimagesize($file);
        $image_data = fread(fopen($file, 'r'), filesize($file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }


}