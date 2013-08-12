/*名片*/
DROP TABLE IF EXISTS `isns_business_card`;

CREATE TABLE `isns_business_card` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `cn_name` varchar(100) NOT NULL,
  `en_name` varchar(100) NOT NULL,
  `cn_corp_name` varchar(200) NOT NULL,
  `en_corp_name` varchar(200) NOT NULL,
  `address` varchar(400) NOT NULL,
  `telephone` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `postcode` varchar(100) NOT NULL,
  `fax` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `roles` varchar(100) NOT NULL,
  `modify_time` datetime default NULL,
  `template_card_id` smallint(5) unsigned NOT NULL,
  `background_img` varchar(200) default 'default',
  `background_color` varchar(20) default 'default',
  `font_color` varchar(20) default 'default',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



/*名片模板定义*/
DROP TABLE IF EXISTS `isns_template_card`;

CREATE TABLE `isns_template_card` (
  `template_card_id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(200) default NULL,
  `tag` varchar(200) default NULL,
  `template` text NOT NULL,
  `background_img` varchar(200) default NULL,
  `background_color` varchar(20) default NULL,
  `font_color` varchar(20) default NULL,
  `is_pass` tinyint(2) unsigned default '1',
  `sort` smallint(5) unsigned default '100',
  `add_time` datetime default NULL,
  PRIMARY KEY  (`template_card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/*同步中文名*/
alter table `isns_users`
add column
(
  `cn_name` varchar(100) default NULL
);
alter table `isns_pals_mine`
add column
(
  `cn_name` varchar(100) default NULL
)

/**增加字段qq,msn*/
alter table `isns_business_card` 
add column
(
	`msn` varchar(100) not NULL,
	`qq` varchar(100) not NULL
);

alter table `isns_business_card` 
add column
(
`corp_color` varchar(20) default 'default'
);


/*备注*/
alter table `isns_pals_mine`
add column
(
  `pal_note` varchar(100) default NULL
)

/*私信*/
alter table `isns_msg_outbox`
add column
(
  `count` mediumint(8) unsigned DEFAULT '1'
)

delete from `isns_msg_outbox`;
delete from `isns_msg_inbox`;

/*blog,权限*/
alter table `isns_blog`
add column
(
  `can_comment` tinyint(2) unsigned default '1',
  `can_share` tinyint(2) unsigned default '1'
);
/*--return 
alter table `isns_blog` drop column `can_comment`;
alter table `isns_blog` drop column `can_share`;
*/


update `isns_blog`
set edit_time=add_time
where edit_time is null;


/**收藏*/
DROP TABLE IF EXISTS `isns_collect`;

CREATE TABLE `isns_collect` (
  `collect_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned DEFAULT NULL,
  `type_id` tinyint(2) DEFAULT NULL,
  `for_content_id` mediumint(8) unsigned DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL,
  `add_time` datetime DEFAULT NULL,
  `comments` int(5) unsigned DEFAULT '0',
  `is_pass` tinyint(2) DEFAULT '1',
  `tag` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`collect_id`),
  KEY `user_id` (`user_id`),
  KEY `type_id` (`type_id`,`for_content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*默认加好友方式*/
ALTER TABLE `isns_users` CHANGE `palsreq_limit` `palsreq_limit` TINYINT( 2 ) NULL DEFAULT '1'

update `isns_users` set `palsreq_limit` =1;


/*申请说明*/
alter table `isns_pals_request`
add column
(
  `pal_note` varchar(100) default NULL
)
//推荐人
alter table `isns_pals_request`
add column
(
  `referr_user` mediumint(8) unsigned DEFAULT NULL
)

ALTER TABLE `isns_pals_request` CHANGE `pal_note` `pal_note` VARCHAR( 1000 ) DEFAULT NULL ;

--  ----名言
DROP TABLE IF EXISTS `isns_day_remark`;

CREATE TABLE `isns_day_remark` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `remark` varchar(500) DEFAULT NULL,
  `sort_time` datetime DEFAULT NULL,
  `comments` int(5) unsigned DEFAULT '0',
  `is_pass` tinyint(2) DEFAULT '1',
  `tag` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


----名片上添加一个email
ALTER TABLE `isns_business_card` ADD `email` varchar(100);

--增加统计数字
alter table `isns_blog`
add column
(
  `hagrees` MEDIUMINT default '0',
  `hopposes` MEDIUMINT default '0',
  `hthanks` MEDIUMINT default '0'
)

--2013/5/15 21:57
--自我介绍 introduction
--行业 Industry Role
alter table `isns_users`
add column
(
  `introduction` varchar(300) default NULL,
  `industry` varchar(30) default NULL,
  `industry_role` varchar(30) default NULL
);

--港口
DROP TABLE IF EXISTS `isns_harbor`;

CREATE TABLE `isns_harbor` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `portname` varchar(200) DEFAULT NULL,
  `portname_en` varchar(200) DEFAULT NULL,
  `portname_city` varchar(50) DEFAULT NULL,
  `portname_country` varchar(50) DEFAULT NULL,
  `parent` mediumint(8) DEFAULT NULL,
  `sort` mediumint(8) DEFAULT NULL,
  `is_port` tinyint(2) DEFAULT '1',
  `tag` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--用户港口设置
CREATE TABLE `isns_harbor_mine` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL,
  `harbor_id` mediumint(8) NOT NULL,
  `is_port` tinyint(2) DEFAULT '1',
  `tag` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--港口数据：
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('欧洲', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('中南美', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('中东/红海', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('澳新', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('北美', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('非洲', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('东南亚', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('日韩', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('地中海', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('印巴', '1');
INSERT INTO `isns_harbor` (`portname`,`is_port`) VALUES ('中国', '1');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Aalborg 奥尔堡 丹麦  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Aalesund 奥勒松 挪威  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Aarhus 奥尔胡斯 丹麦  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Aberdeen 阿伯丁郡 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Amsterdam 阿姆斯特丹 荷兰 　', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Antwerp 安特卫普 比利时  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Antwerp 安特卫普 比利时  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Arkhangelsk 阿尔汉格尔斯克   　', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bar 巴尔 克罗地亚', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Avonmouth 阿芬默斯 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Belfast 贝尔法斯特 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Boston 波士顿 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bordeaux 波尔多 法国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Berne 伯尔尼 瑞士 　', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bilbao 毕尔巴鄂 西班牙   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bremen 不莱梅 德国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bremenhaven 不来梅哈芬 德国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Brest 布雷斯特 法国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Brisbane 布里斯班 德国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bristol 布里斯托尔 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cadiz 加的斯 西班牙  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cardiff 加的夫 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Copenhagen 哥本哈根 丹麦  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cork 科克 爱尔兰  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dover 多佛尔 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dublin 都柏林 爱尔兰  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dunkirk 敦刻尔克 法国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Durres 都拉斯 阿尔巴尼亚  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dusseldorf 杜赛尔多夫 德国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Felixstowe 费力克斯托 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fos 福斯 法国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Frankfurt 法兰克福 德国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fredericia 腓特烈西亚 丹麦  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fredrikstad 腓特烈斯塔 挪威  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gdansk 格但斯克 波兰  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gdynia 格丁尼亚 波兰  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Ghent 根特 比利时  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gibraltar 直布罗陀 直布罗陀  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Glasgow 格拉斯哥 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gothenburg 哥德堡 瑞典  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Grangemouth 格兰杰默斯 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Halmstad 哈尔姆斯塔德 瑞典  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hamburg 汉堡 德国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Haugesund 豪格松 挪威  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Helsinborg 赫尔辛堡 瑞典  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Helsingo 赫尔辛格 丹麦  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Helsinki 赫尔辛基 芬兰  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hull 赫尔 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Immingham 伊明赫姆船坞 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kholmsk 霍尔姆斯克 俄罗斯', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kiel 基尔 德国   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kotka 科特卡 芬兰   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lancaster 兰开斯特 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Le llavre 勒阿弗尔 法国   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Leningrad 列宁格勒 俄罗斯   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lisbon 里斯本 葡萄牙   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Liverpool 利物浦 英国', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' London 伦敦 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Londonderry 伦敦德里 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lubeck 卢贝克 德国   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Malmo 马尔默 瑞典   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Manchester 曼彻斯特 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Middlesbrough 米德尔斯布勒 英国 ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Murmansk 摩尔曼斯克 俄罗斯   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nakhodka 纳霍德卡 俄罗斯   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nantes 南特 法国   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Newcastle 纽卡斯尔 英国   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Newport 纽波特 英国 ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Oslo 奥斯陆 挪威   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Reykjavik 雷克雅未克 冰岛   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Riga 里加 拉脱维亚   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rostock 罗斯托克 德国   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rotterdam 鹿特丹 荷兰  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Southampton 南安普敦 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' St.Lawrence 圣劳伦斯 瑞典   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Stavanger 斯塔万格 挪 威   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Swansea 斯旺西 英国  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Szozecin 什切青 波兰  ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tallin 塔林 爱沙尼亚   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Turku 图尔库 芬兰   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Vasa 瓦沙 芬兰   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Vigo 维哥 西班牙   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Visby 维斯比 瑞典   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Vostochny 东方港 俄罗斯   ', '2','1');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Wismar 维斯马 德国   ', '2','1');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Acajutla 阿卡胡特拉 萨尔瓦多  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Acapulco 阿卡普尔科 墨西哥  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Antofagasta 安托法加斯塔 智利  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Arica 阿里卡 智利  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bahia Blanca 布兰卡港 阿根廷', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Balboa 巴尔博亚 巴拿马  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Barranquilla 巴兰基利亚 哥伦比亚  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Belem 贝伦 巴西  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Belmopan 贝尔莫潘 伯利兹  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Belize 伯利兹 伯利兹  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bridgetown 布里奇顿 巴巴多斯  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Caracas 加拉加斯 委内瑞拉  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Callao 卡亚俄 秘鲁  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Buenaventura 布埃纳文图拉 哥伦比亚  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Buenos Aires 布宜诺斯艾利斯 阿根廷', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cartagena 卡塔赫纳 哥伦比亚  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cayenne 卡宴 圭亚那  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Chimbote 钦博特 秘鲁  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Christiansted 克里斯琴斯特德 维尔京群岛  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cienfuegos 西恩付戈斯 古巴  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Coatzacoalcos 夸察夸尔科斯 墨西哥', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Colon 科隆 巴拿马  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Corinto 科林托 尼加拉瓜  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cristobal 克里斯托巴尔 巴拿马  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cruz Grande 克鲁斯格兰德 智利', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cumana 库马纳 委内瑞拉  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Ensenada 恩塞纳达 墨西哥  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fort de France 法兰西堡 马提尼克岛', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Freeport 弗里波特 巴哈马  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Georgetown 乔治敦 圭亚那  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Guadalajara 瓜达拉哈拉 墨西哥  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Guayaquil 瓜亚基尔 厄瓜多尔  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Guaymas 瓜伊马斯 墨西哥', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hamilton 哈密尔顿 百慕大群岛  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Havana 哈瓦那 古巴  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Ilo 伊洛 秘鲁  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Iquique 伊基克 智利  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kingstown 金斯敦 圣文森特和格林纳丁斯  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' La Guaira 拉瓜伊拉 委内瑞拉   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' La Paz 拉巴斯 墨西哥   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' La Plata 拉普拉塔 阿根廷   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Manzanillo 曼萨尼略 墨西哥   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Maracaibo 马拉开波 委内瑞拉   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' MardelPlata 马德普拉塔 阿根廷   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Montevideo 蒙得维的亚 乌拉圭   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nassau 拿 骚 巴哈马  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' New Amsterdam 新阿姆斯特丹 圭亚那   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Oranjestad 奥腊涅斯塔德 安的列斯   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Panama 巴拿马 巴拿马   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Paramaribo 帕拉马里博 苏里南   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Paranagua 巴拉那瓜 巴西   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Plymouth 普列茅斯 蒙特塞拉特岛  特立尼达和多巴哥', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Pointe-a-Pitre 皮特尔角 瓜德罗普岛   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Ponce 蓬 塞 波多黎各   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Recife 累西排 巴西   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rio de Janeiro 里约热内卢 巴西   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rio Grande 里奥格兰德 巴西   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Fernando 圣费尔南多 特立尼达和多巴哥   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Jose 圣何塞 危地马拉   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Juan del Sur 南圣胡安 尼加拉瓜   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Juan 圣胡安 波多黎各 ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Lorenzo 圣洛伦索 阿根廷   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Santa Cruz del Sur 南圣克鲁斯 古巴  ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Santo Domingo 圣多明各 多美尼加   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Santos 圣多斯 巴西   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' St.Georege’s 圣乔治 格林纳达   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' St.Georges 圣乔治 百慕大群岛   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' St.John’s 圣约翰斯 安提瓜和巴布   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' St.Thomas 圣托马斯 维尔京群岛    ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tampico 坦皮科 墨西哥   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tujillo 特鲁希略 洪都拉斯   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tumaco 图马科 哥伦比亚   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Valparaiso 瓦尔帕莱索 智利   ', '2','2');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Willemstad 威廉斯塔德 安的列斯    ', '2','2');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Abadan 阿巴丹 伊朗  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Abu Dhabi 阿布扎比 阿联酋', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Aden 亚丁 也门  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Aqaba 亚喀巴 约旦  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Assab 阿萨布 埃塞俄比亚  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bahrain 巴林 巴林  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bandar Abbas 阿巴斯港 伊朗', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bandar Khomeini 霍梅尼港 伊朗', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bombay 孟买 印度  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Basra 巴士拉 伊拉克  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bushire 布什尔 伊朗  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dubai 迪拜 阿拉伯酋长联合国 ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Doha 多哈 卡塔尔 ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dammam 达曼 沙特阿拉伯', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Djibouti 吉布提 吉布提  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gemlik 盖姆利克 土耳其  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Haifa 海法 以色列  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hodeidah 荷台达 也门  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hudaydak Al 荷台达 也门', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Istanbul 伊斯坦布尔 土耳其  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Izmir 伊兹密尔 土耳其  ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Jebel Ali 杰贝阿里 阿联酋   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Jeddah 吉达 沙特阿拉伯   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Karachi 卡拉奇 巴基斯坦   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Khorramshahr 霍拉姆沙赫尔 伊朗   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kuwait 科威特 科威特   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Male 马累 马尔代夫   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Massawa 马萨瓦 埃塞俄比亚   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mokha 穆哈 也门   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mukalla 木卡拉 也门   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Muscat(Mina Qaboos) 马斯喀特 阿曼    ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Santa Cruz 圣克鲁期 加那利群岛 亚速尔群岛 ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sharjah 沙 加 阿联酋   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sharjah 沙 加 阿联酋   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sokhna 索科那 埃及', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Suez 苏伊士 埃及   ', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sur 苏尔 阿曼', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Umm Qasr 乌姆盖斯尔 伊拉克', '2','3');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Umm Said 乌姆赛义德 卡塔尔   ', '2','3');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Adelaide 阿德莱德 澳大利亚 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Apia 阿皮亚 西萨磨亚群岛 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Auckland 奥克兰 新西兰 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Burnie 伯尼 澳大利亚 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Christchurch 克赖斯特彻奇 新西兰', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Darwin 达尔文 澳大利亚 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dunedin 达尼丁 新西兰 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fremantle 佛里曼特尔 澳大利亚 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Geelong 吉朗 澳大利亚 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Funafuti 富纳富提 图瓦卢 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Guam 关岛 马利亚纳群岛 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kieta 基埃塔 所罗门群岛  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' KaVieng 卡维恩 巴布亚新几内亚  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hobart 霍巴特 澳大利亚 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Honiara 霍尼亚拉 所罗门群岛 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lae 莱城 巴布亚新几内亚  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Launceston 朗塞斯顿 澳大利亚  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lautoka 劳托卡 斐济  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lyttelton 利特尔顿 新西兰  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Madang 马 丹 巴布亚新几内亚  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Melborne 默尔本 澳大利亚 ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Napier 纳皮尔 新西兰  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nauru 瑙鲁 瑙鲁  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' New Plymouth 新普利默斯 新西兰  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Noumea 努美阿 新喀里多尼亚  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nukualofa 努库阿洛法 汤加  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Pago Pago 帕果帕果 东萨摩亚   ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Papeete 帕皮提 波利尼西亚(法属)  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Perth 珀斯 澳大利亚', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Port Adelaide 阿德雷德港 澳大利亚  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rabaul 腊包尔港 巴布亚新几内亚  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rarotonga 拉罗通加 库克群岛  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Santo 圣吐 瓦努阿图  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Suva 苏瓦 斐济  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sydney 悉尼 澳大利亚', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tarawa 塔拉瓦 基里巴斯  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Wellington 惠灵顿 新西兰  ', '2','4');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Wewak 威瓦克 巴布亚新几内亚  ', '2','4');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Agana 阿加尼亚 关岛', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Atlanta 亚特兰大 美国 　', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Baltimore 巴尔的摩 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Boston 波士顿 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cambridge 坎布里奇 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Charleston 查尔斯顿 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Charlotte Aamalie 夏洛特阿马利亚 维尔京群岛 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Charlotte 夏洛特 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Churchill 彻奇尔 加拿大 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Chicago 芝加哥 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cleveland 克利夫兰 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' DutchHarbour 荷兰港 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Detroit 底特律 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Columbus 哥伦布 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dallas 达拉斯 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Georgetown 乔治敦 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Godthab 戈特霍布 格陵兰 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Halifax 哈立法克斯 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hamilton 哈密尔顿 加拿大 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Honalulu 檀香山 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Houston 休斯敦 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Jacksonvile 捷克逊维尔 美国 ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Long Beach 长滩 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Los Angeles 洛杉矶 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Miami 迈阿密 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Midland 米德兰 加拿大  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mobile 莫比尔 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Montreal 蒙特利尔 加拿大  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' New Orleans 新奥尔良 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Newcastle 纽卡斯尔 美国   ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Newport 纽波特 美国', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' NewYork 纽 约 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Norfolk 诺福克 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Oakland 奥克兰 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Perth 珀 斯 美国', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Philadedlphia 费 城 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Diego 圣迭戈 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Francisco 旧金山 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Savannsh 萨凡纳 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Seattle 西雅图 美国  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' St.John 圣约翰 加拿大  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Stockholm 斯德哥尔摩 加拿大  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sydney 悉尼 澳大利亚', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Toronto 多伦多 加拿大  ', '2','5');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Vancouver 温哥华 加拿大', '2','5');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Abidjan 阿比让 科特迪瓦 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Accra 阿克拉 加纳 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Apapa 阿帕帕 尼日利亚 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Aseb 阿萨布 埃塞俄比亚 非洲', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Banana 巴纳纳 扎伊尔 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Banjul(banthurst) 班珠尔 冈比亚 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bata 巴塔 赤道几内亚 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Beira 贝拉 莫桑比克 东非', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bissau 比绍 及内比亚绍 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Berbera 伯贝拉 索马里 东非', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Boma 博马 扎伊尔 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Conakry 科纳克里 几内亚 非洲', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Casablanca 卡萨布兰卡 摩洛哥 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cabinda 卡宾达 安哥拉 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cape Town 开普顿 南非', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cotonou 科托努 贝宁 非洲', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dakar 达喀尔 塞内加尔 非洲', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dar El-Beida 达尔贝达 摩洛哥', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dar Es Salaam 达累斯萨拉姆 坦桑尼亚', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Douala 杜阿拉 喀麦隆 非洲', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Durban 德班 南非', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' East London 东伦敦 南非', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Freetown 弗里敦 塞拉利昂 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Funchal 丰沙尔 马德拉群岛 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Horta 澳尔塔 亚速尔群岛（葡属） ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Johannesburg 约翰内斯堡 南非', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kismayu 基斯马尤 索马里  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Las Palmas 拉斯帕尔马斯 加那利群岛  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Libfeville 利伯维尔 加蓬  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lindi 林 迪 坦桑尼亚  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lome 洛美 多哥  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Lourenco-Mar-que 洛伦索 马贵斯 莫桑比克  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Luanda 罗安达 安哥拉  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Majunga 马任加 马达加斯加  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Malabo(SantaIsabel) 马拉博 赤道几内亚  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Malindi 马林迪 肯尼亚  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Matadi 马塔迪 扎伊尔  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mindelo 明德卢 佛得角  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mogadiscio 摩加迪沙 索马里  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mombass 蒙巴萨 肯尼亚  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Monrovia 荣罗维亚 利比里亚  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mozambique 莫桑比克 莫桑比克  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mutsamudu 木察木杜 科摩罗  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nouakchott 努瓦克肖特 毛里塔尼亚  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Owendo 奥文多 加蓬  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Pointe des Galets 加莱角 留尼汪岛  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Pointe Noire 黑 角 刚 果  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rabat 拉巴特 摩洛哥  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' San Tome 圣多美 圣多美和普林西北  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Santiago 圣地亚哥 佛得角  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' St.Denis 圣但尼 留尼汪岛  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Takoradi 塔科拉迪 加 纳  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tamatave 塔马塔夫 马达加斯加  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tanga 坦噶 坦桑尼亚  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tangier 丹吉尔 摩洛哥  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tema 特马 加纳  ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Victoria 维多利亚 喀麦隆 ', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Walvis Bay 沃尔维斯湾 纳米比亚', '2','6');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Zanzibar 桑给巴尔 坦桑尼亚 ', '2','6');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bandar Seri Begawan 斯里巴加湾市 文莱', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bangkok 曼谷 泰国 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Banjarmaisn 马辰 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bassein 勃生 缅甸 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Belawan 乌拉湾 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Butterworth 巴特沃斯 马来西亚 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cam Pha 锦普 越南', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cebu 宿务 菲律宾 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cheribon 井里文 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Djakarta(Jakarta) 雅加达 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Danang 岘港 越南 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Georgetown 乔治敦 马来西亚 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hongay 鸿基 越南 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Ho Chi Ming City 胡志明 越南', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Haiphong 海防 越南 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hanoi 河内 越南 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Jakarta 雅加达 印尼  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Jogjakarta 日惹 印尼  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Johore Bahru 柔佛巴鲁 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kompong Som 磅逊 柬埔塞 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kota Kinabalu 亚庇(哥打基纳巴卢) 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kuching 古晋 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kudat 库达特 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Labuan 拉布安(纳闽) 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Makasar 望加锡 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Malacca 马六甲 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Manila 马尼拉 菲律宾 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Menado 万鸦老 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Miri 米里 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Moulmein 毛淡棉 缅甸  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Pakang 巴东 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Palembang 巨港  印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Penang 棺城 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Phom Penh 金边 柬埔寨  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Pontlanak 坤甸 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rangoon 仰光 缅甸  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sabang 沙 璜 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sandakan 山打根 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Semarang 三宝垄 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sibu 诗巫 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Singapore 新加坡 新加坡  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Surabaya 泗水 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tabaco 塔巴科 菲律宾 菲律宾 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tandjungpriok 丹戎不碌 印尼 ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tawau 斗湖 马来西亚  ', '2','7');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tjirebon 井里汉 印尼 ', '2','7');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Busan 釜山 南韩 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Chiba 千叶 日本', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Chongjin(Seishin) 清律 朝鲜 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fukuoka 福冈 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fukuyama 福山 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hakata 伯方 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hakodate 涵馆 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hiroshima 广岛 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Inchon 仁川 南韩 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hungnam 兴南 朝鲜 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kawasaki 川崎 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nagasaki 长崎 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Moji 门司 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kobe 神户 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Kure 吴港 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nagoya 名古屋 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Naha 那 霸 日 本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nampo 南浦 朝鲜 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Osaka 大阪 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Otaru 小樽 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tokyo 东京 日本 ', '2','8');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Yokohama 横滨 日本 ', '2','8');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Agadir 阿加迪尔 摩洛哥 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Alexandria 亚历山大 埃及 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Algeciras 阿尔赫西拉斯 西班牙 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Algiers 阿尔及尔 阿尔及利亚 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Ancona 安科纳 意大利 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Annaba 安纳巴 阿尔及利亚 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Ashdod 阿什杜德 以色列 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Athens 雅典 希腊 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Beirut 贝鲁特 黎巴嫩 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Barcelona 巴塞罗那 西班牙 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Benghazi 班加西 利比亚 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cagliari 卡利亚里 意大利 　', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Brindisi 布林迪西 意大利 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bizerta 比塞大 突尼斯 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bourgas 布尔加斯 保加利亚 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cartagena 卡塔赫纳 西班牙 　', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Constanza 康斯坦察 罗马尼亚 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Crotone 克努托内 意大利 　', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Damietta 达米埃塔 埃及 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gela 杰拉 意大利 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Genoa(Genova) 热那亚 意大利 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gijon 希洪 西班牙 　', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gioia Tauro 焦亚陶罗 意大利', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Iskenderun 伊斯肯德伦 土耳其 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Latakia 拉塔基亚 叙利亚  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Leghorn(Livorno) 里窝那 意大利  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Limassol 利马索尔 塞浦路斯  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Malta 马耳他 马耳他  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Marseilles 马赛 法国  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Messina 默西拿 意大利  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Naples(Napoli) 那不勒斯(那波利) 意大利  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Odessa 敖德萨 乌克兰  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Oran 奥兰 阿尔及利亚  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Piraeus 比雷埃夫斯 希 腊  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Rijeka 里耶卡 克罗地亚  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sete 塞特 法国  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Split 斯普利特 波黑', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Sur 苏尔 黎巴嫩 ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tel Aviv 特拉维夫 以色列  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Toulon 土伦 法国  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Trieste 的里雅斯特 意大利  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tripoli 的黎波里 利比亚', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Tunis 突尼斯 突尼斯  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Valletta 瓦莱塔 马耳他  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Valona 发罗拉 阿尔巴尼亚', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Varna 戊尔纳 保加利亚  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Venice 威尼斯 意大利  ', '2','9');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Yalta 雅尔塔 俄罗斯  ', '2','9');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Bombay 孟买 印度  ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Calcutta 加尔各答 印度 ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Chennai 钦奈 印度 ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Chittagong 吉大港 孟加拉 ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Cochin 科钦 印度 ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Colombo 科伦坡 斯里兰卡 ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dacca 达卡 孟加拉 ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Gwadur 瓜达尔 巴基斯坦 ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Madras 马熔拉斯 印 度  ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Karachi 卡拉奇 巴基斯坦   ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mahe 马希 印 度   ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Mundra 蒙德拉 印度', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Nhavasheva 那瓦夏瓦 印度', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Pondicherry 本地治里 印度  ', '2','10');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Trincomalee 亭可马里 斯里兰卡', '2','10');

INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Dalian 大连 中国 ', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Fuzhou 福州 中国 ', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Guangzhou 广州 中国 ', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hai Kou 海口 中国', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Hong Kong 香港 中国', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Huangpu 黄埔 中国 ', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Macao 澳门 中国  ', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Shanghai 上海 中国', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Shenzhen 深圳 中国', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Taiwan 台湾 中国', '2','11');
INSERT INTO `isns_harbor` (`portname`,`is_port`,`parent`) VALUES (' Yantan 烟台 中国', '2','11');


update `isns_harbor`
set `portname` = TRIM(`portname`);

update `isns_harbor` 
set `portname_en` = trim(substring(`portname`,1,LOCATE( ' ', `portname` ))),
`portname_city` = trim(substring(`portname`,LOCATE( ' ', `portname` ))) 
WHERE `is_port` = 2;

update `isns_harbor` 
set `portname_country` =  trim(substring(`portname_city`,LOCATE( ' ', `portname_city` ))) ,
`portname_city` = trim(substring(`portname_city`,1,LOCATE( ' ', `portname_city` )))
WHERE `is_port` = 2;

---over


