CREATE TABLE `classic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '刊期id',
  `index` int(255) NOT NULL DEFAULT '0' COMMENT '期号',
  `title` varchar(255) NOT NULL COMMENT '期刊题目',
  `type` int(11) NOT NULL DEFAULT '100' COMMENT '期刊类型,这里的类型分为:100 电影 200 音乐 300 句子',
  `content` varchar(1000) NOT NULL COMMENT '期刊内容',
  `pubdate` varchar(20) NOT NULL COMMENT '发布日期=>2015-2',
  `image` varchar(500) NOT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT '期刊信息';

CREATE TABLE `like` (
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `cid` int(11) DEFAULT '0' COMMENT '刊期id',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '点赞信息';

--- 后台应用表
CREATE TABLE `sys_app` (
  `app_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用编号',
  `app_ename` varchar(50) DEFAULT NULL COMMENT '应用Code',
  `app_name` varchar(100) DEFAULT NULL COMMENT '应用名称',
  `app_img` varchar(200) DEFAULT NULL COMMENT '图片',
  `app_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `app_tree_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在导航中显示',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台应用表';

-- 后台功能表
CREATE TABLE `sys_app_function` (
  `func_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '功能编号',
  `app_id` int(11) NOT NULL COMMENT '应用编号',
  `func_name` varchar(50) DEFAULT NULL COMMENT '功能名称',
  `func_ename` varchar(100) DEFAULT NULL COMMENT '功能代码',
  `func_url` varchar(200) DEFAULT NULL COMMENT '地址',
  `func_img` varchar(200) DEFAULT NULL COMMENT '图标',
  `func_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` varchar(45) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`func_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台功能表';

-- 后台角色表
CREATE TABLE `sys_role` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色',
  `role_name` varchar(100) DEFAULT NULL COMMENT '角色名称',
  `role_ename` varchar(50) DEFAULT NULL COMMENT '角色代码',
  `role_funcnames` varchar(3000) DEFAULT NULL COMMENT '角色功能',
  `role_funcids` varchar(3000) DEFAULT NULL COMMENT '角色功能代码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台角色表';

-- 后台角色功能表
CREATE TABLE `sys_role_function` (
  `role_id` int(11) NOT NULL COMMENT '角色编号',
  `func_id` int(11) NOT NULL COMMENT '功能编号',
  `func_op` varchar(100) DEFAULT NULL COMMENT '功能操作',
  PRIMARY KEY (`role_id`,`func_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='后台角色功能表';

--- 系统管理员
CREATE TABLE `sys_admin_user` (
  `admin_id` int(11) NOT NULL COMMENT '用户',
  `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
  `role_id` int(11) NOT NULL COMMENT '角色',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '姓名',
  `reg_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '注册ip',
  `reg_date` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统管理员';