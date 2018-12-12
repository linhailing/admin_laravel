<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 13:26
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use http\Env\Request;

class ClassicController extends AdminController{
    public function index(){
        $d = ['title'=>'刊期管理'];
        return view('admin.classic.classic_list',$d);
    }
    public function classicUpload(Request $request){
        $file = isset($_FILES['filedata']) ? @$_FILES['filedata'] : @$_FILES['uploadName'];
        if (!empty($file) && $file['name'] && $file['error'] == 0 && (isset($GLOBALS['upload_mime'][$file['type']]))) {
            $path = '/upload/classic/';
            $url = $path.TIMESTAMP.'.'.pathinfo($file['name'])['extension'];
            if (!is_dir(RESOURCEPATH.'/'.$path)){
                if(!mkdir(RESOURCEPATH.'/'.$path, 0777)){
                    return $this->jsonp(['ret'=>1, 'msg'=>'目录创建失败']);
                }
            };
            if (!move_uploaded_file($file['tmp_name'], RESOURCEPATH.'/'.$url))  return $this->jsonp(['ret'=>1, 'msg'=>'上传图片失败']);
            if($request->get('is_url')){
                return $this->jsonp(['ret'=>0, 'errno'=> 0,'url' => CDNRESOURCE.$url]);
            }else{
                return $this->jsonp(['ret'=>0, 'errno'=> 0,'url' => $url]);
            }

        }
        return $this->jsonp(['ret'=>1, 'msg'=>'上传图片失败!']);
    }
    public function classicAdd(){
        $d = ['title'=>'新增刊期'];
        return view('admin.classic.classic_add',$d);
    }
}
