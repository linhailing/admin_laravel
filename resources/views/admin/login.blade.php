<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/17
 * Time: 13:51
 */
?>
<html><head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/client/admin/lib/html5shiv.js"></script>
    <script type="text/javascript" src="/client/admin/lib/respond.min.js"></script>
    <![endif]-->
    <link href="/client/admin/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css">
    <link href="/client/admin/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css">
    <link href="/client/admin/h-ui.admin/css/style.css" rel="stylesheet" type="text/css">
    <link href="/client/admin/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css">
    <!--[if IE 6]>
    <script type="text/javascript" src="/client/admin/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
    <script>DD_belatedPNG.fix('*');</script>
    <![endif]-->
    <title>后台登录 - H-ui.admin v3.1</title>
    <meta name="keywords" content="henry">
    <meta name="description" content="henry">
    <link rel="stylesheet" href="/client/admin/lib/layer/2.4/skin/layer.css" id="layui_layer_skinlayercss" style=""></head>
<body>
<div class="header"></div>
<div class="loginWraper">
    <div id="loginform" class="loginBox">
        <form class="form form-horizontal" action="{{url('/admin/loginCheck')}}" method="post">
        @csrf
        <div class="row cl">
            <label class="form-label col-xs-3" style="width:8%;margin-left:17%;"><i class="Hui-iconfont"></i></label>
            <div class="formControls col-xs-8">
                <input name="username" type="text" placeholder="账户" class="input-text size-L">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-xs-3" style="width:8%;margin-left:17%;"><i class="Hui-iconfont"></i></label>
            <div class="formControls col-xs-8">
                <input name="password" type="password" placeholder="密码" class="input-text size-L">
            </div>
        </div>
        <!--<div class="row cl">
            <div class="formControls col-xs-8 col-xs-offset-3">
                <input class="input-text size-L" type="text" placeholder="验证码" onblur="if(this.value==''){this.value='验证码:'}" onclick="if(this.value=='验证码:'){this.value='';}" value="验证码:" style="width:150px;">
                <img src=""> <a id="kanbuq" href="javascript:;">看不清，换一张</a>
            </div>
        </div>-->
        <!--<div class="row cl">
            <div class="formControls col-xs-8 col-xs-offset-3">
                <label for="online">
                    <input type="checkbox" name="online" id="online" value="">
                    使我保持登录状态
                </label>
            </div>
        </div>-->
        <div class="row cl">
            <div class="formControls col-xs-8 col-xs-offset-3">
                <input type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
                <input type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
            </div>
        </div>
        </form>
    </div>
</div>
<div class="footer">Henry</div>
<script type="text/javascript" src="/client/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/client/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/client/admin/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/client/admin/js/H-ui.admin.js"></script>
<div id="cli_dialog_div"></div>
</body></html>
