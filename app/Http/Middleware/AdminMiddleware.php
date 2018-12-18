<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/17
 * Time: 16:27
 */

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class AdminMiddleware{
    public function handle(Request $request, \Closure $next){
        //判断是否登录
        $GLOBALS['user_id'] = \Cookie::get('user_id');
        if (!isset($GLOBALS['user_id']) || empty(@$GLOBALS['user_id'])){
            return redirect('admin/login');
        }
        return $next($request);
    }
}
