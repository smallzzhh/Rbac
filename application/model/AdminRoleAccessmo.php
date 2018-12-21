<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/10/12
 * Time: 9:37
 */

namespace app\model;


use app\common\model\BaseModel;
use think\Exception;

class AdminRoleAccessmo extends BaseModel
{
    protected $table = 'role_access';

    /**
     * @param int $admin_id
     * @return bool|false|\PDOStatement|string|\think\Collection
     */
    public function getRoleList(int $admin_id)
    {
        $roleaccess = new AdminRoleAccessmo();
        try {
            $list = $roleaccess->where(['admin_id' => $admin_id])->select();
            return $list;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @Author liyongchuan
     * @DateTime 2017-12-15
     *
     * @description 获取admin_id
     * @param array $roleId
     * @return array
     */
    public function getAdminIdForRoleId(array $roleId)
    {
        $where['role_id'] = ['in', $roleId];
        return AdminRoleAccessmo::where($where)->field('admin_id')->select();
    }

    /**
     * auth smallzz----bilibilihome
     * @param $rid
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getRoledminId($rid)
    {
        return $this->where(['role_id' => $rid])->field('admin_id')->select();
    }
}