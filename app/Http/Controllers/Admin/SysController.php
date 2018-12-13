<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/13
 * Time: 11:58
 */

namespace App\Http\Controllers\Admin;


use App\Models\Model;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Mod;

class SysController extends AdminController{
    //功能
    public function funcList(){
        $d = ['title'=>'功能列表'];
        $list = Model::Sys()->functions();
        $funcs = array();
        foreach ($list as $func) {
            $funcs[$func->app_id]['app_id'] 		= $func->app_id;
            $funcs[$func->app_id]['app_name'] 		= $func->app_name;
            $funcs[$func->app_id]['app_ename'] 		= $func->app_ename;
            $funcs[$func->app_id]['app_img'] 		= $func->app_img;
            $funcs[$func->app_id]['app_order'] 		= $func->app_order;
            $funcs[$func->app_id]['app_status'] 	= $func->app_status;

            $funcs[$func->app_id]['children'][$func->func_id]['func_id'] 		= $func->func_id;
            $funcs[$func->app_id]['children'][$func->func_id]['func_name'] 		= $func->func_name;
            $funcs[$func->app_id]['children'][$func->func_id]['func_ename'] 	= $func->func_ename;
            $funcs[$func->app_id]['children'][$func->func_id]['func_url'] 		= $func->func_url;
            $funcs[$func->app_id]['children'][$func->func_id]['func_img'] 		= $func->func_img;
            $funcs[$func->app_id]['children'][$func->func_id]['func_order'] 	= $func->func_order;
            $funcs[$func->app_id]['children'][$func->func_id]['func_status'] 	= $func->func_status;
        }
        $napps = Model::Sys()->napps();
        $d['napps'] = $napps;
        $d['funcs'] = $funcs;
        return view('admin.sys.func_list',$d);
    }
    public function funcOp(Request $request){
        $app_id = intval($request->get('app_id'));
        $func_id = intval($request->get('func_id'));
        $d = ['title' => '功能列表'];
        $d['func'] = Model::Sys()->getfuncById($func_id);
        $app = Model::Sys()->getApps();
        $d['apps'] = $app;
        $d['app_id'] = $app_id;
        $d['func_id'] = $func_id;
        return view('admin/sys/func_op',$d);
    }
    public function funcPost(Request $request){
        $func_id = intval($request->get('func_id'));
        $app_id = intval($request->get('app_id'));
        $func_name = $request->get('func_name');
        $func_url = $request->get('func_url');
        $func_ename = $request->get('func_ename');
        $func_img = $request->get('func_img');
        $func_order = $request->get('func_order');
        $status = $request->get('status');
        $data = [
            'app_id' => $app_id,
            'func_name' => $func_name,
            'func_ename' => $func_ename,
            'func_url' => $func_url,
            'func_img' => $func_img,
            'func_order' => $func_order,
            'status' => $status,
        ];
        if ($func_id < 1){
            Model::Sys()->insertFunc($data);
        }else{
            Model::Sys()->updateFunc($func_id, $data);
        }
        return redirect('admin/sys/func_list');
    }
    //sys app
    public function appOp(){
        $d = ['title'=>'功能新增/修改'];
        return view('admin.sys.app_op',$d);
    }
    public function appPost(Request $request){
        $app_id = intval($request->get('app_id'));
        $txtName 		= $request->input('app_name');
        $txtEName 		= $request->input('app_ename');
        $txtImg 		= $request->input('app_img');
        $txtOrder 		= intval($request->input('app_order'));
        $cboStatus 		= intval($request->input('status'));

        $data = array(
            'app_name'	=> $txtName,
            'app_ename'	=> $txtEName,
            'app_img'	=> $txtImg,
            'app_order'	=> $txtOrder,
            'status'	=> $cboStatus,
        );
        if ($app_id<1) { //添加
            $result = Model::Sys()->insertApp($data);
        } else { //修改
            $this->checkFunction('FunctionManage', "edit");
            $result = Model::Sys()->updateApp($app_id, $data);
        }
        return redirect('admin/sys/func_list');
    }
    //角色管理
    public function roleList(Request $request){
        $d = ['title'=>'角色管理'];
        return view('admin.sys.role_list',$d);
    }
    public function roleOp(Request $request){
        $d = ['title'=>'角色管理'];
        $role_id = intval($request->get('role_id'));
        $d['role_id'] 	= $role_id;
        $d['role'] 		= Model::Sys()->role($role_id);
        $funcs = array();
        $apps = Model::Sys()->apps2();
        foreach ($apps as $app) $funcs[$app->app_id] = Model::Sys()->funcs($app->app_id, $role_id);
        $d['funcs'] = $funcs;
        $d['apps'] = $apps;
        return view('admin.sys.role_op',$d);
    }
    public function rolePost(Request $request){
        dd($request);
    }
}