<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 14:07
 */
?>
@extends('admin/form')
@section('style')
@stop
@section('content')
<form class="form form-horizontal" id="form-article-add">
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>期号：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="" placeholder="请填写期号" name="index">
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2">期刊题目：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" class="input-text" value="" placeholder="请填写期刊题目" name="title">
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>期刊类型：</label>
        <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
            <select name="" class="select">
                <option value="0">请选择类型</option>
                <option value="100">电影</option>
                <option value="200">音乐</option>
                <option value="300">句子</option>
            </select>
            </span>
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2">期刊内容：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <textarea name="content" cols="" rows="" class="textarea"  placeholder="说点什么...最少输入10个字符" datatype="*10-100" dragonfly="true" nullmsg="备注不能为空！" onKeyUp="textarealength(this,200)"></textarea>
            <p class="textarea-numberbar"><em class="textarea-length">0</em>/200</p>
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2">发布日期：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" name="pubdate" class="input-text Wdate">
        </div>
    </div>
    <div class="row cl">
        <label class="form-label col-xs-4 col-sm-2">缩略图：</label>
        <div class="formControls col-xs-8 col-sm-9">
            <textarea class="input-text hide" id="image" name="image"></textarea>
        </div>
    </div>
    <div class="row cl">
        <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
            <button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存并提交审核</button>
            <button onClick="article_save();" class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 保存草稿</button>
            <button onClick="removeIframe();" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
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
        $('#image').upload({ path: '/', url: '/admin/classic/upload?_token'+ token, width:178, height:100 }, cb);
    })
</script>
@stop
