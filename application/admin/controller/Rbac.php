<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/10/9
 * Time: 10:20
 */

namespace app\admin\controller;

//登录检测暂时先不继承
use app\admin\logic\RbacLogic;
use app\common\controller\BaseController;
use think\App;

class Rbac extends BaseController
{
    private $RbacLogic = null;
    function __construct(App $app = null)
    {
        parent::__construct($app);

        $this->RbacLogic = new RbacLogic();
    }

    /**
     * @api {POST} /admin/Rbac/authAdd 权限添加
     * @apiDescription  新增权限操作
     * @apiGroup Rbac
     * @apiName authAdd
     * @apiVersion 1.0.0
     * @apiParam {string} pid 父级权限id（添加子级的时候传，默认传0）
     * @apiParam {string} operation 操作形式(CURD)
     * @apiParam {string} before_path 前端路由
     * @apiParam {string} after_path 后端路由
     * @apiParam {int} status 状态（0关闭,1启用）
     * @apiParam {int} sort 排序
     * @apiParam {int} mark 列表页权限标志（1，默认0）
     * @apiParam {string} color 按钮颜色
     *
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xxx.xxx.com/admin/Rbac/authAdd
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "添加成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function authAdd()
    {
        $param = $this->request->post();
        $result = $this->validate($param,'AuthValidate.add');
        if(true !== $result){
            return $this->ajaxError(777,[],$result);
        }
        return $this->RbacLogic->authAddLogic($param);
    }

    /**
     * @api {POST} /admin/Rbac/authEdit 权限编辑
     * @apiDescription  编辑权限操作
     * @apiGroup Rbac
     * @apiName authEdit
     * @apiVersion 1.0.0
     * @apiParam {string} id 权限id
     * @apiParam {string} operation 操作形式(CURD)
     * @apiParam {string} before_path 前端路由
     * @apiParam {string} after_path 后端路由
     * @apiParam {string} title 权限名称
     * @apiParam {int} status 状态（0关闭,1启用）
     * @apiParam {int} sort 排序
     * @apiParam {int} mark 列表页权限标志（1，默认0）
     * @apiParam {string} color 按钮颜色
     *
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xxx.xxx.com/admin/Rbac/authUp
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "编辑成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function authEdit()
    {
        $param = $this->request->post();
        $result = $this->validate($param,'AuthValidate.edit');
        if(true !== $result){
            return $this->ajaxError(777,[],$result);
        }
        $this->RbacLogic->authEdit($param);
    }

    /**
     * @api {POST} /admin/Rbac/authDel 权限删除
     * @apiDescription  删除权限
     * @apiGroup Rbac
     * @apiName authDel
     * @apiVersion 1.0.0
     * @apiParam {string} id 权限id
     *
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/authDel
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "删除成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function authDel()
    {
        $param = $this->request->post();
        $result = $this->validate($param,'AuthValidate.del');
        if(true !== $result){
            return $this->ajaxError(777,[],$result);
        }
        $this->RbacLogic->authEdit($param);
    }

    /**
     * @api {GET} /admin/Rbac/authList 权限列表获取
     * @apiDescription  获取权限列表
     * @apiGroup Rbac
     * @apiName authList
     * @apiVersion 1.0.0
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/authList
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "请求成功",
     *  "data": {
     *          "pid": "1",
     *          "function": "Home/index/index",
     *          "title": "我是标题"，
     *          "status": "1(1是启用,0是禁用)"，
     *          "sort": "100"，
     *          "created_at": "2017-10-11 09:40:34"
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function authList()
    {
        return $this->RbacLogic->authListLogic();
    }

    /**
     * @api {GET} /admin/Rbac/getAdminUserAuth 查询账户所拥有的权限
     * @apiDescription  查询账户所拥有的权限
     * @apiGroup Rbac
     * @apiName getAdminUserAuth
     * @apiVersion 1.0.0
     * @apiParam {string} admin_id 账户id
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/getAdminUserAuth
     * @apiSuccessExample {json} Response 200 Example
     * array(5) {
     * [0]=>
     * array(3) {
     * ["id"]=>
     * int(1)
     * ["pid"]=>
     * int(0)
     * ["title"]=>
     * string(12) "权限管理"
     * }
     * }
     */
    public function getAdminUserAuth()
    {
        $param = $this->request->param();
        return $this->RbacLogic->getAdminUserAuthLogic($param['admin_id']);
    }
    #

