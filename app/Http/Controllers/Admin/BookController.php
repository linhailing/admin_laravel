<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2018/12/23
 * Time: 12:18 PM
 */
namespace App\Http\Controllers\Admin;


use App\Models\Model;
use Illuminate\Http\Request;

class BookController extends AdminController{
    public function index(Request $request){
        $d = ['title'=>'图书信息'];
        $page = intval($request->get('page',1));
        $d['list'] = Model::Book()->getBooks($page);
        $d['count'] = Model::Book()->getBooksCount();
        $d['page'] = $page;
        return view('admin/book/index', $this->render($d));
    }
    public function bookAdd(Request $request){
        $id = intval($request->get('id'));
        $d = ['title'=>'新增图书信息'];
        $d['info'] = Model::Book()->getBookById($id);
        $d['id'] = $id;
        return view('admin/book/book_add', $this->render($d));
    }
    public function bookPost(Request $request){
        $id = intval($request->get('id'));
        $title = $request->get('title');
        $author = $request->get('author');
        $subtitle = $request->get('subtitle');
        $category = $request->get('category');
        $binding = $request->get('binding');
        $isbn = $request->get('isbn');
        $pages = $request->get('pages');
        $price = $request->get('price');
        $pubdate = $request->get('pubdate');
        $publisher = $request->get('publisher');
        $image = $request->get('image');
        $images = $request->get('images');
        $summary = $request->get('summary');
        $translator = $request->get('translator');
        if (empty($title)) return $this->msg('错误提示','书籍标题不能为空');
        $data = [
            'title' => $title,
            'subtitle' => $subtitle,
            'author' => $author,
            'category' => $category,
            'binding' => $binding,
            'isbn' => $isbn,
            'pages' => $pages,
            'price' => $price,
            'pubdate' => $pubdate,
            'publisher' => $publisher,
            'image' => $image,
            'images' => $images,
            'summary' => $summary,
            'translator' => $translator,
        ];
        if ($id > 0){
            Model::Book()->updateBook($id,$data);
        }else{
            Model::Book()->insertBook($data);
        }
        return redirect('admin/book');
    }
    public function bookUpload(Request $request){
        $file = isset($_FILES['filedata']) ? @$_FILES['filedata'] : @$_FILES['uploadName'];
        if (!empty($file) && $file['name'] && $file['error'] == 0 && (isset($GLOBALS['upload_mime'][$file['type']]))) {
            $path = '/upload/books/';
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
}