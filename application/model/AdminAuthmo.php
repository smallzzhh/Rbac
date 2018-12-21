<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/10/11
 * Time: 10:26
 */

namespace app\model;

use app\common\model\BaseModel;
use think\Db;
use think\db\Query;
use think\Exception;

class AdminAuthmo extends BaseModel
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'admin_auth';

    /**
     * 获取所有权限列表
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function authListAll(){
        $list = $this->order('sort desc')->select();
        return $list;
    }

    /** 权限添加
     * @param array $data
     * @return bool
     */
    public function add(array $data){
        return $this->save($data);
    }

    /**
     * @param $admin_id
     * @param $auth_id
     * @param $mark
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function getListAuth($admin_id, $auth_id, $mark)
    {
        //查角色就可以了
        $authmo = new AdminAuthmo();
        if ($admin_id == 1) { //草鸡管理员
            $list = $authmo->where(['pid' => $auth_id, 'mark' => $mark])->field('id,href as class,title as name,icon,color,sort')->order('sort asc')->select();
            return $list;
        }
        $role = Db::query('select role_id from pgy_role_access WHERE admin_id = ' . $admin_id);
        $str = '';
        $i = 0;
        foreach ($role as $k => $v) {
            //查询role表
            $rolemo = new AdminRolemo();
            $role = $rolemo->where(['id' => $v['role_id'], 'status' => 1])->field('auth')->find();
            if ($i < 1) {
                $str .= $role['auth'];
            } else {
                $str .= ',' . $role['auth'];
            }
            $i += 1;
        }
        $newstr = explode(',', $str);
        //$arr = implode(',',array_unique($newstr));
        //查询
        $lista = $authmo->where(['pid' => $auth_id, 'mark' => $mark])->field('id,href as class,title as name,icon,color,sort')->order('sort asc')->select();
        $list = [];
        $i = 0;
        foreach ($lista as $k1 => $v1) {
            if (in_array($v1['id'], $newstr)) {
                $list[$i]['id'] = $v1['id'];
                $list[$i]['class'] = $v1['class'];
                $list[$i]['name'] = $v1['name'];
                $list[$i]['color'] = $v1['color'];
                $i++;
            }
        }

        return $list;
    }


    /**
     * @param $admin_id
     * @param $auth_id
     * @return bool
     */
    public function setAdminUserIndex($admin_id, $auth_id)
    {
        $authmo = new AdminAuthmo();
        try {
            $res = $authmo->where(['admin_id' => $admin_id])->update(['menuindex' => $auth_id]);
            if ($res !== false) {
                return true;
            }
            return false;
        } catch (Exception $exception) {
            return false;
        }
    }



    /**
     * @return mixed
     */
    public function deparList()
    {
        $list = Db::query('select id,pid,depar_name as title,data_scope,sort from pgy_department WHERE pid = 0 order by sort ASC ');
        foreach ($list as $k => $v) {
            $list2 = Db::query('select id,pid,depar_name as title,data_scope,sort from pgy_department WHERE pid = ' . $v['id'] . ' order by sort ASC ');
            $list[$k]['auth'] = $list2;
            if (!empty($list2)) {
                foreach ($list2 as $k2 => $v2) {
                    $list[$k]['auth'][$k2]['auth'] = [];
                }
            }
        }
        return $list;
    }

}