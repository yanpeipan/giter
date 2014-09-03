
--
-- 表的结构 `pt_action`
--

DROP TABLE IF EXISTS `pt_action`;
CREATE TABLE IF NOT EXISTS `pt_action` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action` bigint(3) DEFAULT NULL COMMENT '1:add 2:update 3:del',
  `table_name` varchar(50) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `is_del` tinyint(1) DEFAULT '0' COMMENT '0:否  1：删除',
  `user_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ac_vid` (`vid`),
  KEY `ac_action` (`action`),
  KEY `ac_table_name` (`table_name`),
  KEY `idx_is_del` (`is_del`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=841255 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_action_for_solr`
--

DROP TABLE IF EXISTS `pt_action_for_solr`;
CREATE TABLE IF NOT EXISTS `pt_action_for_solr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action` bigint(3) DEFAULT NULL COMMENT '1:add 2:update 3:del',
  `table_name` varchar(50) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `is_del` tinyint(1) DEFAULT '0' COMMENT '0:否  1：删除',
  `user_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ac_vid` (`vid`),
  KEY `ac_action` (`action`),
  KEY `ac_table_name` (`table_name`),
  KEY `idx_is_del` (`is_del`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=215413 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin`
--

DROP TABLE IF EXISTS `pt_admin`;
CREATE TABLE IF NOT EXISTS `pt_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `is_super_admin` int(1) NOT NULL DEFAULT '0' COMMENT '是否为超级管理员',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_act`
--

DROP TABLE IF EXISTS `pt_admin_act`;
CREATE TABLE IF NOT EXISTS `pt_admin_act` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vid` int(11) unsigned NOT NULL,
  `action` smallint(1) unsigned NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `date_time` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=647335 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_profiles`
--

DROP TABLE IF EXISTS `pt_admin_profiles`;
CREATE TABLE IF NOT EXISTS `pt_admin_profiles` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `real_name` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_profiles_fields`
--

DROP TABLE IF EXISTS `pt_admin_profiles_fields`;
CREATE TABLE IF NOT EXISTS `pt_admin_profiles_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `field_type` varchar(50) NOT NULL DEFAULT '',
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` text,
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` text,
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_time`
--

DROP TABLE IF EXISTS `pt_admin_time`;
CREATE TABLE IF NOT EXISTS `pt_admin_time` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `user_name` varchar(255) NOT NULL COMMENT '用户名',
  `utime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4705 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_admin_users`
--

DROP TABLE IF EXISTS `pt_admin_users`;
CREATE TABLE IF NOT EXISTS `pt_admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastvisit_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username` (`username`),
  UNIQUE KEY `user_email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_assignments`
--

DROP TABLE IF EXISTS `pt_assignments`;
CREATE TABLE IF NOT EXISTS `pt_assignments` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pt_auth_info`
--

DROP TABLE IF EXISTS `pt_auth_info`;
CREATE TABLE IF NOT EXISTS `pt_auth_info` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vid` int(11) unsigned NOT NULL,
  `time` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `date_time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=125213 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_background_image`
--

DROP TABLE IF EXISTS `pt_background_image`;
CREATE TABLE IF NOT EXISTS `pt_background_image` (
  `id` int(11) NOT NULL,
  `pic` varchar(255) DEFAULT NULL COMMENT '图片url',
  `status` varchar(45) DEFAULT '1' COMMENT '0 backgokund  1，晴 2阴  3 阵雨  4 大雨  5暴雨  6小雪  7大雪。\\\\\\\\n		',
  `sort` int(3) DEFAULT '500' COMMENT '排序',
  `big_pic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pt_category_cover`
--

DROP TABLE IF EXISTS `pt_category_cover`;
CREATE TABLE IF NOT EXISTS `pt_category_cover` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL,
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '图片路径',
  `time` int(11) NOT NULL COMMENT '最近一次封面更新时间',
  `user_id` varchar(45) NOT NULL DEFAULT '' COMMENT '操作者',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0',
  `name_pic` varchar(255) DEFAULT NULL,
  `bg_pic` varchar(255) DEFAULT NULL,
  `cover_pic` varchar(255) NOT NULL,
  `is_animate` varchar(255) DEFAULT NULL,
  `name_pic_new` varchar(255) DEFAULT NULL,
  `bg_pic_new` varchar(255) DEFAULT NULL,
  `cover_pic_new` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index2` (`pic`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类封面图片表' AUTO_INCREMENT=104 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_channel`
--

DROP TABLE IF EXISTS `pt_channel`;
CREATE TABLE IF NOT EXISTS `pt_channel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ch_name` varchar(150) NOT NULL COMMENT '频道名字',
  `time` varchar(255) NOT NULL COMMENT '添加时间戳',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片地址',
  `ups` int(11) DEFAULT '0' COMMENT '更新数',
  `order_id` int(11) DEFAULT NULL COMMENT '排序',
  `desc` text COMMENT '描述',
  `is_vision` int(10) DEFAULT '0' COMMENT '显示',
  `tags` int(1) DEFAULT '0' COMMENT '标记',
  `ch_type` int(1) DEFAULT '1' COMMENT '频道类型1编辑创建，2用户创建',
  `type_id` varchar(150) DEFAULT '' COMMENT '产品类型ID',
  `ch_yunimg` varchar(255) DEFAULT NULL COMMENT 'upyun存储图片地址',
  `width` int(11) DEFAULT NULL COMMENT '封面图片的宽度',
  `height` int(11) DEFAULT NULL COMMENT '封面图片的高度',
  `position_x` int(11) DEFAULT NULL COMMENT 'x坐标',
  `position_y` int(11) DEFAULT NULL COMMENT 'y坐标',
  `category` int(11) DEFAULT NULL COMMENT '所属分类id，例如电影2',
  `bg_img` varchar(255) DEFAULT NULL COMMENT '终端，边框图',
  `cover_img` varchar(255) DEFAULT NULL COMMENT '封面，终端3.0二级页面',
  `sotu_cate_id` varchar(200) DEFAULT NULL,
  `sotu_isshow` int(11) unsigned DEFAULT NULL,
  `sotu_order_id` int(11) unsigned DEFAULT NULL,
  `sotu_bgcolor` varchar(45) DEFAULT NULL,
  `sotu_bgimg` varchar(200) DEFAULT NULL,
  `sotu_mimg` varchar(200) CHARACTER SET big5 DEFAULT NULL,
  `sotu_simg` varchar(200) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `is_show` int(11) DEFAULT NULL,
  `for_terminal_version` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ch_name` (`ch_name`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=326 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_channel_order`
--

DROP TABLE IF EXISTS `pt_channel_order`;
CREATE TABLE IF NOT EXISTS `pt_channel_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ch_id` int(11) DEFAULT NULL COMMENT '频道ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `order_id` int(11) DEFAULT NULL COMMENT '是用户的第多少个频道',
  `ch_type` int(1) NOT NULL DEFAULT '1' COMMENT '1编辑创建，2用户创建',
  `v_cate` int(11) DEFAULT NULL,
  `v_type` int(11) DEFAULT NULL,
  `v_area` int(11) DEFAULT NULL,
  `v_year` int(11) DEFAULT NULL,
  `ch_name` varchar(255) DEFAULT NULL COMMENT '频道名字',
  `description` text COMMENT '描述',
  `tags` int(1) NOT NULL DEFAULT '0',
  `is_del` int(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `ctime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_ch_id` (`ch_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87451 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_channel_videos`
--

DROP TABLE IF EXISTS `pt_channel_videos`;
CREATE TABLE IF NOT EXISTS `pt_channel_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v_id` int(11) DEFAULT NULL COMMENT '视频的id',
  `ch_id` int(11) DEFAULT NULL COMMENT '频道id',
  `time` int(10) DEFAULT NULL COMMENT '添加时间',
  `orders_id` int(11) DEFAULT NULL COMMENT '排序',
  `user_id` varchar(255) DEFAULT NULL COMMENT '用户',
  PRIMARY KEY (`id`),
  KEY `v_id` (`v_id`),
  KEY `ch_id` (`ch_id`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52243 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_charge_relat`
--

DROP TABLE IF EXISTS `pt_charge_relat`;
CREATE TABLE IF NOT EXISTS `pt_charge_relat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vid` int(10) unsigned NOT NULL,
  `vname` varchar(255) DEFAULT '',
  `letv` varchar(255) DEFAULT '',
  `youku` varchar(255) DEFAULT '',
  `qiyi` varchar(255) DEFAULT '',
  `sina` varchar(255) DEFAULT '',
  `qq` varchar(255) DEFAULT '',
  `cntv` varchar(255) DEFAULT '',
  `tudou` varchar(255) DEFAULT '',
  `wangyi` varchar(255) DEFAULT '',
  `wole` varchar(255) DEFAULT '',
  `ku6` varchar(255) DEFAULT '',
  `sohu` varchar(255) DEFAULT '',
  `ifeng` varchar(255) DEFAULT '',
  `m1905` varchar(255) DEFAULT '',
  `pps` varchar(255) DEFAULT '',
  `pptv` varchar(255) DEFAULT '',
  `umiwi` varchar(255) DEFAULT '',
  `tv189` varchar(255) DEFAULT '',
  `yinyuetai` varchar(255) DEFAULT '',
  `joy` varchar(255) DEFAULT '',
  `funshion` varchar(255) DEFAULT '',
  `xunlei` varchar(255) DEFAULT '',
  `is_new` varchar(255) DEFAULT '',
  `category` int(11) DEFAULT '0',
  `letv_ok` tinyint(1) DEFAULT '0',
  `youku_ok` tinyint(1) DEFAULT '0',
  `qiyi_ok` tinyint(1) DEFAULT '0',
  `sina_ok` tinyint(1) DEFAULT '0',
  `qq_ok` tinyint(1) DEFAULT '0',
  `cntv_ok` tinyint(1) DEFAULT '0',
  `tudou_ok` tinyint(1) DEFAULT '0',
  `wangyi_ok` tinyint(1) DEFAULT '0',
  `wole_ok` tinyint(1) DEFAULT '0',
  `ku6_ok` tinyint(1) DEFAULT '0',
  `sohu_ok` tinyint(1) DEFAULT '0',
  `ifeng_ok` tinyint(1) DEFAULT '0',
  `m1905_ok` tinyint(1) DEFAULT '0',
  `pps_ok` tinyint(1) DEFAULT '0',
  `pptv_ok` tinyint(1) DEFAULT '0',
  `umiwi_ok` tinyint(1) DEFAULT '0',
  `tv189_ok` tinyint(1) DEFAULT '0',
  `yinyuetai_ok` tinyint(1) DEFAULT '0',
  `joy_ok` tinyint(1) DEFAULT '0',
  `funshion_ok` tinyint(1) DEFAULT '0',
  `xunlei_ok` tinyint(1) DEFAULT '0',
  `cnt` int(11) unsigned NOT NULL DEFAULT '0',
  `cnt2` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_vid` (`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci' AUTO_INCREMENT=1127 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_charge_tv`
--

DROP TABLE IF EXISTS `pt_charge_tv`;
CREATE TABLE IF NOT EXISTS `pt_charge_tv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tv_id` int(11) NOT NULL COMMENT '第几集',
  `tv_name` varchar(255) NOT NULL DEFAULT '' COMMENT '每一集的名字',
  `tv_parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属电影ID',
  `tv_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '每一集的播放地址',
  `tv_play_count` varchar(11) NOT NULL DEFAULT '' COMMENT '每一集的播放次数',
  `tv_support` int(11) NOT NULL DEFAULT '0' COMMENT '顶（针对一集的）',
  `tv_opposition` int(11) NOT NULL DEFAULT '0' COMMENT '踩（针对一集的）',
  `time_length` int(11) NOT NULL DEFAULT '0' COMMENT '时长（每一集的）',
  `source` varchar(255) NOT NULL DEFAULT '' COMMENT '来源',
  `time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_del` int(1) DEFAULT '0' COMMENT '是否显示 0是显示 1是不显示',
  `swf_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'flash播放地址',
  `user_id` varchar(255) DEFAULT '' COMMENT '用户名称',
  `tv_time` varchar(255) DEFAULT '' COMMENT '时间 针对综艺的年月',
  `tv_resolution` varchar(255) DEFAULT '' COMMENT '分集分辨率',
  PRIMARY KEY (`id`),
  KEY `tv_id` (`tv_id`),
  KEY `tv_parent_id` (`tv_parent_id`),
  KEY `source` (`source`),
  KEY `idx_is_del` (`is_del`),
  KEY `idx_time` (`time`),
  KEY `idx_tv_url` (`tv_url`),
  KEY `tv_name` (`tv_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1250 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_charge_video`
--

DROP TABLE IF EXISTS `pt_charge_video`;
CREATE TABLE IF NOT EXISTS `pt_charge_video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '电影名',
  `director` varchar(255) NOT NULL DEFAULT '' COMMENT '导演',
  `main_actors` varchar(255) NOT NULL DEFAULT '' COMMENT '主演',
  `desc` text NOT NULL COMMENT '简介/剧情（电视剧）',
  `comment_desc` text NOT NULL COMMENT '推荐描述',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图片',
  `free` int(1) NOT NULL DEFAULT '0' COMMENT '1收费，0免费',
  `genuine` int(1) NOT NULL DEFAULT '0' COMMENT '1正片/0非正片/2其他',
  `resolution` int(1) NOT NULL DEFAULT '0' COMMENT '分辨率（0普通/1高清/2超清/3其他）',
  `time_length` varchar(255) NOT NULL DEFAULT '' COMMENT '时长',
  `area` varchar(255) NOT NULL DEFAULT '0' COMMENT '地区（大陆、港、欧美..）',
  `year` varchar(255) NOT NULL DEFAULT '0' COMMENT '年份',
  `category` int(2) NOT NULL DEFAULT '0' COMMENT '分类（电影/电视剧..）',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '类型（科幻/喜剧/动漫...）',
  `play_count` int(11) NOT NULL DEFAULT '0' COMMENT '播放次数',
  `score` float NOT NULL DEFAULT '0' COMMENT '评星',
  `source` varchar(255) NOT NULL DEFAULT '' COMMENT '视频来源',
  `tv_application_time` varchar(255) NOT NULL DEFAULT '' COMMENT '上映时间',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论总数',
  `letter` varchar(255) DEFAULT '' COMMENT '字母索引',
  `is_show` int(1) DEFAULT '0' COMMENT '是否显示',
  `user_id` varchar(255) DEFAULT '',
  `age` int(11) NOT NULL DEFAULT '0' COMMENT '年龄 6未成年 18成年 100所有',
  `alias` varchar(255) DEFAULT '' COMMENT '别名',
  `is_show_source` int(1) DEFAULT '1' COMMENT '接入点控制',
  `status` int(1) DEFAULT '1' COMMENT '视频状态(是否完结)',
  `yun_img` varchar(255) DEFAULT '' COMMENT 'upyun存储封面图片',
  `source_resolution` varchar(255) NOT NULL DEFAULT '' COMMENT '接入点分辨率',
  `rank_order` int(11) NOT NULL DEFAULT '500' COMMENT '排行排序',
  `hd_order` int(11) NOT NULL DEFAULT '10000' COMMENT '高清排序',
  `resolution_value` varchar(255) NOT NULL DEFAULT '',
  `is_update` int(1) NOT NULL DEFAULT '0' COMMENT '是否修改',
  `name_1` varchar(255) DEFAULT '' COMMENT 'name清洗时所用',
  `name_2` varchar(255) DEFAULT '' COMMENT 'name清洗时所用',
  `is_spider` tinyint(2) DEFAULT '0' COMMENT '1为spider添加，0为后台添加，2为老库数据',
  `update_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `is_create` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `idx_type` (`type`),
  KEY `idx_source` (`source`),
  KEY `area` (`area`),
  KEY `year` (`year`),
  KEY `idx_play_count` (`play_count`),
  KEY `idx_is_show` (`is_show`),
  KEY `director` (`director`),
  KEY `main_actors` (`main_actors`),
  KEY `tv_application_time` (`tv_application_time`),
  KEY `rank_order` (`rank_order`),
  KEY `comment_count` (`comment_count`),
  KEY `resolution_value` (`resolution_value`),
  KEY `category` (`category`),
  KEY `time` (`time`),
  KEY `name_1` (`name_1`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1127 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_china`
--

DROP TABLE IF EXISTS `pt_china`;
CREATE TABLE IF NOT EXISTS `pt_china` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `name_type` set('province','city','station') NOT NULL,
  `weather_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '中国天气网城市id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2953 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_active_user`
--

DROP TABLE IF EXISTS `pt_cnt_active_user`;
CREATE TABLE IF NOT EXISTS `pt_cnt_active_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` varchar(10) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `soft` varchar(40) NOT NULL DEFAULT '',
  `soft_version` varchar(180) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `date` (`date_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_areas`
--

DROP TABLE IF EXISTS `pt_cnt_areas`;
CREATE TABLE IF NOT EXISTS `pt_cnt_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provice` varchar(45) DEFAULT NULL,
  `adduser` int(11) DEFAULT NULL,
  `addmil` int(11) DEFAULT NULL,
  `start` int(11) DEFAULT NULL,
  `startmil` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_area_actives`
--

DROP TABLE IF EXISTS `pt_cnt_area_actives`;
CREATE TABLE IF NOT EXISTS `pt_cnt_area_actives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) DEFAULT NULL,
  `uids` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_area_list`
--

DROP TABLE IF EXISTS `pt_cnt_area_list`;
CREATE TABLE IF NOT EXISTS `pt_cnt_area_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) DEFAULT NULL,
  `new_user` int(11) DEFAULT '0',
  `active_user` int(11) DEFAULT '0',
  `play_cnt` int(11) DEFAULT '0',
  `new_user_from_equipment` int(11) DEFAULT '0' COMMENT '新增用户',
  `active_user_from_list` varchar(45) DEFAULT NULL COMMENT '激活用户',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=232 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_area_newusers`
--

DROP TABLE IF EXISTS `pt_cnt_area_newusers`;
CREATE TABLE IF NOT EXISTS `pt_cnt_area_newusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `area_id` int(11) DEFAULT NULL,
  `uids` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_edit`
--

DROP TABLE IF EXISTS `pt_cnt_edit`;
CREATE TABLE IF NOT EXISTS `pt_cnt_edit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL DEFAULT '',
  `date_time` varchar(10) NOT NULL DEFAULT '' COMMENT 'Y-m-d',
  `cnt` int(11) NOT NULL DEFAULT '0',
  `type` enum('ADD','SPIDER') NOT NULL DEFAULT 'ADD',
  `pro` varchar(45) DEFAULT NULL,
  `action` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3860 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_edit_list`
--

DROP TABLE IF EXISTS `pt_cnt_edit_list`;
CREATE TABLE IF NOT EXISTS `pt_cnt_edit_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL DEFAULT '',
  `table_name` varchar(50) DEFAULT 'tv_new',
  `vid` int(11) DEFAULT NULL,
  `type` enum('ADD','SPIDER','DEL','UPDATE') NOT NULL DEFAULT 'ADD',
  `date_time` varchar(10) NOT NULL DEFAULT '' COMMENT 'Y-m-d',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=387735 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_edit_list_new`
--

DROP TABLE IF EXISTS `pt_cnt_edit_list_new`;
CREATE TABLE IF NOT EXISTS `pt_cnt_edit_list_new` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
  `pro` varchar(255) DEFAULT '' COMMENT '应用名（CP、SPIDER、HELPER）',
  `method` varchar(100) DEFAULT NULL COMMENT '功能',
  `action` varchar(255) DEFAULT 'ADD' COMMENT '操作 ADD UPDATE DEL 必须为此三种操作',
  `date_time` varchar(10) NOT NULL DEFAULT '' COMMENT '时间（Y-M-D）',
  `time` int(11) NOT NULL DEFAULT '0' COMMENT '时间（时间戳）',
  `table_name` varchar(100) DEFAULT NULL COMMENT '操作表名',
  `t_id` int(11) DEFAULT NULL COMMENT '操作数据的ID',
  `cnt_list_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pro` (`pro`),
  KEY `action` (`action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1029399 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_ips`
--

DROP TABLE IF EXISTS `pt_cnt_ips`;
CREATE TABLE IF NOT EXISTS `pt_cnt_ips` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `isp` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_list`
--

DROP TABLE IF EXISTS `pt_cnt_list`;
CREATE TABLE IF NOT EXISTS `pt_cnt_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `tid` bigint(20) DEFAULT '0',
  `ip` varchar(15) DEFAULT NULL,
  `place_id` int(11) DEFAULT NULL,
  `time` varchar(10) DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  `user_agent` varchar(100) DEFAULT NULL,
  `agent` varchar(20) DEFAULT NULL,
  `soft` varchar(45) NOT NULL DEFAULT 'TuziHD2.0' COMMENT '软件名',
  `soft_version` varchar(128) DEFAULT NULL COMMENT '软件版本',
  PRIMARY KEY (`id`),
  KEY `idx_method_id` (`method_id`),
  KEY `idx_time` (`time`),
  KEY `idx_tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_list_date`
--

DROP TABLE IF EXISTS `pt_cnt_list_date`;
CREATE TABLE IF NOT EXISTS `pt_cnt_list_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` varchar(10) NOT NULL DEFAULT '' COMMENT '日期',
  `user_cnt` int(11) NOT NULL DEFAULT '0' COMMENT '用户总数',
  `new_user` int(11) NOT NULL DEFAULT '0' COMMENT '新增用户数',
  `active_user` int(11) NOT NULL DEFAULT '0' COMMENT '活跃用户',
  `play_cnt` int(11) NOT NULL DEFAULT '0' COMMENT '点播数',
  `soft` varchar(45) DEFAULT NULL COMMENT '软件名',
  `soft_version` varchar(128) DEFAULT NULL COMMENT '软件版本',
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3125 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_playerinfo`
--

DROP TABLE IF EXISTS `pt_cnt_playerinfo`;
CREATE TABLE IF NOT EXISTS `pt_cnt_playerinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned DEFAULT NULL,
  `vid` int(11) unsigned DEFAULT NULL,
  `screen_ratio` varchar(50) DEFAULT NULL COMMENT '屏幕分辨率',
  `play_time` varchar(20) DEFAULT NULL COMMENT '播放时间',
  `play_ratio` varchar(10) DEFAULT NULL COMMENT '播放分辨率',
  `definition` varchar(50) DEFAULT NULL COMMENT '清晰度',
  `place_id` int(11) unsigned DEFAULT NULL,
  `date_time` varchar(50) DEFAULT NULL,
  `flg` tinyint(1) unsigned DEFAULT '0',
  `tv_id` int(11) unsigned DEFAULT '0' COMMENT 'tv_new的主键id',
  `tid` varchar(45) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `api_version` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `playertime` (`play_time`),
  KEY `idx_vid` (`vid`),
  KEY `idx_datetime` (`date_time`),
  KEY `idx_tvid` (`tv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15801260 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_sum`
--

DROP TABLE IF EXISTS `pt_cnt_sum`;
CREATE TABLE IF NOT EXISTS `pt_cnt_sum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dates` int(11) NOT NULL COMMENT '日期',
  `adduser` int(11) NOT NULL COMMENT '新增用户',
  `activeuser` int(11) NOT NULL COMMENT '活跃用户',
  `start` int(11) NOT NULL COMMENT '启动次数',
  `times` int(11) NOT NULL COMMENT '启动时长',
  `pt_cnt_sumcol` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_summaray`
--

DROP TABLE IF EXISTS `pt_cnt_summaray`;
CREATE TABLE IF NOT EXISTS `pt_cnt_summaray` (
  `id` int(11) NOT NULL COMMENT '统计概要',
  `logintime` int(11) DEFAULT NULL COMMENT '登陆时间',
  `startsum` int(11) DEFAULT NULL COMMENT '启动次数',
  `starttime` int(11) DEFAULT NULL COMMENT '启动时间',
  `date` int(10) unsigned NOT NULL COMMENT '日期',
  `uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_UNIQUE` (`uid`),
  UNIQUE KEY `date_UNIQUE` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pt_cnt_video`
--

DROP TABLE IF EXISTS `pt_cnt_video`;
CREATE TABLE IF NOT EXISTS `pt_cnt_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_time` int(11) DEFAULT NULL COMMENT '每天的时间撮',
  `dy` int(11) DEFAULT '0' COMMENT '电影',
  `dsj` int(11) DEFAULT '0' COMMENT '电视剧',
  `area` int(11) DEFAULT NULL,
  `cnt` int(11) DEFAULT NULL,
  `cnt_new` int(11) DEFAULT NULL,
  `cnt_tv` int(11) DEFAULT NULL,
  `cnt_new_tv` int(11) DEFAULT NULL,
  `source` varchar(45) DEFAULT NULL,
  `dm` int(11) DEFAULT '0' COMMENT '动漫',
  `zy` int(11) DEFAULT '0' COMMENT '综艺',
  `ggk` int(11) DEFAULT '0' COMMENT '公开课',
  `zb` int(11) DEFAULT '0' COMMENT '直播',
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3457 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_collect`
--

DROP TABLE IF EXISTS `pt_collect`;
CREATE TABLE IF NOT EXISTS `pt_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户UID',
  `vid` int(11) DEFAULT NULL COMMENT '视频ID',
  `mark` int(1) DEFAULT '0' COMMENT '1删除，0未删除',
  `time` int(10) DEFAULT NULL COMMENT '收藏时间',
  `tid` bigint(20) NOT NULL DEFAULT '0' COMMENT 'tid',
  `v_name` varchar(200) DEFAULT '' COMMENT '用户添加视频name',
  `cate_id` int(11) DEFAULT '0' COMMENT '视频分类id',
  `v_url` varchar(255) DEFAULT '' COMMENT '视频链接',
  `is_user_add` int(1) DEFAULT '0' COMMENT '1用户添加0用户收藏',
  `source` varchar(45) DEFAULT NULL COMMENT '接入点',
  `userid` int(11) DEFAULT NULL COMMENT '区分兔单收藏和普通收藏用',
  `ts` int(11) DEFAULT '0' COMMENT '用于控制ios端更新标志的显示与否',
  `tv_id` int(11) DEFAULT '0' COMMENT '分集的id,追剧用',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_mark` (`mark`),
  KEY `idx_time` (`time`),
  KEY `idx_tid` (`tid`),
  KEY `idx_vid` (`vid`),
  KEY `idx_userid` (`userid`),
  KEY `idx_tv_id` (`tv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=494724 ;

--
-- 触发器 `pt_collect`
--
DROP TRIGGER IF EXISTS `t_afterinsert_on_pt_collect`;
DELIMITER //
CREATE TRIGGER `t_afterinsert_on_pt_collect` AFTER INSERT ON `pt_collect`
 FOR EACH ROW insert into pt_sotu_rating(uid,vid,preference,time) values(new.uid,new.vid,(select score_douban from pt_new_video where new.vid=id),new.time)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_common_member`
--

DROP TABLE IF EXISTS `pt_common_member`;
CREATE TABLE IF NOT EXISTS `pt_common_member` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `email` char(40) NOT NULL DEFAULT '',
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `emailstatus` tinyint(1) NOT NULL DEFAULT '0',
  `avatarstatus` tinyint(1) NOT NULL DEFAULT '0',
  `videophotostatus` tinyint(1) NOT NULL DEFAULT '0',
  `adminid` tinyint(1) NOT NULL DEFAULT '0',
  `groupid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `groupexpiry` int(10) unsigned NOT NULL DEFAULT '0',
  `extgroupids` char(20) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `credits` int(10) NOT NULL DEFAULT '0',
  `notifysound` tinyint(1) NOT NULL DEFAULT '0',
  `timeoffset` char(4) NOT NULL DEFAULT '',
  `newpm` smallint(6) unsigned NOT NULL DEFAULT '0',
  `newprompt` smallint(6) unsigned NOT NULL DEFAULT '0',
  `accessmasks` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmincp` tinyint(1) NOT NULL DEFAULT '0',
  `onlyacceptfriendpm` tinyint(1) NOT NULL DEFAULT '0',
  `conisbind` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `groupid` (`groupid`),
  KEY `conisbind` (`conisbind`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63547 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_config`
--

DROP TABLE IF EXISTS `pt_config`;
CREATE TABLE IF NOT EXISTS `pt_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cfg_name` varchar(255) DEFAULT NULL COMMENT '配置名称',
  `cfg_value` varchar(255) DEFAULT NULL COMMENT '配置值',
  `is_complete` int(1) DEFAULT '1' COMMENT '1完成/0未完成',
  `cfg_version` varchar(200) DEFAULT NULL,
  `version_value` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cfg_name` (`cfg_name`),
  KEY `idx_cfg_value` (`cfg_value`),
  KEY `idx_is_complete` (`is_complete`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_dev_app`
--

DROP TABLE IF EXISTS `pt_dev_app`;
CREATE TABLE IF NOT EXISTS `pt_dev_app` (
  `app_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(50) NOT NULL,
  `app_intro` text NOT NULL,
  `app_category_id` int(11) unsigned NOT NULL,
  `app_tags` varchar(255) NOT NULL,
  `app_icon_url` varchar(128) NOT NULL,
  `app_slide_url` varchar(128) NOT NULL,
  `app_thumbs` text,
  `app_is_web` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `app_is_android` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `app_version` varchar(20) NOT NULL,
  `app_android_version` varchar(20) NOT NULL,
  `app_android_package` varchar(128) NOT NULL,
  `app_price` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `app_times_setup` int(11) unsigned NOT NULL DEFAULT '0',
  `app_times_hit` int(11) unsigned NOT NULL DEFAULT '0',
  `app_star` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `app_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `app_android_url` varchar(255) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL,
  `client_id` varchar(255) NOT NULL DEFAULT '',
  `app_web_url` varchar(255) NOT NULL DEFAULT '',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0',
  `utime` int(11) unsigned NOT NULL DEFAULT '0',
  `display_order` int(11) unsigned NOT NULL DEFAULT '0',
  `app_android_size` varchar(50) NOT NULL DEFAULT '',
  `client_secret` varchar(255) NOT NULL DEFAULT '',
  `redirect_uri` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`app_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_dev_token`
--

DROP TABLE IF EXISTS `pt_dev_token`;
CREATE TABLE IF NOT EXISTS `pt_dev_token` (
  `oauth_token` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(255) DEFAULT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT 'ucenter的uid',
  PRIMARY KEY (`oauth_token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pt_error_list`
--

DROP TABLE IF EXISTS `pt_error_list`;
CREATE TABLE IF NOT EXISTS `pt_error_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `tid` bigint(20) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `channel` varchar(100) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL,
  `time` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=237653 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_error_report`
--

DROP TABLE IF EXISTS `pt_error_report`;
CREATE TABLE IF NOT EXISTS `pt_error_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) DEFAULT '0',
  `tv_parent_id` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51838 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_icon`
--

DROP TABLE IF EXISTS `pt_icon`;
CREATE TABLE IF NOT EXISTS `pt_icon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(255) DEFAULT '' COMMENT '接入点名称',
  `icon` varchar(255) DEFAULT '' COMMENT 'icon地址',
  `ctime` varchar(255) DEFAULT '',
  `url` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '接入点名称',
  `status` int(11) NOT NULL DEFAULT '0',
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_integral_tip`
--

DROP TABLE IF EXISTS `pt_integral_tip`;
CREATE TABLE IF NOT EXISTS `pt_integral_tip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '加分类型',
  `score` int(11) DEFAULT NULL COMMENT '分',
  `tm` int(10) DEFAULT NULL,
  `isread` tinyint(1) DEFAULT '0' COMMENT '1读取过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15780 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_itemchildren`
--

DROP TABLE IF EXISTS `pt_itemchildren`;
CREATE TABLE IF NOT EXISTS `pt_itemchildren` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pt_items`
--

DROP TABLE IF EXISTS `pt_items`;
CREATE TABLE IF NOT EXISTS `pt_items` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pt_letter`
--

DROP TABLE IF EXISTS `pt_letter`;
CREATE TABLE IF NOT EXISTS `pt_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) DEFAULT NULL COMMENT '视频ID',
  `letter` varchar(255) DEFAULT NULL COMMENT '字母索引',
  `score` float DEFAULT '0' COMMENT '评星',
  `is_show` int(1) DEFAULT NULL COMMENT '是否显示（管理员相关）0显示、1不显示',
  `is_show_source` int(1) DEFAULT NULL COMMENT '接入点管理（1显示、0不显示）',
  `free` int(1) DEFAULT NULL COMMENT '收费情况，1收费，0免费',
  `source` varchar(255) DEFAULT NULL COMMENT '接入源',
  `is_name` int(1) DEFAULT '0' COMMENT '1名字；0字母；2是别名',
  `category` int(11) DEFAULT '-1' COMMENT '分类',
  `weights` int(11) NOT NULL COMMENT '权重',
  `alias_length` int(11) NOT NULL,
  `alias_pinyin` varchar(255) NOT NULL COMMENT '别名的多音字,1是，0否',
  `datatype` tinyint(1) DEFAULT '0' COMMENT '0普通数据，1兔单',
  `videodatatype` tinyint(1) DEFAULT '0' COMMENT '0正常数据 1:qvod 2:baidu 3:qvod$$$baidu',
  PRIMARY KEY (`id`),
  KEY `idx_vid` (`vid`),
  KEY `idx_letter` (`letter`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_is_show_source` (`is_show_source`),
  KEY `idx_is_free` (`free`),
  KEY `idx_source` (`source`),
  KEY `idx_category` (`category`),
  KEY `idx_datatype` (`datatype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3632677 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_letter_all`
--

DROP TABLE IF EXISTS `pt_letter_all`;
CREATE TABLE IF NOT EXISTS `pt_letter_all` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) DEFAULT NULL,
  `letter` text,
  PRIMARY KEY (`id`),
  KEY `idx_vid` (`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=779307 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_l_list`
--

DROP TABLE IF EXISTS `pt_l_list`;
CREATE TABLE IF NOT EXISTS `pt_l_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `summary` varchar(255) DEFAULT NULL COMMENT '简介',
  `url` varchar(255) DEFAULT NULL COMMENT '播放地址',
  `pic` varchar(255) DEFAULT NULL COMMENT '图片',
  `category` int(11) DEFAULT NULL COMMENT '种类',
  `type` int(11) DEFAULT NULL COMMENT '分类',
  `time` int(10) DEFAULT NULL,
  `is_show` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `update_time` int(10) DEFAULT NULL,
  `letter` varchar(45) DEFAULT NULL COMMENT '首字母',
  `resolution` tinyint(1) DEFAULT NULL COMMENT '分辨率（0普通/1高清/2超清/3其他）',
  `play_count` int(11) DEFAULT NULL COMMENT '播放次数',
  `source` varchar(255) DEFAULT NULL COMMENT '视频来源',
  `source_resolution` varchar(255) DEFAULT NULL,
  `hd_order` int(11) DEFAULT '10000' COMMENT '高清排序',
  `rank_order` int(11) DEFAULT '500',
  `is_edit` tinyint(1) DEFAULT NULL COMMENT '1:已编辑 2代表cdn上传成功 默认为0  ',
  `position_x` int(11) DEFAULT '0',
  `position_y` int(11) DEFAULT '0',
  `cover_img` varchar(45) DEFAULT '',
  `width` int(11) DEFAULT '0',
  `height` int(11) DEFAULT '0',
  `img` varchar(45) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='直播名' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_l_tv`
--

DROP TABLE IF EXISTS `pt_l_tv`;
CREATE TABLE IF NOT EXISTS `pt_l_tv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL COMMENT '第几集',
  `list_name` varchar(255) NOT NULL COMMENT '分级名称\n',
  `list_parent_id` int(11) NOT NULL COMMENT '所属直播id',
  `list_url` varchar(255) NOT NULL,
  `list_play_count` int(11) NOT NULL,
  `list_support` int(11) NOT NULL COMMENT '对一级的顶',
  `list_opposition` varchar(45) NOT NULL COMMENT '对一集的猜',
  `source` varchar(45) NOT NULL,
  `time` int(10) NOT NULL,
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 1删除 0没删除',
  `list_resolution` tinyint(1) NOT NULL COMMENT '分辨率',
  `user_id` varchar(45) DEFAULT NULL COMMENT '用户名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='直播接入点' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_middle_table`
--

DROP TABLE IF EXISTS `pt_middle_table`;
CREATE TABLE IF NOT EXISTS `pt_middle_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source_id` int(10) unsigned NOT NULL COMMENT '资源id',
  `category` tinyint(2) DEFAULT '0',
  `match_value` varchar(255) DEFAULT '' COMMENT '匹配值',
  `source` varchar(255) DEFAULT '',
  `type` varchar(255) DEFAULT '' COMMENT 'more 代表匹配多个 exist代表匹配结果中含有该接入点',
  `source_name` varchar(255) DEFAULT '',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=144541 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_online_cnt`
--

DROP TABLE IF EXISTS `pt_online_cnt`;
CREATE TABLE IF NOT EXISTS `pt_online_cnt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `onlinesum` int(11) DEFAULT NULL,
  `ueerall` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_person_info`
--

DROP TABLE IF EXISTS `pt_person_info`;
CREATE TABLE IF NOT EXISTS `pt_person_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '豆瓣的人物唯一标识，http://movie.douban.com/celebrity/{cid}可以访问该人物的豆瓣主页',
  `name` varchar(100) NOT NULL COMMENT '人物名称',
  `name_en` varchar(100) DEFAULT NULL COMMENT '人物英文名',
  `aka` varchar(100) DEFAULT NULL COMMENT '别名，或原名。一般为人物国籍所用语言的写法',
  `avatars` varchar(255) DEFAULT NULL COMMENT '头像',
  `born_place` varchar(45) DEFAULT NULL COMMENT '出生国家或地区',
  `summary` text COMMENT '人物简介',
  `gender` varchar(10) DEFAULT NULL COMMENT '性别',
  `birthday` varchar(45) DEFAULT NULL COMMENT '生日',
  `professions` varchar(45) NOT NULL COMMENT '身份，如演员、导演、编剧等',
  `constellation` varchar(45) DEFAULT NULL COMMENT '星座',
  `photos` text COMMENT '人物相关照片',
  `time` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL COMMENT '分库sp_douban_person中的id',
  `sort` int(11) DEFAULT NULL COMMENT '排序 ',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为不显示，1为显示',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认演员字段  1是默认 0不是默认',
  `sort_default` int(10) NOT NULL DEFAULT '0' COMMENT '倒序排序 DESC',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_play_history`
--

DROP TABLE IF EXISTS `pt_play_history`;
CREATE TABLE IF NOT EXISTS `pt_play_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `vid` int(11) DEFAULT NULL COMMENT '视频ID',
  `tv_id` int(11) DEFAULT NULL COMMENT '分集tv_id',
  `play_status` varchar(255) DEFAULT NULL COMMENT '播放状态',
  `source` varchar(255) DEFAULT NULL COMMENT '来源',
  `mark` int(1) DEFAULT '0' COMMENT '标识用户是否删除（1删除，0未删除）',
  `time` int(10) DEFAULT NULL COMMENT '时间',
  `tid` bigint(20) DEFAULT NULL COMMENT 'tid',
  `t_id` int(11) DEFAULT NULL COMMENT '分集主键ID',
  `userid` int(11) DEFAULT NULL COMMENT '区分兔单还是普通数据，如果是兔单数据user_id为空，userid不为空',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_mark` (`mark`),
  KEY `idx_tv_id` (`tv_id`),
  KEY `idx_vid` (`vid`),
  KEY `idx_tid` (`tid`),
  KEY `idx_userid` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1875653 ;

--
-- 触发器 `pt_play_history`
--
DROP TRIGGER IF EXISTS `t_afterinsert_on_pt_play_histroy`;
DELIMITER //
CREATE TRIGGER `t_afterinsert_on_pt_play_histroy` AFTER INSERT ON `pt_play_history`
 FOR EACH ROW insert into pt_sotu_rating(uid,vid,preference,time) values(new.user_id,new.vid,(select score_douban from pt_new_video where new.vid=id),new.time)
//
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_push_record`
--

DROP TABLE IF EXISTS `pt_push_record`;
CREATE TABLE IF NOT EXISTS `pt_push_record` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` varchar(90) DEFAULT NULL COMMENT '谁推送',
  `userid` varchar(30) DEFAULT NULL COMMENT '百度云userid',
  `content` text COMMENT 'content',
  `status` smallint(1) DEFAULT '0' COMMENT '状态1成功0失败',
  `ts` int(10) DEFAULT NULL COMMENT 'time',
  `devtype` int(1) DEFAULT NULL COMMENT '设备类型',
  `tagname` varchar(20) DEFAULT NULL COMMENT '标签',
  `sendtype` int(11) DEFAULT NULL COMMENT '1,推送视频  记录vid',
  `tuid` varchar(45) DEFAULT NULL COMMENT '推送给谁',
  `action` varchar(45) DEFAULT NULL COMMENT '动作 1历史，2收藏 3，建兔单,4好友推荐,好友消息也是4   //5百度推送下架',
  `videotype` varchar(45) DEFAULT NULL COMMENT '1，普通视频2，兔单视频，3活动，4应用',
  `vid` varchar(45) DEFAULT NULL,
  `is_new` tinyint(1) DEFAULT '1' COMMENT '是否是新消息',
  `username` varchar(100) DEFAULT NULL COMMENT '用户名',
  `avatar_url` varchar(255) DEFAULT NULL COMMENT '好友头像',
  `vname` varchar(255) DEFAULT NULL COMMENT '影片名',
  `vpic` varchar(255) DEFAULT NULL COMMENT '影片封面',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '是否显示，和用户member表设置有关系',
  `sendstatus` int(1) NOT NULL COMMENT '是否推送成功',
  PRIMARY KEY (`id`),
  KEY `idx_is_new` (`is_new`),
  KEY `idx_isshow` (`isshow`),
  KEY `tuid` (`tuid`),
  KEY `sendstatus` (`sendstatus`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51759 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_recommend`
--

DROP TABLE IF EXISTS `pt_recommend`;
CREATE TABLE IF NOT EXISTS `pt_recommend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vid` int(11) DEFAULT NULL COMMENT '视频ID',
  `topic_id` int(11) DEFAULT NULL COMMENT '专题ID',
  `order` int(11) DEFAULT NULL COMMENT '推荐排列顺序',
  `time` int(10) DEFAULT NULL COMMENT '添加 时间',
  `category` int(11) DEFAULT NULL COMMENT '所属分类',
  `imgurl` varchar(255) DEFAULT NULL COMMENT '封面大图',
  `yunimg` varchar(255) DEFAULT NULL COMMENT 'upyun存储的图片',
  `rec_info` text COMMENT '推荐理由',
  `small_image_url` varchar(255) DEFAULT NULL,
  `data_type` int(1) DEFAULT '0' COMMENT '0普通1兔单2专题3活动,4应用',
  `is_show` int(11) DEFAULT '0' COMMENT '0:不显示,1:显示, ',
  `img_tu4` varchar(255) DEFAULT NULL,
  `img_tu4_mak` varchar(255) DEFAULT NULL,
  `new_order` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_topic_id` (`topic_id`),
  KEY `idx_order` (`order`),
  KEY `idx_category` (`category`),
  KEY `idx_vid` (`vid`),
  KEY `idx_time` (`time`),
  KEY `idx_imgurl` (`imgurl`),
  KEY `yunimg` (`yunimg`),
  KEY `idx_data_type` (`data_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1714 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_relat_table`
--

DROP TABLE IF EXISTS `pt_relat_table`;
CREATE TABLE IF NOT EXISTS `pt_relat_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(10) unsigned NOT NULL,
  `video_name` varchar(255) DEFAULT '',
  `letv` varchar(255) DEFAULT '',
  `youku` varchar(255) DEFAULT '',
  `qiyi` varchar(255) DEFAULT '',
  `sina` varchar(255) DEFAULT '',
  `qq` varchar(255) DEFAULT '',
  `cntv` varchar(255) DEFAULT '',
  `tudou` varchar(255) DEFAULT '',
  `wangyi` varchar(255) DEFAULT '',
  `wole` varchar(255) DEFAULT '',
  `ku6` varchar(255) DEFAULT '',
  `sohu` varchar(255) DEFAULT '',
  `ifeng` varchar(255) DEFAULT '',
  `m1905` varchar(255) DEFAULT '',
  `pps` varchar(255) DEFAULT '',
  `pptv` varchar(255) DEFAULT '',
  `umiwi` varchar(255) DEFAULT '',
  `tv189` varchar(255) DEFAULT '',
  `yinyuetai` varchar(255) DEFAULT '',
  `joy` varchar(255) DEFAULT '',
  `funshion` varchar(255) DEFAULT '',
  `xunlei` varchar(255) DEFAULT '',
  `is_new` varchar(255) DEFAULT '',
  `category` int(11) DEFAULT '0',
  `letv_ok` tinyint(1) DEFAULT '0',
  `youku_ok` tinyint(1) DEFAULT '0',
  `qiyi_ok` tinyint(1) DEFAULT '0',
  `sina_ok` tinyint(1) DEFAULT '0',
  `qq_ok` tinyint(1) DEFAULT '0',
  `cntv_ok` tinyint(1) DEFAULT '0',
  `tudou_ok` tinyint(1) DEFAULT '0',
  `wangyi_ok` tinyint(1) DEFAULT '0',
  `wole_ok` tinyint(1) DEFAULT '0',
  `ku6_ok` tinyint(1) DEFAULT '0',
  `sohu_ok` tinyint(1) DEFAULT '0',
  `ifeng_ok` tinyint(1) DEFAULT '0',
  `m1905_ok` tinyint(1) DEFAULT '0',
  `pps_ok` tinyint(1) DEFAULT '0',
  `pptv_ok` tinyint(1) DEFAULT '0',
  `umiwi_ok` tinyint(1) DEFAULT '0',
  `tv189_ok` tinyint(1) DEFAULT '0',
  `yinyuetai_ok` tinyint(1) DEFAULT '0',
  `joy_ok` tinyint(1) DEFAULT '0',
  `funshion_ok` tinyint(1) DEFAULT '0',
  `xunlei_ok` tinyint(1) DEFAULT '0',
  `cnt` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '后台审核接入点个数',
  `cnt2` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '匹配到的接入点个数',
  `douban` int(11) DEFAULT '0' COMMENT '豆瓣id',
  `douban_ok` int(2) NOT NULL DEFAULT '0',
  `youpeng` int(11) NOT NULL DEFAULT '0' COMMENT '优朋接入点',
  `youpeng_ok` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_video_id` (`video_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci' AUTO_INCREMENT=124750 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_reserve`
--

DROP TABLE IF EXISTS `pt_reserve`;
CREATE TABLE IF NOT EXISTS `pt_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teamid` int(11) DEFAULT NULL COMMENT '活动的id',
  `mobile` mediumtext COMMENT '用户手机号码,多个之间用@分割',
  `tm` int(10) DEFAULT NULL COMMENT '预约时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_teamid` (`teamid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='活动的--预约' AUTO_INCREMENT=715 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_search_record`
--

DROP TABLE IF EXISTS `pt_search_record`;
CREATE TABLE IF NOT EXISTS `pt_search_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `k_word` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `v_name` varchar(255) NOT NULL DEFAULT '' COMMENT '片名',
  `channel` varchar(45) NOT NULL DEFAULT '' COMMENT '来源',
  `ctime` int(11) NOT NULL COMMENT '时间',
  `third_score` int(11) NOT NULL DEFAULT '0' COMMENT '第三方分数，人工干预',
  `v_id` int(11) DEFAULT NULL COMMENT '视频ID',
  `stime` int(11) DEFAULT NULL COMMENT '搜索次数',
  PRIMARY KEY (`id`),
  KEY `idx_k_word` (`k_word`),
  KEY `index3` (`v_name`),
  KEY `idx_channel` (`channel`),
  KEY `idx_third_score` (`third_score`),
  KEY `idx_v_id` (`v_id`),
  KEY `idx_stime` (`stime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_shorturl`
--

DROP TABLE IF EXISTS `pt_shorturl`;
CREATE TABLE IF NOT EXISTS `pt_shorturl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `ctime` int(11) unsigned NOT NULL,
  `md5` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL COMMENT 'yanzheng key',
  `is_use` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已经生成过订单 1 已经用过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=611 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_sms_content`
--

DROP TABLE IF EXISTS `pt_sms_content`;
CREATE TABLE IF NOT EXISTS `pt_sms_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `mobile` varchar(20) DEFAULT NULL,
  `is_send` tinyint(1) DEFAULT '0',
  `tm` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '短信内容类型1预约2绑定',
  `activeid` int(5) NOT NULL COMMENT '活动的id',
  `uid` int(11) DEFAULT NULL COMMENT '用户的uid',
  PRIMARY KEY (`id`),
  KEY `activeid` (`activeid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='异步发送短信' AUTO_INCREMENT=1204 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_sms_content_verifycode`
--

DROP TABLE IF EXISTS `pt_sms_content_verifycode`;
CREATE TABLE IF NOT EXISTS `pt_sms_content_verifycode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text,
  `mobile` varchar(20) DEFAULT NULL,
  `is_send` tinyint(1) DEFAULT '0',
  `tm` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '短信内容类型1预约2绑定',
  `uid` int(11) DEFAULT NULL COMMENT '用户的uid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=565 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_sms_record`
--

DROP TABLE IF EXISTS `pt_sms_record`;
CREATE TABLE IF NOT EXISTS `pt_sms_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) DEFAULT '' COMMENT '手机号码',
  `tm` int(10) DEFAULT NULL COMMENT '发送时间',
  `content` varchar(255) DEFAULT '' COMMENT '短信内容',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=352 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_source`
--

DROP TABLE IF EXISTS `pt_source`;
CREATE TABLE IF NOT EXISTS `pt_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `is_show` tinyint(1) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_source_cnt`
--

DROP TABLE IF EXISTS `pt_source_cnt`;
CREATE TABLE IF NOT EXISTS `pt_source_cnt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `source` varchar(255) DEFAULT NULL,
  `mil` int(11) DEFAULT NULL,
  `playsum` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_speed`
--

DROP TABLE IF EXISTS `pt_speed`;
CREATE TABLE IF NOT EXISTS `pt_speed` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) DEFAULT '',
  `tid` varchar(255) DEFAULT '',
  `IP` varchar(255) DEFAULT NULL COMMENT '内网IP',
  `source` varchar(255) DEFAULT '',
  `time` varchar(255) DEFAULT '',
  `speed` varchar(255) DEFAULT '',
  `url` varchar(255) DEFAULT NULL COMMENT '播放页面url',
  `parse_time` varchar(255) DEFAULT '',
  `wan_ip` varchar(100) DEFAULT NULL COMMENT '外网ip',
  `real_url` varchar(255) DEFAULT NULL COMMENT '真实播放地址',
  `client_type` varchar(100) DEFAULT NULL COMMENT '终端类型',
  `client_version` varchar(100) DEFAULT NULL COMMENT '终端版本',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=194498 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_system`
--

DROP TABLE IF EXISTS `pt_system`;
CREATE TABLE IF NOT EXISTS `pt_system` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token_key` varchar(255) DEFAULT NULL,
  `domain` varchar(255) DEFAULT NULL COMMENT '域名',
  `website_name` varchar(255) DEFAULT NULL COMMENT '站点名称',
  `is_cache` tinyint(1) DEFAULT '0' COMMENT '缓存开关',
  `cache_type` tinyint(1) DEFAULT NULL COMMENT '缓存种类  1： memcache  2：redis 3 mongodb',
  `cache_server` varchar(255) DEFAULT NULL,
  `is_cdn` tinyint(1) DEFAULT '0' COMMENT '是否开启cdn',
  `cdn_type` tinyint(1) DEFAULT NULL COMMENT '1又拍云 2',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_s_config`
--

DROP TABLE IF EXISTS `pt_s_config`;
CREATE TABLE IF NOT EXISTS `pt_s_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cfg_name` varchar(128) NOT NULL DEFAULT '' COMMENT '配置名称',
  `cfg_value` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `cfg_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `cfg_pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `cfg_type` set('SYSTEM','USER') NOT NULL DEFAULT 'USER' COMMENT 'SYSTEM:系统配置,USER:用户配置',
  `cfg_comment` varchar(255) DEFAULT NULL COMMENT '配置说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=544 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_tags`
--

DROP TABLE IF EXISTS `pt_tags`;
CREATE TABLE IF NOT EXISTS `pt_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `is_plugin` tinyint(1) DEFAULT '0',
  `is_show` tinyint(4) DEFAULT '1',
  `sort` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_team`
--

DROP TABLE IF EXISTS `pt_team`;
CREATE TABLE IF NOT EXISTS `pt_team` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(128) DEFAULT NULL,
  `summary` text,
  `city_id` int(10) unsigned NOT NULL DEFAULT '0',
  `city_ids` text,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `partner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `system` enum('Y','N') NOT NULL DEFAULT 'Y',
  `team_price` double(10,2) NOT NULL DEFAULT '0.00',
  `market_price` double(10,2) NOT NULL DEFAULT '0.00',
  `product` varchar(128) DEFAULT NULL,
  `condbuy` varchar(255) DEFAULT NULL,
  `per_number` int(10) unsigned NOT NULL DEFAULT '1',
  `permin_number` int(10) DEFAULT '1',
  `min_number` int(10) unsigned NOT NULL DEFAULT '1',
  `max_number` int(10) unsigned NOT NULL DEFAULT '0',
  `now_number` int(10) unsigned NOT NULL DEFAULT '0',
  `pre_number` int(10) unsigned NOT NULL DEFAULT '0',
  `allowrefund` enum('Y','N') NOT NULL DEFAULT 'N',
  `image` varchar(128) DEFAULT NULL,
  `image1` varchar(128) DEFAULT NULL,
  `image2` varchar(128) DEFAULT NULL,
  `flv` varchar(128) DEFAULT NULL,
  `mobile` varchar(16) DEFAULT NULL,
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `card` int(10) unsigned NOT NULL DEFAULT '0',
  `fare` int(10) unsigned NOT NULL DEFAULT '0',
  `farefree` int(11) NOT NULL DEFAULT '0',
  `bonus` int(11) NOT NULL DEFAULT '0',
  `address` varchar(128) DEFAULT NULL,
  `detail` text,
  `systemreview` text,
  `userreview` text,
  `notice` text,
  `express` text,
  `delivery` varchar(16) NOT NULL DEFAULT 'coupon',
  `state` enum('none','success','soldout','failure','refund') NOT NULL DEFAULT 'none',
  `conduser` enum('Y','N') NOT NULL DEFAULT 'Y',
  `buyonce` enum('Y','N') NOT NULL DEFAULT 'Y',
  `team_type` varchar(20) DEFAULT 'normal',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  `expire_time` int(10) unsigned NOT NULL DEFAULT '0',
  `begin_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `reach_time` int(10) unsigned NOT NULL DEFAULT '0',
  `close_time` int(10) unsigned NOT NULL DEFAULT '0',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keyword` varchar(255) DEFAULT NULL,
  `seo_description` text,
  `express_relate` text,
  `sub_id` int(10) NOT NULL DEFAULT '0',
  `needScore` int(11) DEFAULT '0' COMMENT '需要积分',
  `shopurl` varchar(255) DEFAULT '' COMMENT '免费',
  `shopurlBuy` varchar(255) DEFAULT '' COMMENT '低折扣购买地址',
  `cover` varchar(255) DEFAULT '' COMMENT '列表封面图片，tv端用',
  `free_number` int(11) DEFAULT '0' COMMENT '免费商品数量',
  `product_img` varchar(200) DEFAULT '' COMMENT '产品小图',
  `cardSuccess` int(11) DEFAULT '0' COMMENT '创建成功的代金券个数',
  `is_show` tinyint(1) DEFAULT '0' COMMENT '是否显示',
  `ts` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=452 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_tj_area`
--

DROP TABLE IF EXISTS `pt_tj_area`;
CREATE TABLE IF NOT EXISTS `pt_tj_area` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vcate` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '地区',
  `new_user` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '新用户',
  `plays` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '启动次数',
  `ttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_tj_content`
--

DROP TABLE IF EXISTS `pt_tj_content`;
CREATE TABLE IF NOT EXISTS `pt_tj_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日期',
  `newvideo` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '新视频数',
  `newtv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '新分集数',
  `totalvideo` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '视频总数',
  `totaltv` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分集总数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_tj_source`
--

DROP TABLE IF EXISTS `pt_tj_source`;
CREATE TABLE IF NOT EXISTS `pt_tj_source` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vcate` varchar(255) NOT NULL DEFAULT '' COMMENT '接入点',
  `plays` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '播放次数',
  `ttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_tj_video`
--

DROP TABLE IF EXISTS `pt_tj_video`;
CREATE TABLE IF NOT EXISTS `pt_tj_video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vcate` varchar(255) NOT NULL DEFAULT '0' COMMENT '视频分类',
  `plays` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '播放数',
  `ttime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '统计时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_token`
--

DROP TABLE IF EXISTS `pt_token`;
CREATE TABLE IF NOT EXISTS `pt_token` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `tid` bigint(20) DEFAULT NULL,
  `is_use` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tid` (`tid`),
  KEY `idx_is_use` (`is_use`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10128620 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_topic`
--

DROP TABLE IF EXISTS `pt_topic`;
CREATE TABLE IF NOT EXISTS `pt_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic_name` varchar(255) DEFAULT NULL COMMENT '专题名称',
  `time` int(10) DEFAULT NULL COMMENT '添加时间',
  `category` int(11) DEFAULT NULL COMMENT '所属分类',
  `order` int(11) DEFAULT NULL COMMENT '排序',
  `bg_img` varchar(255) DEFAULT NULL,
  `cover_img` varchar(255) DEFAULT NULL,
  `is_shows` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `topic_name` (`topic_name`),
  KEY `time` (`time`),
  KEY `idx_category` (`category`),
  KEY `order` (`order`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_uc_members`
--

DROP TABLE IF EXISTS `pt_uc_members`;
CREATE TABLE IF NOT EXISTS `pt_uc_members` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `email` char(32) NOT NULL DEFAULT '',
  `myid` char(30) NOT NULL DEFAULT '',
  `myidkey` char(16) NOT NULL DEFAULT '',
  `regip` char(15) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` int(10) NOT NULL DEFAULT '0',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `salt` char(6) NOT NULL,
  `secques` char(8) NOT NULL DEFAULT '',
  `parent_pwd` char(32) NOT NULL DEFAULT '',
  `is_from` tinyint(2) DEFAULT '1' COMMENT '默认为1 来自网站、2来自G7、3来自兔子',
  `mobile` int(11) DEFAULT '0',
  `agent` varchar(255) DEFAULT NULL,
  `flg` tinyint(1) DEFAULT '0',
  `countfensi` int(11) DEFAULT '0' COMMENT '粉丝总数',
  `countfoucs` int(11) DEFAULT '0' COMMENT '关注总数',
  `countfriends` int(11) DEFAULT '0' COMMENT '好友总数',
  `statushistory` tinyint(1) DEFAULT '1' COMMENT '是否允许察看历史,1允许0不允许',
  `statuscollect` tinyint(1) DEFAULT '1' COMMENT '是否允许察看收藏，1允许0不允许',
  `statustudan` tinyint(1) DEFAULT '1' COMMENT '是否允许察看兔单，1允许0不允许',
  `tfriend` int(10) DEFAULT '0' COMMENT '获取好友动态列表时间',
  `trecommend` int(10) DEFAULT '0' COMMENT '获取朋友推荐列表的时间',
  `tfoucs` int(10) DEFAULT '0' COMMENT '获取关注列表时间',
  `tfensi` int(10) DEFAULT '0' COMMENT '获取粉丝列表时间',
  `tyuanxian` int(10) DEFAULT '0' COMMENT '获取院线操作的时间',
  `ts` int(10) DEFAULT '0',
  `tactivity` int(11) DEFAULT '0' COMMENT '获取活动列表的时间',
  `qq_uid` int(11) DEFAULT NULL,
  `sina_uid` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61932 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_uc_members_info`
--

DROP TABLE IF EXISTS `pt_uc_members_info`;
CREATE TABLE IF NOT EXISTS `pt_uc_members_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `nick_name` varchar(20) NOT NULL DEFAULT '',
  `real_name` varchar(20) DEFAULT '',
  `province` mediumint(6) NOT NULL,
  `city` mediumint(6) NOT NULL,
  `location` varchar(255) NOT NULL,
  `score` int(11) DEFAULT '0',
  `sex` tinyint(1) NOT NULL,
  `bir_year` int(4) DEFAULT NULL,
  `bir_month` int(2) DEFAULT NULL,
  `bir_day` int(2) DEFAULT NULL,
  `blog_url` varchar(100) DEFAULT NULL,
  `QQ` int(11) DEFAULT '0',
  `MSN` varchar(50) DEFAULT '',
  `abstruct` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `is_init` tinyint(1) DEFAULT NULL,
  `is_synchronizing` tinyint(1) DEFAULT NULL,
  `ctime` int(11) DEFAULT NULL,
  `address` varchar(100) DEFAULT '',
  `postal_code` int(10) DEFAULT NULL,
  `tel` varchar(20) DEFAULT '',
  `domain_name` varchar(100) DEFAULT '',
  `comment_area` smallint(2) DEFAULT NULL,
  `private_area` smallint(2) DEFAULT NULL,
  `honor_area` smallint(2) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `is_del` tinyint(2) DEFAULT '0',
  `parent_control` smallint(2) NOT NULL DEFAULT '0',
  `avatar_url` varchar(255) DEFAULT '',
  `avatar_url_phone` varchar(255) NOT NULL DEFAULT '',
  `university` varchar(150) DEFAULT NULL COMMENT '院校',
  `education` tinyint(2) DEFAULT NULL COMMENT '学历',
  `profession` tinyint(3) DEFAULT NULL COMMENT '职业',
  `zoneimg` varchar(255) DEFAULT NULL,
  `weixinId` varchar(255) DEFAULT '' COMMENT '用户绑定微信openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uid` (`uid`),
  KEY `idx_is_del` (`is_del`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33051 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_uc_third_users`
--

DROP TABLE IF EXISTS `pt_uc_third_users`;
CREATE TABLE IF NOT EXISTS `pt_uc_third_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT '0' COMMENT 'punica_UID',
  `uid_sina` varchar(50) DEFAULT '' COMMENT 'sina_UID',
  `uid_qq` varchar(50) DEFAULT '' COMMENT 'qq_UID',
  `uid_msn` varchar(50) DEFAULT '' COMMENT 'msn_UID',
  `bind_sina` tinyint(1) DEFAULT '0',
  `bind_msn` tinyint(1) DEFAULT '0',
  `bind_qq` tinyint(1) DEFAULT '0',
  `token_sina` varchar(255) DEFAULT '',
  `token_msn` text,
  `token_qq` varchar(255) DEFAULT '',
  `ctime` int(11) DEFAULT NULL,
  `uid_douban` varchar(50) DEFAULT '',
  `bind_douban` tinyint(1) DEFAULT '0',
  `token_douban` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1384 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_uc_userid`
--

DROP TABLE IF EXISTS `pt_uc_userid`;
CREATE TABLE IF NOT EXISTS `pt_uc_userid` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_use` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort`),
  KEY `idx_is_use` (`is_use`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11705641 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user`
--

DROP TABLE IF EXISTS `pt_user`;
CREATE TABLE IF NOT EXISTS `pt_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户ID（uc_member）',
  `tid` bigint(20) DEFAULT NULL COMMENT '终端tid',
  `time` int(10) DEFAULT NULL COMMENT '绑定时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9142 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_action_record`
--

DROP TABLE IF EXISTS `pt_user_action_record`;
CREATE TABLE IF NOT EXISTS `pt_user_action_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(20) DEFAULT NULL COMMENT '用户id',
  `action` int(1) DEFAULT NULL COMMENT '动作 1分享，2收藏 3，建兔单',
  `times` int(20) DEFAULT NULL COMMENT '操作的时间',
  `vid` int(11) DEFAULT NULL,
  `videotype` int(1) DEFAULT NULL COMMENT '1，普通视频2，兔单视频',
  `avatar_url` varchar(255) DEFAULT NULL,
  `avatar_name` varchar(45) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `is_del` tinyint(1) DEFAULT '0' COMMENT '1删除 0未删除',
  `fid` int(11) DEFAULT NULL COMMENT '好友uid',
  `dtype` tinyint(1) DEFAULT '0' COMMENT '0普通数据，1兔单',
  PRIMARY KEY (`id`),
  KEY `idx_is_del` (`is_del`),
  KEY `idx_fid` (`fid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_action` (`action`),
  KEY `idx_dtype` (`dtype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_bdpush`
--

DROP TABLE IF EXISTS `pt_user_bdpush`;
CREATE TABLE IF NOT EXISTS `pt_user_bdpush` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '兔子视频的uid',
  `userid` bigint(20) DEFAULT NULL COMMENT '百度userid',
  `appid` int(11) DEFAULT NULL COMMENT '百度appid',
  `channelid` varchar(30) DEFAULT NULL COMMENT '百度channel',
  `uniqkey` varchar(10) DEFAULT NULL COMMENT '和userid相对应的唯一key',
  `devtype` int(1) DEFAULT NULL COMMENT '3: 安卓 4 ios',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqkey_UNIQUE` (`uniqkey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_city`
--

DROP TABLE IF EXISTS `pt_user_city`;
CREATE TABLE IF NOT EXISTS `pt_user_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_city` (`city`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_commend`
--

DROP TABLE IF EXISTS `pt_user_commend`;
CREATE TABLE IF NOT EXISTS `pt_user_commend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `vid` varchar(255) DEFAULT NULL COMMENT '视频的id，兔单id以t_开头',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_vid` (`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=738 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_followers`
--

DROP TABLE IF EXISTS `pt_user_followers`;
CREATE TABLE IF NOT EXISTS `pt_user_followers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'uid',
  `fid` int(11) NOT NULL COMMENT '被关注者uid',
  `linker` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否互相关注,是为1 不是为0',
  `time` int(10) NOT NULL COMMENT '关注时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_friends`
--

DROP TABLE IF EXISTS `pt_user_friends`;
CREATE TABLE IF NOT EXISTS `pt_user_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
  `fid` int(11) DEFAULT NULL COMMENT '好友uid',
  `relation` smallint(1) DEFAULT '1' COMMENT '1关注（单方）2互相关注',
  `ts` int(10) DEFAULT NULL COMMENT 'time',
  `is_new` tinyint(1) DEFAULT '1' COMMENT '1新的0非新',
  `uptime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_fid` (`fid`),
  KEY `idx_relation` (`relation`),
  KEY `idx_is_new` (`is_new`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8315 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_hidden`
--

DROP TABLE IF EXISTS `pt_user_hidden`;
CREATE TABLE IF NOT EXISTS `pt_user_hidden` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `tv_id` int(11) DEFAULT NULL,
  `date_time` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '0,影片 1，兔单',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `vid` (`vid`),
  KEY `uid_2` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3964 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_hot`
--

DROP TABLE IF EXISTS `pt_user_hot`;
CREATE TABLE IF NOT EXISTS `pt_user_hot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '用户uid',
  `orderid` int(11) DEFAULT '500' COMMENT '顺序',
  `ts` int(10) DEFAULT NULL COMMENT 'time',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_user_login_status`
--

DROP TABLE IF EXISTS `pt_user_login_status`;
CREATE TABLE IF NOT EXISTS `pt_user_login_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '兔子视频uid',
  `uniqkey` varchar(10) DEFAULT NULL COMMENT 'pt_user_dbpush中对应的uniqkey',
  `ts` int(10) DEFAULT NULL COMMENT '登陆时间',
  `status` smallint(1) DEFAULT '0' COMMENT '1登陆状态，0非登陆状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_video_player`
--

DROP TABLE IF EXISTS `pt_video_player`;
CREATE TABLE IF NOT EXISTS `pt_video_player` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mac_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'æ•°æ®æºid',
  `video_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'new_videoè¡¨ä¸­çš„id',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT 'è§†é¢‘åç§°',
  `pinyin` varchar(100) NOT NULL DEFAULT '' COMMENT 'æ‹¼éŸ³',
  `letter` varchar(100) NOT NULL DEFAULT '' COMMENT 'é¦–å­—æ¯',
  `cate` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'åˆ†ç±»',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT 'å›¾ç‰‡ç½‘ç»œè·¯å¾„',
  `local_pic` varchar(255) NOT NULL DEFAULT '' COMMENT 'å›¾ç‰‡ç½‘ç»œè·¯å¾„',
  `actors` varchar(100) NOT NULL DEFAULT '' COMMENT 'æ¼”å‘˜',
  `directs` varchar(100) NOT NULL DEFAULT '' COMMENT 'å¯¼æ¼”',
  `year` varchar(32) NOT NULL DEFAULT '' COMMENT 'ä¸Šæ˜ æ—¶é—´',
  `area` varchar(32) NOT NULL DEFAULT '' COMMENT 'åœ°åŒº',
  `language` varchar(32) NOT NULL DEFAULT '',
  `desc` varchar(32) NOT NULL DEFAULT '' COMMENT 'ä»‹ç»',
  `add_time` datetime NOT NULL COMMENT 'æ·»åŠ æ—¶é—´',
  `update_time` datetime NOT NULL COMMENT 'æ›´æ–°æ—¶é—´',
  `source` varchar(64) NOT NULL DEFAULT '' COMMENT 'æ¥æº',
  `tv_url` text NOT NULL COMMENT 'æ‰€æœ‰åˆ†é›†',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `flag` int(3) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `video_id_bak` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'video_id备份',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_update_time` (`update_time`),
  KEY `idx_video_id` (`video_id`),
  KEY `idx_mac_id` (`mac_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='utf8_general_ci' AUTO_INCREMENT=46778 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_v_list`
--

DROP TABLE IF EXISTS `pt_v_list`;
CREATE TABLE IF NOT EXISTS `pt_v_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '电影名',
  `director` varchar(255) NOT NULL COMMENT '导演',
  `main_actors` varchar(255) NOT NULL COMMENT '主演',
  `actors` varchar(255) NOT NULL COMMENT '演员',
  `desc` text NOT NULL COMMENT '简介/剧情（电视剧）',
  `comment_desc` text NOT NULL COMMENT '推荐描述',
  `url` varchar(255) NOT NULL COMMENT '视频连接地址',
  `pic` varchar(255) NOT NULL COMMENT '封面图片',
  `web_pic` varchar(255) DEFAULT NULL COMMENT 'web封面图',
  `is_edit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1:已编辑 2代表cdn上传成功 默认为0  ',
  `is_big` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为大图 1：大图 ，0：小图',
  `free` int(1) NOT NULL DEFAULT '0' COMMENT '1收费，0免费',
  `genuine` int(1) NOT NULL DEFAULT '0' COMMENT '1正片/0非正片/2其他',
  `resolution` int(1) NOT NULL DEFAULT '0' COMMENT '分辨率（0普通/1高清/2超清/3其他）',
  `time_length` varchar(255) NOT NULL COMMENT '时长',
  `area` varchar(255) NOT NULL DEFAULT '0' COMMENT '地区（大陆、港、欧美..）',
  `year` varchar(255) NOT NULL DEFAULT '0' COMMENT '年份',
  `category` int(2) NOT NULL DEFAULT '0' COMMENT '分类（电影/电视剧..）',
  `type` varchar(255) NOT NULL COMMENT '类型（科幻/喜剧/动漫...）',
  `play_count` int(11) NOT NULL DEFAULT '0' COMMENT '播放次数',
  `score` float NOT NULL DEFAULT '0' COMMENT '评星',
  `support` int(11) NOT NULL DEFAULT '0' COMMENT '顶',
  `opposition` int(11) NOT NULL DEFAULT '0' COMMENT '踩',
  `source` varchar(255) NOT NULL DEFAULT '' COMMENT '视频来源',
  `tv_application_time` varchar(255) NOT NULL COMMENT '上映时间',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `comment_count` int(11) NOT NULL DEFAULT '0' COMMENT '评论总数',
  `third_video_id` varchar(255) DEFAULT '' COMMENT '视频在第三方的ID',
  `topic_id` int(11) DEFAULT '0' COMMENT '所属专题ID',
  `letter` varchar(255) DEFAULT '' COMMENT '字母索引',
  `is_show` int(1) DEFAULT '0' COMMENT '是否显示',
  `user_id` varchar(255) DEFAULT NULL,
  `age` int(11) NOT NULL DEFAULT '0' COMMENT '年龄 6未成年 18成年 100所有',
  `alias` varchar(255) DEFAULT NULL COMMENT '别名',
  `is_show_source` int(1) DEFAULT '1' COMMENT '接入点控制',
  `status` int(1) DEFAULT '1' COMMENT '视频状态(是否完结)',
  `yun_img` varchar(255) DEFAULT '' COMMENT 'upyun存储封面图片',
  `source_resolution` varchar(255) NOT NULL DEFAULT '' COMMENT '接入点分辨率',
  `rank_order` int(11) NOT NULL DEFAULT '500' COMMENT '排行排序',
  `hd_order` int(11) NOT NULL DEFAULT '10000' COMMENT '高清排序',
  `resolution_value` varchar(255) NOT NULL DEFAULT '',
  `is_update` int(1) NOT NULL DEFAULT '0' COMMENT '是否修改',
  `update_url` varchar(255) DEFAULT '' COMMENT '每日更新功能用到,更新地址',
  `is_spider` tinyint(2) DEFAULT '0' COMMENT '1为spider添加，0为后台添加，2为老库数据',
  `source_download` varchar(255) DEFAULT '' COMMENT '下载来源',
  `update_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `spider_time` text COMMENT '蜘蛛更新分集时间',
  `score_douban` float NOT NULL DEFAULT '-1' COMMENT '豆瓣评分',
  `source_genuine` varchar(255) DEFAULT '' COMMENT '各接入点正片 非正片',
  `yuanxian` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否正在热映的片子。0为正常数据，1为从蜘蛛“更新对比”功能导入的即将上映或正在热映的数据，2为添加了在线播放视频后正在热映视频改为下线状态的片子',
  `datatype` int(1) DEFAULT '0' COMMENT '0正常数据 1:qvod 2:baidu 3:qvod$$$baidu',
  `mid` int(11) DEFAULT '0' COMMENT '优朋普乐(voole)对应的mid',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `idx_type` (`type`),
  KEY `idx_source` (`source`),
  KEY `area` (`area`),
  KEY `year` (`year`),
  KEY `idx_play_count` (`play_count`),
  KEY `idx_is_show` (`is_show`),
  KEY `director` (`director`),
  KEY `main_actors` (`main_actors`),
  KEY `tv_application_time` (`tv_application_time`),
  KEY `rank_order` (`rank_order`),
  KEY `comment_count` (`comment_count`),
  KEY `resolution_value` (`resolution_value`),
  KEY `topic_id` (`topic_id`),
  KEY `category` (`category`),
  KEY `time` (`time`),
  KEY `idx_score_douban` (`score_douban`),
  KEY `is_edit` (`is_edit`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='视频列表' AUTO_INCREMENT=140602 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_v_tv`
--

DROP TABLE IF EXISTS `pt_v_tv`;
CREATE TABLE IF NOT EXISTS `pt_v_tv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tv_id` int(11) NOT NULL COMMENT '第几集',
  `tv_name` varchar(255) NOT NULL COMMENT '每一集的名字',
  `pic` varchar(255) NOT NULL COMMENT '图片',
  `tv_parent_id` int(11) NOT NULL COMMENT '所属电影ID',
  `tv_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '每一集的播放地址',
  `tv_play_count` varchar(11) NOT NULL COMMENT '每一集的播放次数',
  `tv_support` int(11) NOT NULL COMMENT '顶（针对一集的）',
  `tv_opposition` int(11) NOT NULL COMMENT '踩（针对一集的）',
  `time_length` int(11) NOT NULL DEFAULT '0' COMMENT '时长（每一集的）',
  `source` varchar(255) NOT NULL COMMENT '来源',
  `time` int(10) NOT NULL COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `is_del` int(1) DEFAULT '0' COMMENT '是否显示 0是显示 1是不显示',
  `swf_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'flash播放地址',
  `user_id` varchar(255) DEFAULT NULL COMMENT '用户名称',
  `tv_time` varchar(255) DEFAULT '' COMMENT '时间 针对综艺的年月',
  `tv_resolution` varchar(255) DEFAULT '' COMMENT '分集分辨率',
  PRIMARY KEY (`id`),
  KEY `tv_id` (`tv_id`),
  KEY `tv_parent_id` (`tv_parent_id`),
  KEY `source` (`source`),
  KEY `idx_is_del` (`is_del`),
  KEY `idx_time` (`time`),
  KEY `idx_tv_url` (`tv_url`),
  KEY `tv_name` (`tv_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='视频分集' AUTO_INCREMENT=4330412 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_weather`
--

DROP TABLE IF EXISTS `pt_weather`;
CREATE TABLE IF NOT EXISTS `pt_weather` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weather_id` int(10) unsigned NOT NULL,
  `weather` text NOT NULL,
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `weather_id` (`weather_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=359025 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_weather_image`
--

DROP TABLE IF EXISTS `pt_weather_image`;
CREATE TABLE IF NOT EXISTS `pt_weather_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `backgound_image` varchar(255) DEFAULT NULL,
  `status_images` varchar(255) DEFAULT NULL,
  `ms` varchar(55) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `pt_zhuiju`
--

DROP TABLE IF EXISTS `pt_zhuiju`;
CREATE TABLE IF NOT EXISTS `pt_zhuiju` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_vid` (`vid`),
  KEY `idx_uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `tbl_migration`
--

DROP TABLE IF EXISTS `tbl_migration`;
CREATE TABLE IF NOT EXISTS `tbl_migration` (
  `version` varchar(255) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tz_assignments`
--

DROP TABLE IF EXISTS `tz_assignments`;
CREATE TABLE IF NOT EXISTS `tz_assignments` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tz_itemchildren`
--

DROP TABLE IF EXISTS `tz_itemchildren`;
CREATE TABLE IF NOT EXISTS `tz_itemchildren` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tz_items`
--

DROP TABLE IF EXISTS `tz_items`;
CREATE TABLE IF NOT EXISTS `tz_items` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
