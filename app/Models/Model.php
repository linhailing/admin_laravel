<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 15:30
 */
namespace App\Models;

class Model{
    public function insertFields($data) {
        return '(`'.implode('`,`', array_keys($data)).'`) values(:'.implode(',:', array_keys($data)).')';
    }
    public function insertAndUpdateFields(&$data, $data2 = null) {
        $sql = $this->insertFields($data);
        if (empty($data2)) $data2 = $data;
        $sql .= ' on duplicate key update '.$this->set2($data, $data2);
        return $sql;
    }
    public function set($data) {
        $sql = array();
        foreach ($data as $key=>$value) {
            $sql[] = sprintf('`%s`=:%s', $key, $key);
        }
        return implode(',', $sql);
    }
    public function set2(&$data, $data2) {
        $sql = array();
        foreach ($data2 as $key=>$value) {
            $sql[] = sprintf('`%s`=:%s2', $key, $key);
            $data[$key.'2'] = $value;
        }
        return implode(',', $sql);
    }
    public function add_set($data) {
        $sql = array();
        foreach($data as $key=>$value) {
            $sql[] = sprintf('`%s`= `%s` + :%s', $key, $key, $key);
        }
        return implode(',', $sql);
    }
    public function fields($data) {
        return '`'.implode('`,`', array_keys($data)).'`';
    }
    public function values($data) {
        $sql = array();
        foreach ($data as $key=>$value) {
            $sql[] = ':'.$key;
        }
        return implode(',', $sql);
    }
    public function values2($data) {
        $sql = array();
        foreach ($data as $key=>$value) {
            $sql[] = '"'.$this->_toString($value).'"';
        }
        return implode(',', $sql);
    }
    private function _toString($value) {
        if ($value === null) {
            return 'NULL';
        } elseif ($value === true) {
            return '1';
        } elseif ($value === false) {
            return '0';
        } else {
            return addslashes($value);
        }
    }
    public function combinateSql($data) {
        $insertValue = '';
        foreach ($data as $value) {
            if ($insertValue)	$insertValue .= ",(".$this->values2($value).")";
            else				$insertValue .= "values(".$this->values2($value).")";
        }
        $result['insertKey'] 	= $data[0];
        $result['insertValue'] 	= $insertValue;
        return $result;
    }
    private static $Api = null;
    public static function Api() {
        if (!self::$Api) self::$Api = new Api();
        return self::$Api;
    }
    private static $Sys = null;
    public static function Sys() {
        if (!self::$Sys) self::$Sys = new Sys();
        return self::$Sys;
    }
}