    /**
     * @api {GET} /admin/Rbac/getUseAuth 查询可编辑的权限
     * @apiDescription  获取可编辑的权限
     * @apiGroup Rbac
     * @apiName getUseAuth
     * @apiVersion 1.0.0
     * @apiParam {string} admin_id 账户id
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/getUseAuth
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "请求成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function getUseAuth()
    {
        $param = $this->request->param();
        return $this->RbacLogic->getEditAuthLogic($param['admin_id']);
    }

    /**
     * @api {POST} /admin/Rbac/roleAdd 角色添加
     * @apiDescription  添加角色信息
     * @apiGroup Rbac
     * @apiName roleAdd
     * @apiVersion 1.0.0
     * @apiParam {string} pid 父级角色id（用于添加子级的时候传的，默认传0）
     * @apiParam {string} role_name 角色名称
     * @apiParam {int} status 状态（0关闭,1启用）
     * @apiParam {int} sort 排序
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/roleAdd
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "添加成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */

    public function roleAdd()
    {
        $param = $this->request->param();
        return $this->RbacLogic->roleAddLogic($param);

    }

    /**
     * @api {POST} /admin/Rbac/getRoleEdit 角色编辑信息获取
     * @apiDescription  获取编辑角色信息
     * @apiGroup Rbac
     * @apiName getRoleEdit
     * @apiVersion 1.0.0
     * @apiParam {int} id 要编辑的角色id
     *
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/getRoleEdit
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "获取成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function getRoleEdit()
    {
        $param = $this->request->post();
        return $this->RbacLogic->getEditRole($param);

    }

    /**
     * @api {POST} /admin/Rbac/roleEdit 角色编辑
     * @apiDescription  编辑角色信息
     * @apiGroup Rbac
     * @apiName roleEdit
     * @apiVersion 1.0.0
     * @apiParam {int} id 要编辑的角色id
     * @apiParam {string} name 角色名称
     * @apiParam {int} status 状态（0关闭,1启用）
     * @apiParam {int} sort 排序
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/roleEdit
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "添加成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function roleEdit()
    {
        $param = $this->request->post();
        return $this->RbacLogic->roleEditLogic($param);
    }
    /**
     * @api {POST} /admin/Rbac/roleDel 角色删除
     * @apiDescription  删除角色
     * @apiGroup Rbac
     * @apiName roleDel
     * @apiVersion 1.0.0
     * @apiParam {int} id 要删除角色id
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/roleDel
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "提示",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function roleDel(){
        $param = $this->request->param();
        return $this->RbacLogic->roleDelLogic($param);
    }
    /**
     * @api {GET} /admin/Rbac/roleList 角色列表
     * @apiDescription  角色列表
     * @apiGroup Rbac
     * @apiName roleList
     * @apiVersion 1.0.0
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/roleList
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "请求成功",
     *  "data": {
     *          "pid": "1",
     *          "role_name": "催收员",，
     *          "status": "1(1是启用,0是禁用)"，
     *          "sort": "100"，
     *          "created_at": "2017-10-11 09:40:34"
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */

    public function roleList()
    {
        $param = $this->request->param();
        return $this->RbacLogic->roleListLogic($param);
    }

    /**
     * @api {POST} /admin/Rbac/roleDistAccount 角色分配账户
     * @apiDescription  账户分配角色
     * @apiGroup Rbac
     * @apiName roleDistAccount
     * @apiVersion 1.0.0
     * @apiParam {int} admin_id 后台账号id
     * @apiParam {string} role_id 角色id
     *
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/roleAllotment
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "分配成功",
     *  "data": {},
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function roleDistAccount()
    {
        $param = $this->request->post();
        return $this->RbacLogic->roleDistAccountLogic($param);
    }

    /**
     * @api {POST} /admin/Rbac/roleDistAuth 角色分配权限
     * @apiDescription  角色权限分配
     * @apiGroup Rbac
     * @apiName roleDistAuth
     * @apiVersion 1.0.0
     * @apiParam {int} role_id 角色id
     * @apiParam {string} auth_id 权限id（多个用逗号分割）
     *
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/roleAuthAllotment
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "分配成功",
     *  "data": {},
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function roleDistAuth()
    {
        $param = $this->request->post();
        return $this->RbacLogic->roleDistAuthLogic($param);
    }

    /**
     * @api {GET} /admin/Rbac/adminUserList 后台账户列表
     * @apiDescription  后台账户列表
     * @apiGroup Rbac
     * @apiName adminUserList
     * @apiVersion 1.0.0
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/adminUserList
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "请求成功",
     *  "data": {
     *          "id": "1",
     *          "admin_name": "zhouzz",，
     *          "nickname": "哈哈"，
     *          "phone": "13260324341   "，
     *          "wechat": "zzhh",
     *          "depar_id": "1"(部门id),
     *          "is_status": "1"（0停用，1启用）,
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function adminUserList()
    {
        return $this->RbacLogic->adminUserListLogic();
    }

    /**
     * @api {GET} /admin/Rbac/adminUserRoleList 后台账户角色显示
     * @apiDescription  后台账户角色显示
     * @apiGroup Rbac
     * @apiName adminUserRoleList
     * @apiVersion 1.0.0
     * @apiParam {int} admin_id 账户id
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/adminUserRoleList
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "请求成功",
     *  "data": [
     * {
     * "id": 1,
     * "role_name": "系统管理员"
     * },
     * {
     * "id": 2,
     * "role_name": "催收员"
     * }
     * ],
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function adminUserRoleList()
    {
        $param = $this->request->param();
        return $this->RbacLogic->getRoleListLogic($param['admin_id']);

    }

    /**
     * @api {POST} /admin/Rbac/adminUserAdd 用户添加
     * @apiDescription  用户添加
     * @apiGroup Rbac
     * @apiName adminUserAdd
     * @apiVersion 1.0.0
     * @apiParam {string} name 账号名
     * @apiParam {string} nickname 昵称
     * @apiParam {string} password 密码
     * @apiParam {string} phone 手机号
     * @apiParam {string} wechat 微信号
     * @apiParam {int} is_status 状态（0关闭,1启用）
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/adminUserAdd
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "添加成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */

