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
//一旦定义了路由 原来的地址就不能访问了 否则就会出现两个不同的URL同样的内容

use think\Route;
// 注册路由到index模块的News控制器的read操作
Route::rule('new/:id','index/TaskInfo/Task');