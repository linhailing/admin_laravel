<?php
/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2018/12/16
 * Time: 3:46 PM
 * 提示框
 */
?>
@extends('admin/form')
@section('script')
@section('script')
    <script type="text/javascript">
        var msg = '{{@$msg}}';
        var title = '{{@$title}}';
        var html =  '<div id="modal-demo" class="modal" style="display: block;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"><div class="modal-dialog"><div class="modal-content radius"><div class="modal-header">' +
            '<h3 class="modal-title">{0}</h3><a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a></div><div class="modal-body"><p>{1}</p>' +
            '</div><div class="modal-footer"><button class="btn btn-primary">确定</button><button class="btn btn-close" data-dismiss="modal" aria-hidden="true">关闭</button></div></div></div></div>';
        $('body').append(html.format(title,msg));
        var url = '{{@$url}}'
        $('.close').click(function () {
            window.location.reload();
        })
        $('.btn-primary').click(function () {
            if (url) window.location = url;
            else window.history.back();
        });
        $('.btn-close').click(function () {
            window.location.reload();
        });
    </script>
@stop
@stop
