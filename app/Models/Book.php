<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2018/12/16
 * Time: 上午12:29
 */

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Book extends Model{
    public function insertBook($data) {
        $sql = 'insert into books('.$this->fields($data).') values('.$this->values($data).')';
        return DB::insert($sql, $data);
    }
    public function updateBook($id, $data) {
        $sql = 'update books set '.$this->set($data).' where id=:id';
        $data['id'] = $id;
        return DB::update($sql, $data);
    }
    public function getBooks($page=1){
        $start = ($page - 1) * PAGESIZE;
        $limit = PAGESIZE;
        $sql = "select * from books  order by `id` desc ";
        $sql .= " limit {$start}, {$limit}";
        return DB::select($sql);
    }
    public function getBooksCount(){
        $sql = "select count(*) as count from books ";
        return @DB::selectOne($sql)->count;
    }
    public function getBookById($id){
        $sql = "select * from books  where id= ? ";
        return DB::selectOne($sql,[$id]);
    }
}