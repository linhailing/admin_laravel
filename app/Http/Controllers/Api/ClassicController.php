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
        echo $str."<br>";
        $str1 = Util::up_decode($str);
        dd($str1);
        $uid = 1;
        $cid = $request->get('cid');
        if (!$cid) return $this->jsonp(['ret'=>1,'msg'=>'参数错误！']);
        $count = Model::Api()->getLikeCount($uid, $cid);
        if ($count >= 1) Model::Api()->delLike($uid,$count);
        else Model::Api()->addLike($uid, $cid);
        return $this->jsonp(['ret'=>0,'msg'=>'ok']);
    }
    //获取最新期刊
    public function getClassicLatest(){
        $info = Model::Api()->getClassicLatest();
        if (empty($info)) return $this->jsonp(['msg'=>'获取数据失败！','ret'=>400]);
        if (!empty($info->image)) $info->image = APPURLCDN.$info->image;
        if (!empty($info->type) && $info->type == 200 && !empty($info->src)) $info->scr = APPURLCDN.$info->src;
        else unset($info->src);
        return $this->jsonp(['list'=>$info,'ret'=>0]);
    }
    //获取下一期期刊
    public function getClassicNext($index){
        if (empty($index)) return $this->jsonp(['msg'=>'参数错误！','ret'=>400]);
        $info = Model::Api()->getClassicByIndex($index);
        if (empty($info)) return $this->jsonp(['msg'=>'暂无数据～','ret'=>400]);
        if (!empty($info->image)) $info->image = APPURLCDN.$info->image;
        if (!empty($info->type) && $info->type == 200 && !empty($info->src)) $info->scr = APPURLCDN.$info->src;
        else unset($info->src);
        return $this->jsonp(['list'=>$info,'ret'=>0]);
    }
    //获取下一期期刊
    public function getClassicPrevious($index){
        if (empty($index)) return $this->jsonp(['msg'=>'参数错误！','ret'=>400]);
        $info = Model::Api()->getClassicByIndex($index);
        if (empty($info)) return $this->jsonp(['msg'=>'暂无数据～','ret'=>400]);
        if (!empty($info->image)) $info->image = APPURLCDN.$info->image;
        if (!empty($info->type) && $info->type == 200 && !empty($info->src)) $info->scr = APPURLCDN.$info->src;
        else unset($info->src);
        return $this->jsonp(['list'=>$info,'ret'=>0]);
    }
}