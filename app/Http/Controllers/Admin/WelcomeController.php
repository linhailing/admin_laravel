<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/6
 * Time: 16:25
 */

namespace App\Http\Controllers\Admin;


class WelcomeController extends AdminController {
    public  function index(){
        $d = ['title'=>'首页'];
        return view('admin/welcome/index',$d);
    }
}