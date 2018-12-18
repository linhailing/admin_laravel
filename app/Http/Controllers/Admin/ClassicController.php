<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 13:26
 */

namespace App\Http\Controllers\Admin;


use App\Models\Model;
use Illuminate\Http\Request;

class ClassicController extends AdminController{
    public function index(){
        $d = ['title'=>'刊期管理'];
        $d['list'] = Model::Classic()->getClassic();
        return view('admin.classic.classic_list',$d);
    }
    public function classicUpload(Request $request){
        $file = isset($_FILES['filedata']) ? @$_FILES['filedata'] : @$_FILES['uploadName'];
        if (!empty($file) && $file['name'] && $file['error'] == 0 && (isset($GLOBALS['upload_mime'][$file['type']]))) {
            $path = '/upload/classic/';
            $url = $path.TIMESTAMP.'.'.pathinfo($file['name'])['extension'];
            if (!is_dir(RESOURCEPATH.$path)){
                if(!mkdir(RESOURCEPATH.$path, 0777)){
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
    public function classicAdd(Request $request){
        $d = ['title'=>'新增刊期'];
        $id = intval($request->get('id'));
        $d['id'] = $id;
        $d['info'] =Model::Classic()->getClassicById($id);
        return view('admin.classic.classic_add',$d);
    }
    public function classicPost(Request $request){
        $id = intval($request->get('id'));
        $d = ['title'=>'新增刊期'];
        $index = $request->get('index');
        $title = $request->get('title');
        $type = $request->get('type');
        $content = $request->get('content');
        $pubdate = $request->get('pubdate');
        $image = $request->get('image');
        $src = $request->get('src');
        if (@$type == 200){
            if (empty($src)) return $this->msg('错误提示', '音乐地址不能空，谢谢');
        }
        $data = [
            'index' => $index,
            'title' => $title,
            'type' => $type,
            'content' => $content,
            'pubdate' => $pubdate,
            'image' => $image,
            'src' => $src
        ];
        if ($id > 0){
            Model::Classic()->updateClassic($id, $data);
        }else{
            Model::Classic()->insertClassic($data);
        }
        return redirect('admin/classic/classic_list');
    }
}