    public function adminUserAdd()
    {
        $param = $this->request->param();
        return $this->RbacLogic->adminUserAddLogic($param);
    }

    /**
     * @api {POST} /admin/Rbac/getAdminUserInfo 用户信息获取
     * @apiDescription  获取用户信息
     * @apiGroup Rbac
     * @apiName getAdminUserInfo
     * @apiVersion 1.0.0
     * @apiParam {int} id 账号id
     *
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/getAdminUserUpdate
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "获取成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function getAdminUserInfo()
    {
        $param = $this->request->param();
        return $this->RbacLogic->getUserInfoLogic($param);

    }

    /**
     * @api {POST} /admin/Rbac/adminUserUpdate 用户修改
     * @apiDescription  用户修改
     * @apiGroup Rbac
     * @apiName adminUserUpdate
     * @apiVersion 1.0.0
     * @apiParam {int} id 账号id
     * @apiParam {string} name 账号名
     * @apiParam {string} nickname 昵称
     * @apiParam {string} phone 手机号
     * @apiParam {string} wechat 微信号
     * @apiParam {int} menu_index 权限id
     * @apiParam {int} is_status 状态（0关闭,1启用）
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/adminUserUpdate
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "添加成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function adminUserUpdate()
    {
        $param = $this->request->param();
        return $this->RbacLogic->adminUserEditLogic($param);
    }

    /**
     * @api {POST} /admin/Rbac/adminUserPwd 用户密码or头像修改
     * @apiDescription  修改用户密码or头像
     * @apiGroup Rbac
     * @apiName adminUserPwd
     * @apiVersion 1.0.0
     * @apiParam {int} admin_id 账号id
     * @apiParam {string} pwd1 密码1
     * @apiParam {string} pwd2 密码2
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/adminUserUppwd
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "操作成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function adminUserPwd()
    {
        //Todo:头像上传未完成
        $param = $this->request->post();
        return $this->RbacLogic->adminUserPwdLogic($param);

    }

    /**
     * @api {POST} /admin/Rbac/adminUserIndex 用户首页菜单设置
     * @apiDescription  用户首页菜单设置
     * @apiGroup Rbac
     * @apiName adminUserIndex
     * @apiVersion 1.0.0
     * @apiParam {int} admin_id 账号id
     * @apiParam {int} auth_id  菜单id
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/adminUserIndex
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "操作成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function adminUserIndex()
    {
        $param = $this->request->post();
        return $this->RbacLogic->adminUserIndexLogic($param);

    }

    /**
     * @api {POST} /admin/Rbac/adminUserStatus 用户启用禁用
     * @apiDescription  用户启用禁用
     * @apiGroup Rbac
     * @apiName adminUserStatus
     * @apiVersion 1.0.0
     * @apiParam {int} admin_id 账号id
     * @apiSuccess {int} status 调用状态 1-调用成功 0-调用失败
     * @apiSuccess {int} code   状态响应码 为0时表示无错误发生，大于0时表示发生了特定错误
     * @apiSuccess {string} message 提示消息
     * @apiSuccess {json} data 数据部分
     * @apiSampleRequest https://xx.xxx.com/admin/Rbac/adminUserStatus
     * @apiSuccessExample {json} Response 200 Example
     * {
     *  "status": 1,
     *  "message": "操作成功",
     *  "data": {
     *
     *   },
     *  "code": 0,
     * "jumpurl": ""
     * }
     */
    public function adminUserStatus()
    {
        $param = $this->request->post();
        return $this->RbacLogic->adminUserStatusLogic($param);
    }





}