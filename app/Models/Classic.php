<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2018/12/16
 * Time: 上午12:29
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Classic extends Model{
    public function insertClassic($data) {
        $sql = 'insert into classic('.$this->fields($data).') values('.$this->values($data).')';
        return DB::insert($sql, $data);
    }
    public function updateClassic($id, $data) {
        $sql = 'update classic set '.$this->set($data).' where id=:id';
        $data['id'] = $id;
        return DB::update($sql, $data);
    }
    public function getClassic(){
        $sql = "select * from classic  order by `index` desc ";
        return DB::select($sql);
    }
    public function getClassicById($id){
        $sql = "select * from classic  where id= ? ";
        return DB::selectOne($sql,[$id]);
    }
}