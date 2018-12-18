<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 15:33
 */

namespace App\Models;

use DB;

class Api extends Model{
    public function getClassic(){
        $sql = ' select * from classic LIMIT 1 ';
        return DB::selectOne($sql);
    }
    public function getClassicLatest(){
        $sql = 'select * from classic order by `index` desc limit 1';
        return DB::selectOne($sql);
    }
    public function getLikeCountByCid($cid){
        $sql = 'select count(uid) count from `like` where cid=?';
        return @DB::selectOne($sql,[$cid])->count ? : 0;
    }
    public function getLikeCount($uid, $cid){
        $sql = 'select count(uid) count from `like` where uid=? and cid=?';
        return @DB::selectOne($sql,[$uid,$cid])->count ? : 0;
    }
    public function updateLike($uid,$cid){
        $sql = 'update `like` set uid = ?,cid = ? where uid = ?';
        return @DB::update($sql,[$uid,$cid, $uid]);
    }
    public function addLike($uid,$cid){
        $sql = "insert into `like`(uid,cid) values(?,?)";
        return @DB::update($sql,[$uid,$cid]);
    }
    public function delLike($uid,$cid){
        $sql = "delete from `like` where uid=? and cid = ? ";
        return DB::delete($sql, [$uid, $cid]);
    }
    public  function getClassicByIndex($index){
        $sql = 'select * from classic where `index` = ?';
        return DB::selectOne($sql ,[$index]);
    }
}