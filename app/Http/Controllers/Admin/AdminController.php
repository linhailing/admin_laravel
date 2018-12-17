<?php
/*
 * admin 基类
 * @Author: Henry 
 * @Date: 2018-12-06 16:10:10 
 * @Last Modified by: Henry
 * @Last Modified time: 2018-12-06 16:20:41
 */
namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use Cookie;
use Illuminate\Http\Request;

class AdminController extends Controller{
    public function __construct(Request $request){
        parent::__construct();
        $GLOBALS['user_id'] = intval(Cookie::get('user_id'));
        $this->middleware('admin.auth')->except(['login','loginCheck']);
    }
    public function render($d=[]){
        $d['left_menu'] = [];
        return $d;
    }
    public function _ret(){
        exit(redirect('/admin/login'));
    }
}