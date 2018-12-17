<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/13
 * Time: 11:58
 */

namespace App\Http\Controllers\Admin;


use App\IpLocation;
use App\Models\Model;
use App\Util;
use Illuminate\Http\Request;
use Cookie;

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
        return view('admin.sys.func_list',$this->render($d));
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
        return view('admin/sys/func_op',$this->render($d));
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
        $d['roles'] = Model::Sys()->rolesAll();
        return view('admin.sys.role_list',$this->render($d));
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
        return view('admin.sys.role_op',$this->render($d));
    }
    public function rolePost(Request $request){
        $role_id = intval($request->get('role_id'));

        $txtName 			= $request->input('role_name');
        $txtEName 			= $request->input('role_ename');
        $txtFuncsID 		= str_replace(" ", "", $request->input('role_funcids'));
        $cboStatus 			= intval($request->input('status'));
        $txtFuncs 			= $request->input('role_funcnames');

        $data = array(
            'role_name'		=> $txtName,
            'role_ename'	=> $txtEName,
            'role_funcnames'=> $txtFuncs,
            'role_funcids'	=> $txtFuncsID,
            'status'		=> $cboStatus,
        );
        if ($role_id < 1) { //添加
            $result = Model::Sys()->insertRole($data);
            if ($result) $role_id = $result;
        } else { //修改
            $result = Model::Sys()->updateRole($role_id, $data);
        }
        if ($result && strlen($txtFuncsID) > 1) {
            Model::Sys()->deleteRoleFuncs($role_id);
            $funcs = explode(';', $txtFuncsID);
            foreach ($funcs as $func_name) {
                $funcid_list = explode('-', $func_name);
                if (count($funcid_list) != 5) continue;

                $func_id = intval($funcid_list[4]);
                if ($func_id < 1) continue;
                $data = array(
                    'role_id'	=> $role_id,
                    'func_id'	=> $func_id,
                    'func_op'	=> $func_name,
                );
                Model::Sys()->insertRoleFuncs($data);
            }
        }
        return redirect('admin/sys/role_list');
    }
    //管理员账号
    public function adminList(Request $request){
        $d = ['title'=>'管理员管理'];
        $d['users'] = Model::Sys()->getAdmins();
        return view('admin.sys.admin_list', $this->render($d));
    }
    public function adminOp(Request $request){
        $admin_id = intval($request->get('admin_id'));
        $d = ['title'=>'管理员管理'];
        $d['admin'] = Model::Sys()->admin($admin_id);
        $d['roles'] = Model::Sys()->roles();
        $d['admin_id'] = $admin_id;
        return view('admin.sys.admin_op', $this->render($d));
    }
    public function adminPost(Request $request){
        $admin_id = intval($request->get('admin_id'));
        $username = $request->get('username');
        $realname = $request->get('realname');
        $password = $request->get('password');
        $role_id = $request->get('role_id');
        $status = $request->get('status');
        $salt = Util::getSalt();
        $ip = IpLocation::getIP();
        if (!empty($password)){
            $password = Util::password($password, $salt);
        }
        $data = [
            'username' => $username,
            'realname' => $realname,
            'password' => $password,
            'role_id' => $role_id,
            'status' => $status,
            'salt' =>$salt,
            'reg_date' => TIMESTAMP,
            'reg_ip' => $ip
        ];
        if (empty($password)) unset($data['password']);
        if ($admin_id > 0){
            unset($data['salt']);
            unset($data['reg_date']);
            Model::Sys()->updateAdminUser($admin_id, $data);
        }
        else Model::Sys()->insertAdminUser($data);
        return redirect('admin/sys/admin_list');
    }
    public function login(){
        $d = ['title'=>'用户登录'];
        return view('admin.login', $d);
    }
    public function loginCheck(Request $request){
        $username = $request->get('username');
        $password = $request->get('password');
        if (empty($username) || empty($password)) dd('用户名或密码不能为空');
        $user = $this->_checkUser($username,$password);
        if(!$user) dd('用户名或密码不能为空');
        //Cookie::queue('admin_id', $user->admin_id, 86400*7/60);
        //return redirect('/admin');
        $GLOBALS['user_id'] = $user->admin_id;
        Cookie::queue('user_id', $user->admin_id, 86400*7/60);
        //$cookie = \Cookie('admin_id', $user->admin_id, 5);
        return redirect('/admin');
    }
    public function logout(){
        return redirect('/admin/login')->withCookie(Cookie::forget('user_id'));
    }
    public function _checkUser($username, $password){
        $user = Model::Sys()->getAdminUserByUsername($username);
        if (empty($user)) return false;
        //验证密码
        $_password = Util::password($password, $user->salt);
        if ($user->password === $_password){
            return $user;
        }else{
            return false;
        }
    }
}