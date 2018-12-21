<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/10/9
 * Time: 14:48
 */

namespace app\admin\logic;

use app\model\AdminAuthmo;
use app\model\AdminRoleAccessmo;
use app\model\AdminRolemo;
use app\model\AdminUsermo;
use app\common\logic\BaseLogic;
use think\App;
use think\Db;
use think\Exception;


class RbacLogic extends BaseLogic
{
    protected $adminAuthmo = null;
    protected $adminRolemo = null;
    protected $adminRoleAccessmo = null;
    protected $adminUsermo = null;

    function __construct(App $app = null)
    {
        parent::__construct($app);

        $this->adminAuthmo = new AdminAuthmo();
        $this->adminUsermo = new AdminUsermo();
        $this->adminRolemo = new AdminRolemo();
        $this->adminRoleAccessmo = new AdminRoleAccessmo();

    }

    /**
     * 获取权限列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function authListLogic(){
        try{
            $list = $this->adminAuthmo->where(['pid'=>0])->select();
            foreach ($list as $k=>&$v){
                $list_ = $this->adminAuthmo->where(['pid'=>$v['id']])->select();
                if(empty($list_)){
                    $v['children'] = [];
                }else{
                    $v['children'] = $list_;
                    foreach ($list_ as $ke=>&$vo){
                        $lists = $this->adminAuthmo->where(['pid'=>$vo['id']])->select();
                        if(empty($lists)){
                            $vo['children'] = [];
                        }else{
                            $vo['children'] = $lists;
                        }
                    }
                }
            }
        }catch (Exception $exception){
            return $this->ajaxError(30101);
        }
        return $this->ajaxSuccess(30100,['data'=>$list]);
    }

    /**
     * 添加权限
     * @param array $param
     * @return array
     */
    public function authAddLogic(array $param){
        try{
            $this->adminAuthmo->save($param);
        }catch (Exception $exception){
            return $this->ajaxError(30103);
        }
        return $this->ajaxSuccess(30102);
    }

