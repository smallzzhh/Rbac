<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/10/11
 * Time: 13:03
 */

namespace app\model;


use app\common\model\BaseModel;
use think\Db;
use think\Exception;
use think\Log;
use think\Model;

class AdminRolemo extends BaseModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'admin_role';



    /**
     * @remark 角色分配
     * @author smallzz--bliblihome
     * @param $admin_id
     * @param $role_id
     * @return bool
     */
    public function allotmentRole($admin_id, $role_id)
    {
        $Rolemo = new AdminRoleAccessmo();

        //删除所有
        try {
            $res = $Rolemo->where(['admin_id' => $admin_id])->delete();
            $role_id = explode(',', $role_id);
            if ($res !== false) {
                foreach ($role_id as $k => $v) {
                    Db::name('role_access')->insert(['admin_id' => $admin_id, 'role_id' => $v]);
                }
                return true;
            }

        } catch (Exception $exception) {
            Log::error(var_export($exception->getMessage()));
            return false;
        }
    }

    /**
     * @param $role_id
     * @param $auth_id
     * @return bool
     */
    public function authAllotmentRole($role_id, $auth_id)
    {
        $Rolemo = new AdminRolemo();
        try {
            $data['auth'] = $auth_id;
            $res = $Rolemo->save($data, ['id' => $role_id]);
            if ($res !== false) {
                return true;
            }
            return false;
        } catch (Exception $exception) {
            Log::error(var_export($exception->getMessage()));
            return false;
        }
    }

    /**
     * @Author liyongchuan
     * @DateTime 2017-12-15
     *
     * @description 获取查询角色的所有ID;
     * @param string $keyWord
     * @return array
     */
    public function roleForKeyWord(string $keyWord)
    {
        return $this->where('role_name', 'like', "%$keyWord%")->column('id');
    }

    /**
     * auth smallzz----bilibilihome
     * @param $key
     * @return mixed
     */
    public function getRoleKId($key)
    {
        return $this->where('role_name LIKE "%' . $key . '%" and status = 1')->value('id');
    }
    public function roleDel($id){
        try{
            $this->where(['id'=>$id])->delete();
        }catch (Exception $exception){
            return false;
        }
        return true;
    }
}