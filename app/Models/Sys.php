<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/13
 * Time: 11:59
 */

namespace App\Models;


use Illuminate\Support\Facades\DB;

class Sys extends Model{
    public function getApps(){
        $sql = "select * from sys_app  order by app_order desc";
        return DB::select($sql);
    }
    public function insertApp($data) {
        $sql = 'insert into sys_app('.$this->fields($data).') values('.$this->values($data).')';
        return DB::insert($sql, $data);
    }
    public function updateApp($app_id, $data) {
        $sql = 'update sys_app set '.$this->set($data).' where app_id=:id';
        $data['id'] = $app_id;
        return DB::update($sql, $data);
    }
    public function deleteApp($app_id) {
        $sql = "delete from sys_app where app_id=?";
        return DB::delete($sql, [$app_id]);
    }
    public function functions() {
        $sql = "select func_id, a.app_id, func_name, func_ename, func_url, func_img, func_order, a.status as func_status,
				app_name, app_ename, app_img, app_order, b.status as app_status
				from sys_app_function as a inner join sys_app as b on a.app_id=b.app_id
				order by app_order asc, func_order asc";
        return DB::select($sql);
    }
    public function getfuncById($func_id){
        $sql = "select * from sys_app_function  where  func_id=?";
        return DB::selectOne($sql,[$func_id]);
    }
    public function napps() {
        $sql = "select app_id, app_ename, app_name, app_img, app_order, status
				from sys_app as a
				where not exists(select app_id from sys_app_function where app_id=a.app_id)
				order by app_order asc";
        return DB::select($sql);
    }
    public function apps2() {
        $sql = "select app_id, app_ename, app_name, app_img, app_order, status
				from sys_app as a
				where exists(select app_id from sys_app_function where app_id=a.app_id)
				order by app_order asc";
        return DB::select($sql);
    }
    public function insertFunc($data) {
        $sql = 'insert into sys_app_function('.$this->fields($data).') values('.$this->values($data).')';
        return DB::insert($sql, $data);
    }
    public function updateFunc($func_id, $data) {
        $sql = 'update sys_app_function set '.$this->set($data).' where func_id=:id';
        $data['id'] = $func_id;
        return DB::update($sql, $data);
    }
    public function role($role_id) {
        $sql = "select * from sys_role where role_id=?";
        return DB::selectOne($sql, [$role_id]);
    }
    public function funcs($app_id, $rid) {
        $sql = "select a.func_id, func_ename, func_name, func_img, func_order, status, role_id
				from sys_app_function as a left join sys_role_function as b on a.func_id=b.func_id and role_id=?
				where app_id=? order by func_order asc";
        return DB::select($sql, [$rid, $app_id]);
    }
    public function insertRole($data) {
        return DB::table('sys_role')->insertGetId($data);
    }
    public function updateRole($role_id, $data) {
        $sql = 'update sys_role set '.$this->set($data).' where role_id=:id';
        $data['id'] = $role_id;
        return DB::update($sql, $data);
    }
    public function deleteRoleFuncs($role_id) {
        $sql = "delete from sys_role_function where role_id=?";
        return DB::delete($sql, [$role_id]);
    }
    public function insertRoleFuncs($data) {
        $sql = 'insert into sys_role_function('.$this->fields($data).') values('.$this->values($data).')';
        return DB::insert($sql, $data);
    }
    public function rolesAll() {
        $sql = "select role_id, role_name, role_ename, status, role_funcnames, role_funcids from sys_role";
        return DB::select($sql);
    }
    public function admin($admin_id) {
        $sql = "select * from sys_admin_user where admin_id=?";
        return DB::selectOne($sql, [$admin_id]);
    }
    public function getAdmins() {
        $sql = "select a.*,b.role_funcnames from sys_admin_user a INNER JOIN sys_role b ON b.role_id = a.role_id  order by a.admin_id desc ";
        return DB::select($sql);
    }
    public function roles() {
        $sql = "select role_id, role_name, role_ename, role_funcnames, role_funcids from sys_role where status=1";
        return DB::select($sql);
    }
    public function insertAdminUser($data){
        $sql = 'insert into sys_admin_user('.$this->fields($data).') values('.$this->values($data).')';
        return DB::insert($sql, $data);
    }
    public function getAdminUserByUsername($username) {
        $sql = "select * from sys_admin_user where username=?";
        return DB::selectOne($sql, [$username]);
    }
    public function updateAdminUser($admin_id, $data){
        $sql = 'update sys_admin_user set '.$this->set($data).' where admin_id=:id';
        $data['id'] = $admin_id;
        return DB::update($sql, $data);
    }
}