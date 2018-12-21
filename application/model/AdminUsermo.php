<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/10/11
 * Time: 14:03
 */

namespace app\model;


use app\common\model\BaseModel;
use think\Exception;
use think\Log;

class AdminUsermo extends BaseModel
{
    protected $table = 'admin_user';

    /**
     * @remark 创建后台账户
     * @author smallzz--bliblihome
     * @param $name
     * @param $nickname
     * @param $password
     * @param $phone
     * @param $wechat
     * @param $is_status
     * @return bool
     */
    public function careteAdminUser($name, $nickname, $password, $phone, $wechat, $is_status)
    {
        $adminusermo = new AdminUsermo();
        try {
            $adminusermo->admin_name = $name;
            $adminusermo->nickname = $nickname;
            $adminusermo->password = sha1(md5('pgy' . $password));
            $adminusermo->phone = $phone;
            $adminusermo->wechat = $wechat;
            $adminusermo->is_status = $is_status;
            $adminusermo->token = sha1(md5($name));
            if ($adminusermo->save()) {
                return true;
            }
            return false;
        } catch (Exception $exception) {
            Log::error(var_export($exception->getMessage()));
            return false;
        }
    }

    public function getEditAdminUser($id)
    {
        $adminusermo = new AdminUsermo();
        try {
            $adminusermo->where(['id' => $id])->field('id,name,nickname,phone,wechat,is_status')->find();
        } catch (Exception $exception) {
            return false;
        }
    }


    /**
     * @remark 编辑账户
     * @author smallzz--bliblihome
     * @param $id
     * @param $name
     * @param $nickname
     * @param $phone
     * @param $wechat
     * @param $is_status
     * @param $menu_index
     * @return bool
     */
    public function editAdminUser($id, $name, $nickname, $phone, $wechat, $is_status, $menu_index)
    {
        $adminusermo = new AdminUsermo();
        try {
            $data['admin_name'] = $name;
            $data['nickname'] = $nickname;
            $data['phone'] = $phone;
            $data['wechat'] = $wechat;
            $data['is_status'] = $is_status;
            $data['menu_index'] = $menu_index;
            if ($adminusermo->save($data, ['id' => $id]) !== false) {
                return true;
            }
            return false;
        } catch (Exception $exception) {
            Log::error(var_export($exception->getMessage()));
            return false;
        }
    }

    /**
     * @remark 修改密码
     * @author smallzz--bliblihome
     * @param $admin_id
     * @param $pwd
     * @return bool
     */
    public function upPwdAdminUser($admin_id, $pwd, $imgs)
    {
        $adminusermo = new AdminUsermo();
        try {
            if (!empty($imgs)) {
                $data['picture'] = $imgs;
            }
            if (!empty($pwd)) {

                $data['password'] = sha1(md5('pgy' . $pwd));
            }
            if (!empty($data)) {
                if ($adminusermo->save($data, ['id' => $admin_id]) !== false) {
                    return true;
                }
                return false;
            }
            return true;
        } catch (Exception $exception) {
            Log::error(var_export($exception->getMessage()));
            return false;
        }
    }

    /**
     * @remark 更新状态
     * @author smallzz--bliblihome
     * @param $admin_id
     * @return bool
     */
    public function statusAdminUser($admin_id)
    {
        //查询当前的状态
        $adminusermo = new AdminUsermo();
        try {
            $is_status = $adminusermo->where('id', $admin_id)->value('is_status');
            if ($is_status == 1) {
                $res = $adminusermo->save(['is_status' => 0, 'updated_at' => date('Y-m-d H:i:s')], ['id' => $admin_id]);
            } else {
                $res = $adminusermo->save(['is_status' => 1, 'updated_at' => date('Y-m-d H:i:s')], ['id' => $admin_id]);
            }
            if ($res !== false) {
                return true;
            }
            return false;
        } catch (Exception $exception) {
            Log::error(var_export($exception->getMessage()));
            return false;
        }
    }

    public function getSetDepar($id)
    {
        $adminusermo = new AdminUsermo();
        $res = $adminusermo->where(['id' => $id])->field('id,depar_id,qrcode_url')->find();
        if ($res) {
            return $res;
        }
        return false;
    }
    public function getUser($admin_id)
    {
        return $this->where(['id' => $admin_id])->value('nickname');
    }
}