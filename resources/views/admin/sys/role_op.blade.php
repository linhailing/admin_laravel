<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/13
 * Time: 16:32
 */
?>
@extends('admin/master')
@section('style')
@stop
@section('content')
    <form class="form form-horizontal" id="form-article-add" method="post" action="{{url('admin/sys/role_post?func_id=')}}">
        @csrf
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">角色名称：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->role_name}}" placeholder="请填写应用代码" name="role_name">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">角色代码：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$info->role_ename}}" placeholder="请填写应用代码" name="role_ename">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">角色功能：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <textarea class="hide" name="role_funcnames" id="txtFuncsName"><?= @$info ? @$info->role_funcnames : "" ?></textarea>
                <textarea class="hide" name="role_funcids" id="txtFuncsID"><?= @$info ? @$info->role_funcids : "" ?></textarea>
                <table class="grid" style="width: 500px;">
                    <tr>
                        <th class="center t" style="padding-top:7px;">功能名稱</th>
                        <th class="center t" style="padding-top:7px;"><label><input type="checkbox" id="chkAll" /> 功能操作</label></th>
                    </tr>
                    <?php foreach($apps as $app):?>
                    <tr class="tree_table_node">
                        <td class="item pl25" colspan="2"><?=$app->app_name?></td>
                    </tr>
                    <?php foreach($funcs[$app->app_id] as $func):?>
                    <tr class="funcitem tree_table_item" name="<?=$func->func_name?>" value="<?=$func->func_id?>">
                        <td class="item pl25" title="<?=$func->func_name?>" nobr><label><input type="checkbox" class="chkFunc" name="chkFunc<?=$func->func_id?>" /> <?=$func->func_name?></label></td>
                        <td class="pl5">
                            <label><input type="checkbox" class="chkOp" name="view">浏览</label>&nbsp;&nbsp;
                            <label><input type="checkbox" class="chkOp" name="add">添加</label>&nbsp;&nbsp;
                            <label><input type="checkbox" class="chkOp" name="edit">修改</label>&nbsp;&nbsp;
                            <label><input type="checkbox" class="chkOp" name="delete">刪除</label>&nbsp;&nbsp;
                        </td>
                    </tr>
                    <?php endforeach;?>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">功能icon：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$func->func_img}}" placeholder="请填写应用icon" name="func_img">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">排序：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <input type="text" class="input-text" value="{{@$func->func_order}}" placeholder="请填写排序" name="func_order">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-4 col-sm-2">状态：</label>
            <div class="formControls col-xs-8 col-sm-9">
                <span class="select-box">
                    <select name="status" class="select">
                        <option value="">请选择状态</option>
                        <option value="1" @if(@$func->status == 1) selected @endif>显示</option>
                        <option value="0" @if(@$func->status == 0) selected @endif>隐藏</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="row cl">
            <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                <button class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
                <button  class="btn btn-default radius" type="button">返回</button>
            </div>
        </div>
    </form>
@stop
@section('script')
    <script>
        function checkValue() {
            var funcitem = $(".funcitem"), check = false, funcnames = '', funcids = '', me = null;
            funcitem.each(function() {
                me = $(this);
                check = me.find(".chkFunc").attr("checked") || false;
                if (check) {
                    funcnames += me.attr("name") + ";";
                    me.find(".chkOp").each(function() { funcids += (this.checked ? this.name : "") + "-"; });
                    funcids += me.attr("value") + ";";
                }
            });
            $("#txtFuncsName").val(funcnames);
            $("#txtFuncsID").val(funcids);
        }

        $(function () {
            $(".grid").treeTable(true);
            $(".chkFunc").click(function() {
                $(this).parent().parent().next().find("input[type='checkbox']").attr("checked", $(this).attr("checked") || false);
                checkValue();
            });
            $("#chkAll").click(function() {
                $(".grid input[type='checkbox']").attr("checked", $(this).attr("checked") || false);
                checkValue();
            });
            $(".chkOp").click(function() {
                var parent = $(this).parent().parent();
                var len = parent.find("input[type='checkbox']:checked").length;
                parent.prev().find("input[type='checkbox']:eq(0)").attr("checked", len == 0 ? false : true);
                checkValue();
            });
            $("button[type='submit']").click(function() {
                if ($("input[name='role_name']").val() == '') { alert("请輸入角色名称！"); $("input[name='role_name']").focus(); return false; };
                if ($("input[name='role_ename']").val() == '') { alert("请輸入角色代码！"); $("input[name='role_ename']").focus(); return false; };
                checkValue();
                return true;
            });
        })
    </script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script type="text/javascript" src="/js/jquery.admin.extend.js"></script>

@stop
