<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
/**
 * 后端接口路由
 */
Route::group('admin', function () {
    Route::group('auth', [  #菜单
        'list' => ['admin/Auth/authList', ['method' => 'get']],
        'add' => ['admin/Auth/authAdd', ['method' => 'post']],
        'edit' => ['admin/Auth/authEdit', ['method' => 'post']],
        'delete' => ['admin/Auth/authDelete', ['method' => 'post']],
    ]);
    Route::group('admin', [  #后台账户
        'list' => ['admin/Admin/adminList', ['method' => 'get']],
        'add' => ['admin/Admin/adminAdd', ['method' => 'post']],
        'edit' => ['admin/Admin/adminEdit', ['method' => 'post']],
        'delete' => ['admin/Admin/adminDelete', ['method' => 'post']],
    ]);
    Route::group('role', [   #角色
        'list' => ['admin/Role/roleList', ['method' => 'get']],
        'add' => ['admin/Role/roleAdd', ['method' => 'post']],
        'edit' => ['admin/Role/roleEdit', ['method' => 'post']],
        'delete' => ['admin/Role/roleDelete', ['method' => 'post']],
    ]);
});



/**
 * 前端接口路由
 */
Route::group('index', function () {
    Route::group('auth', [
        'edit' => ['admin/Auth/authEdit', ['method' => 'post']],

    ]);
});
