<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 15:27
 */

namespace App\Http\Controllers\Api;


use App\Models\Model;
use App\Util;
use Illuminate\Http\Request;


class ClassicController extends ApiController{
    public function classic(){
        $uid = 1;
        $classic = Model::Api()->getClassic();
        $count = Model::Api()->getLikeCountByCid($classic->id);
        $fan = Model::Api()->getLikeCount($uid,$classic->id);
        $classic->fav_nums = $count ? $count : 0;
        $classic->like_status = $fan ? 1 : 0;
        return $this->jsonp(['info'=>$classic,'ret'=>0]);
    }
    public function like(Request $request){
        $arr = ['u'=>'1','p'=>'1','d'=>'1111'];
        $str = Util::up_encode($arr);
        dd($str);
        $uid = 1;
        $cid = $request->get('cid');
        if (!$cid) return $this->jsonp(['ret'=>1,'msg'=>'参数错误！']);
        $count = Model::Api()->getLikeCount($uid, $cid);
        if ($count >= 1) Model::Api()->delLike($uid,$count);
        else Model::Api()->addLike($uid, $cid);
        return $this->jsonp(['ret'=>0,'msg'=>'ok']);
    }
}