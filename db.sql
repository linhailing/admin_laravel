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