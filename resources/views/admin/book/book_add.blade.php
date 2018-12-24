<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2018/12/23
 * Time: 12:32 PM
 */
?>
<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 14:07
 */
?>
@extends('admin/master')
@section('style')
@stop
@section('content')
    <form class="form form-horizontal" id="form-article-add" method="post" action="{{url('admin/book/post?id='.@$id)}}">
        @csrf
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>标题：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->title}}" placeholder="请填写标题" name="title">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">二级标题：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->subtitle}}" placeholder="请填写二级标题" name="subtitle">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">分类信息：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->category}}" placeholder="请填写二分类信息" name="category">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">封装信息：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->binding}}" placeholder="请填写封装信息" name="binding">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">作者：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->author}}" placeholder="请填写author" name="author">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">isbn：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->isbn}}" placeholder="请填写isbn" name="isbn">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">书籍页码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->pages}}" placeholder="请填写书籍页码" name="pages">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">书籍价格：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="number" class="input-text" value="{{@$info->price}}" placeholder="请填写书籍价格" name="price">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">发版日期：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" name="pubdate" value="{{@$info->pubdate}}" class="input-text Wdate">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">发版社：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" name="publisher" value="{{@$info->publisher}}" placeholder="请填写发版社"  class="input-text">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">书籍缩略图：</label>
            <div class="formControls col-xs-8 col-sm-9" style="width: 200px;">
                <textarea class="input-text hide" id="image" name="image">@if(@$info->image){{@$info->image}}@endif</textarea>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">书籍图：</label>
            <div class="formControls col-xs-8 col-sm-9" style="width: 600px;">
                <textarea class="input-text hide" id="images" name="images">{{@$info->images}}</textarea>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">书籍介绍：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea name="summary" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-1000" dragonfly="true" nullmsg="书籍介绍不能为空！" onKeyUp="textarealength(this,200)">{{@$info->summary}}</textarea>
                <p class="textarea-numberbar"><em class="textarea-length">0</em>/1000</p>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">翻译：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->translator}}" placeholder="请填写翻译" name="translator">
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
                <button onClick="window.location.reload()" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
            </div>
        </div>
    </form>
@stop
@section('script')
    <script type="text/javascript" src="/client/admin/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script type="text/javascript" src="/js/jquery.admin.extend.js"></script>

    <script type="text/javascript">
        $(function() {
            function cb(box, d) {
                if (d.ret != 0) return alert(d.msg);
                box.find('> .nup-img-img').attr('src', cdn + d.url);
            }
            var token = '<?=csrf_token()?>';
            $('#image').upload({ path: '/', url: '/admin/book/book_upload?_token='+ token, width:178, height:100,path:cdn }, cb);
            $('#images').upload({ path: '/', url: '/admin/book/book_upload?_token='+ token, width:178, height:100,length:3,path:cdn }, cb);
        })
    </script>
@stop