    /**
     * 编辑权限
     * @param array $param
     * @return array
     */
    public function authEdit(array $param){
        try{
            $id = intval($param['id']);
            unset($param['id']);
            $this->adminAuthmo->save($param,['id'=>$id]);
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }

    /**
     * 删除权限
     * @param array $param
     * @return array
     * @throws \Exception
     */
    public function authDel(array $param){
        try{
            $this->adminAuthmo
                ->where(['id'=>intval($param['id'])])
                ->delete();
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }

    /**
     * 获取所有权限列表
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _authList(){
        try{
            $list = $this->adminAuthmo->authListAll();
            $list = $this->generateTree($list);
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>$list]);
    }

    /**
     * 查询账户所拥有的权限
     * @param $admin_id
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAdminUserAuthLogic($admin_id){
        try{
            if (empty($admin_id)) return $this->ajaxError(777,[],'管理员id不能为空');
            //查询角色 查询他的权限
            if ($admin_id == 1) { //超级管理员
                $roleList = $this->adminAuthmo->field('id,pid,title')->select();
                if ($roleList) {
                    return $roleList;
                }
                return [];
            }
            $role = $this->adminRoleAccessmo->where(['admin_id'=>$admin_id])->field('role_id')->select();
            $str = '';
            $i = 0;
            foreach ($role as $k => $v) {
                //查询role表
                $role = $this->adminRolemo->where(['id' => $v['role_id'], 'status' => 1])->field('auth')->find();
                if ($i < 1) {
                    $str .= $role['auth'];
                } else {
                    $str .= ',' . $role['auth'];
                }
                $i += 1;
            }
            $newstr = explode(',', $str);
            $arr = implode(',', array_unique($newstr));

            $rolelist = $this->adminAuthmo->where('id in (' . $arr . ')')->select();
            if (!$rolelist) {
                $rolelist = [];
            }
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>$rolelist]);
    }

    /**
     * 获取可编辑的权限
     * @param $admin_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getEditAuthLogic($admin_id){
        try{
            if (empty($admin_id)) return $this->ajaxError(777,[],'管理员id不能为空');
            #查询所属角色
            if ($admin_id == 1) { //草鸡管理员
                $list = $this->adminAuthmo->where(' pid > 0 and operation = ""')
                    ->field('id,title')
                    ->select();
            } else {
                $res = $this->adminRoleAccessmo
                    ->where(['admin_id' => $admin_id])
                    ->find();
                $admin = $this->adminRolemo
                    ->where(['id' => $res['role_id']])
                    ->find();
                $arr = explode(',', $admin['auth']);
                foreach ($arr as $k => $v) {
                    $lanmu = $this->adminAuthmo
                        ->where('id = ' . $v . ' and pid > 0 and operation = ""')
                        ->find();
                    if ($lanmu) {
                        $list[$k]['id'] = $lanmu['id'];
                        $list[$k]['title'] = $lanmu['title'];
                    }
                }
            }
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>array_values($list)]);
    }

    //角色=====================================================================================

    /**
     * 添加角色
     * @param array $param
     * @return array
     */
    public function roleAddLogic(array $param){
        try{
            if (empty($param['role_name'])) return $this->ajaxError(777,[],'角色名称不能为空');
            $this->adminRolemo->allowField(true)->save($param);
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);

    }

    /**
     * 获取角色信息
     * @param array $param
     * @return array
     */
    public function getEditRole(array $param){
        try{
            $list = $this->adminRolemo->where(['id'=>$param['id']])->find();
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>$list]);
    }

    /**
     * 编辑角色
     * @param array $param
     * @return array
     */
    public function roleEditLogic(array $param){
        try{
            if (empty($param['role_name'])) return $this->ajaxError(777,[],'角色名称不能为空');
            $id = intval($param['id']);
            $this->adminRolemo->allowField(true)->save($param,['id',$id]);
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }

    /**
     * 删除角色
     * @param array $param
     * @return array
     * @throws \Exception
     */
    public function roleDelLogic(array $param){
        try{
            $this->adminRolemo->where(['id'=>intval($param['id'])])->delete();
            $this->adminRoleAccessmo->where(['role_id'=>intval($param['id'])])->delete();
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }
    /**
     * 获取角色列表
     * @param array $param
     * @return array
     */
    public function roleListLogic(array $param){
        try{
            $list = $this->adminRolemo
                ->order('id', 'desc')
                ->select();
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>$list]);
    }

    /**
     * 角色分配权限
     * @param array $param
     * @return array
     */
    public function roleDistAuthLogic(array $param){
        $role_id = intval($param['role_id']);
        try {
            $data['auth'] = $param['auth_id'];
            $this->adminRolemo->save($data, ['id' => $role_id]);

        } catch (Exception $exception) {
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }
    /**
     * 账户分配角色
     * @param array $param
     * @return array
     * @throws \Exception
     */
    public function roleDistAccountLogic(array $param){
        $role_id = intval($param['role_id']);
        $admin_id = intval($param['admin_id']);
        //删除所有
        try {
            $res = $this->adminRoleAccessmo->where(['admin_id' => $admin_id])->delete();
            $role_id = explode(',', $role_id);
            if ($res !== false) {
                $data = [];
                foreach ($role_id as $k => $v) {
                    $data[] = ['admin_id' => $admin_id, 'role_id' => $v];
                }
                $this->adminRoleAccessmo->insertAll($data);
            }
        } catch (Exception $exception) {
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }

    //admin_user======================================================================================

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function adminUserListLogic(){
        try{
            $list = $this->adminUsermo->where([])
                ->field('id,admin_name,nickname,phone,menu_index,is_status,recent_loginip,recent_logintime')
                ->order('id asc')
                ->select();
            //查询账户列表的权限
            foreach ($list as $k => $v) {
                $list[$k]['role'] = '';
                $lists = $this->adminRoleAccessmo->where(['admin_id' => $v['id']])->field('role_id')->select();
                $list[$k]['menu_index'] = $this->adminAuthmo->where(['id' => $v['menu_index']])->value('title');
                $i = 1;
                foreach ($lists as $k1 => $v1) {
                    if ($i > 1) {
                        $list[$k]['role'] .= ',' . $v1['role_id'];
                    } else {
                        $list[$k]['role'] = $v1['role_id'];
                    }
                    $i += 1;
                }
            }
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>$list]);
    }

    /**
     * 后台账户角色显示
     * @param $admin_id
     * @return array
     */
    public function getRoleListLogic($admin_id)
    {
        try {
            $list = $this->adminRoleAccessmo->alias('ra')
                ->join('admin_role r','ra.role_id = r.id','inner')
                ->where(['ra.admin_id' => $admin_id])
                ->field('r.id,r.role_name')
                ->select();
        } catch (Exception $exception) {
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>$list]);
    }

    /**
     * 后台用户添加
     * @param array $param
     * @return array
     */
    public function adminUserAddLogic(array $param){
        try{
            $param['password'] = sha1(md5('smallzz' . $param['password']));
            $this->adminUsermo->allowField(true)->save($param);
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }

    /**
     * 后台用户编辑
     * @param array $param
     * @return array
     */
    public function adminUserEditLogic(array $param){
        try{
            $id = intval($param['id']);
            if(empty($id)){
                return $this->ajaxError(777,[],'参数错误');
            }
            unset($param['id']);
            $this->adminUsermo->allowField(true)->save($param,['id'=>$id]);
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }
    /**
     * 获取用户信息
     * @param array $param
     * @return array
     */
    public function getUserInfoLogic(array $param){
        try{
            if (empty($param['id'])) return $this->ajaxError(777,[],'参数错误');
            $info = $this->adminUsermo
                ->where(['id' => intval($param['id'])])
                ->field('id,name,nickname,phone,wechat,is_status')
                ->find();
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110,['data'=>$info]);
    }

    /**
     * 修改用户密码or头像
     * @param array $param
     * @return array
     */
    public function adminUserPwdLogic(array $param){
        try{
            if (empty($admin_id)) return $this->ajaxError(777,[],'参数错误');
            if (empty($pwd1) or empty($pwd2)) return $this->ajaxError(777,[],'密码错误');
            if (!empty($imgs)) {
                $imgs = $this->base64ToPng($imgs);
                if(!$imgs){
                    return $this->ajaxError(777,[],'头像上传失败');
                }
                $data['picture'] = $imgs;
            }
            if (!empty($pwd)) {
                $data['password'] = sha1(md5('smallzz' . $pwd));
            }
            if (!empty($data)) {
                $this->adminUsermo->save($data, ['id' => $param['admin_id']]);
            }
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }

    /**
     * 用户首页菜单设置
     * @param array $param
     * @return array
     */
    public function adminUserIndexLogic(array $param){
        $admin_id = intval($param['admin_id']);
        $auth_id = intval($param['auth_id']);
        try {
            $this->adminUsermo->where(['admin_id' => $admin_id])->update(['menuindex' => $auth_id]);
        } catch (Exception $exception) {
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }

    /**
     * 用户启用禁用
     * @param array $param
     * @return array
     */
    public function adminUserStatusLogic(array $param){
        $admin_id = intval($param['admin_id']);
        try{
            if (empty($admin_id)) return $this->ajaxError(777,[],'参数错误');
            $is_status = $this->adminUsermo->where('id', $admin_id)->value('is_status');
            if ($is_status == 1) {
                $this->adminUsermo->save(['is_status' => 0, 'updated_at' => date('Y-m-d H:i:s')], ['id' => $admin_id]);
            } else {
                $this->adminUsermo->save(['is_status' => 1, 'updated_at' => date('Y-m-d H:i:s')], ['id' => $admin_id]);
            }
        }catch (Exception $exception){
            return $this->ajaxError(30111);
        }
        return $this->ajaxSuccess(30110);
    }























    /**
     * 递归树
     * @param $data
     * @return array
     */
    private function generateTree($data){
        $items = [];
        foreach ($data as $k=>$v){
            $items[$v['id']]['id'] = $v['id'];
            $items[$v['id']]['pid'] = $v['pid'];
            $items[$v['id']]['title'] = $v['title'];
            $items[$v['id']]['before_path'] = $v['before_path'];
            $items[$v['id']]['after_path'] = $v['after_path'];
            $items[$v['id']]['icon'] = $v['icon'];
            $items[$v['id']]['operation'] = $v['operation'];
            $items[$v['id']]['status'] = $v['status'];
        }
        $tree = array();
        foreach($items as $k=> $item){
            if(isset($items[$item['pid']])){
                $items[$item['id']]['children'] = [];
                $items[$item['pid']]['children'][] = &$items[$item['id']];
            }else{
                $tree[$k] = &$items[$item['id']];
                $tree[$k]['children'] = [];
            }
        }
        return $tree;
    }



}