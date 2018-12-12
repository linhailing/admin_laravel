<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/12
 * Time: 14:17
 */
?>
<?php
/**
 * Created by PhpStorm.
 * User: haili
 * Date: 2018/12/6
 * Time: 16:36
 */
?>
        <!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <LINK rel="Bookmark" href="favicon.ico" >
    <LINK rel="Shortcut Icon" href="favicon.ico" />
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/client/admin/lib/html5.js"></script>
    <script type="text/javascript" src="/client/admin/lib/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="/client/admin/h-ui/css/H-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/client/admin/h-ui.admin/css/H-ui.admin.css" />
    <link rel="stylesheet" type="text/css" href="/client/admin/lib/Hui-iconfont/1.0.8/iconfont.css" />

    <link rel="stylesheet" type="text/css" href="/client/admin/h-ui.admin/skin/default/skin.css" id="skin" />
    <link rel="stylesheet" type="text/css" href="/client/admin/h-ui.admin/css/style.css" />
    @yield('style')
</head>
<body>
<article class="page-container">
@yield('content')
</article>
<script type="text/javascript" src="/client/admin/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/client/admin/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
<script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/validate-methods.js"></script>
<script type="text/javascript" src="/client/admin/lib/jquery.validation/1.14.0/messages_zh.js"></script>
<script type="text/javascript" src="/client/admin/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/client/admin/h-ui.admin/js/H-ui.admin.page.js"></script>
@yield('script')
</body>
</html>

