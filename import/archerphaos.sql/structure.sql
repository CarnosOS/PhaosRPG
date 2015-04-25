-- 
-- Table structure for table `phaos_admin`
-- 

CREATE TABLE `phaos_admin` (
  `id` int(11) NOT NULL auto_increment,
  `admin_user` varchar(100) NOT NULL default '',
  `admin_pass` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_armor`
-- 

CREATE TABLE `phaos_armor` (
  `name` varchar(100) NOT NULL default '',
  `armor_class` int(4) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `armor_class` (`armor_class`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_boots`
-- 

CREATE TABLE `phaos_boots` (
  `name` varchar(100) NOT NULL default '',
  `armor_class` int(4) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `armor_class` (`armor_class`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_buildings`
-- 

CREATE TABLE `phaos_buildings` (
  `shop_id` int(11) unsigned NOT NULL auto_increment,
  `location` int(11) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `owner_id` int(11) NOT NULL default '1000',
  `capacity` int(11) NOT NULL default '10',
  KEY `shop_id` (`shop_id`),
  KEY `location` (`location`),
  KEY `name` (`name`),
  KEY `type` (`type`),
  KEY `owner_id` (`owner_id`),
  KEY `capacity` (`capacity`)
) TYPE=MyISAM AUTO_INCREMENT=125 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_char_inventory`
-- 

CREATE TABLE `phaos_char_inventory` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL default '',
  `item_id` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `equiped` char(1) NOT NULL default 'N',
  `asking_price` int(11) NOT NULL default '0',
  `sell_to` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `item_id` (`item_id`),
  KEY `type` (`type`),
  KEY `equiped` (`equiped`),
  KEY `asking_price` (`asking_price`),
  KEY `sell_to` (`sell_to`)
) TYPE=MyISAM AUTO_INCREMENT=289386 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_characters`
-- 

CREATE TABLE `phaos_characters` (
  `id` int(11) NOT NULL auto_increment,
  `location` int(11) NOT NULL default '0',
  `username` varchar(20) NOT NULL default '',
  `name` varchar(50) NOT NULL default 'a',
  `age` int(4) NOT NULL default '0',
  `sex` varchar(20) NOT NULL default 'not specified',
  `strength` int(3) NOT NULL default '0',
  `dexterity` int(3) NOT NULL default '0',
  `wisdom` int(3) NOT NULL default '0',
  `constitution` int(3) NOT NULL default '0',
  `weapon` int(3) NOT NULL default '0',
  `hit_points` int(6) NOT NULL default '0',
  `race` varchar(30) NOT NULL default '',
  `class` varchar(30) NOT NULL default '',
  `xp` int(10) NOT NULL default '0',
  `level` varchar(5) NOT NULL default '',
  `gold` int(10) NOT NULL default '0',
  `armor` int(3) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `stat_points` int(11) NOT NULL default '0',
  `boots` int(3) NOT NULL default '0',
  `gloves` int(3) NOT NULL default '0',
  `helm` int(3) NOT NULL default '0',
  `shield` int(3) NOT NULL default '0',
  `regen_time` int(11) NOT NULL default '0',
  `dungeon_location` int(11) NOT NULL default '1',
  `stamina` int(10) NOT NULL default '0',
  `stamina_time` int(11) NOT NULL default '0',
  `fight` int(5) NOT NULL default '1',
  `defence` int(5) NOT NULL default '1',
  `weaponless` int(5) NOT NULL default '1',
  `lockpick` int(5) NOT NULL default '1',
  `traps` int(5) NOT NULL default '1',
  `rep_time` int(11) NOT NULL default '0',
  `rep_points` int(11) NOT NULL default '0',
  `rep_helpfull` int(11) NOT NULL default '0',
  `rep_generious` int(11) NOT NULL default '0',
  `rep_combat` int(11) NOT NULL default '0',
  `bankgold` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `location` (`location`),
  KEY `username` (`username`),
  KEY `name` (`name`),
  KEY `stamina` (`stamina`),
  KEY `stamina_time` (`stamina_time`),
  KEY `regen_time` (`regen_time`)
) TYPE=MyISAM AUTO_INCREMENT=146557 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_clan_admin`
-- 

CREATE TABLE `phaos_clan_admin` (
  `id` int(11) NOT NULL auto_increment,
  `clanname` varchar(30) NOT NULL default '',
  `clanleader` varchar(30) NOT NULL default '',
  `clanleader_1` varchar(30) NOT NULL default '',
  `clanbanner` varchar(100) NOT NULL default '',
  `clansig` varchar(6) NOT NULL default '',
  `clanlocation` varchar(11) NOT NULL default '',
  `clanslogan` varchar(100) NOT NULL default '',
  `clancashbox` int(11) NOT NULL default '0',
  `clanmembers` int(10) NOT NULL default '0',
  `clancreatedate` varchar(11) NOT NULL default '',
  `clanrank_1` varchar(20) NOT NULL default '',
  `clanrank_2` varchar(20) NOT NULL default '',
  `clanrank_3` varchar(20) NOT NULL default '',
  `clanrank_4` varchar(20) NOT NULL default '',
  `clanrank_5` varchar(20) NOT NULL default '',
  `clanrank_6` varchar(20) NOT NULL default '',
  `clanrank_7` varchar(20) NOT NULL default '',
  `clanrank_8` varchar(20) NOT NULL default '',
  `clanrank_9` varchar(20) NOT NULL default '',
  `clanrank_10` varchar(20) NOT NULL default '',
  `clan_sig` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `clanname` (`clanname`)
) TYPE=MyISAM AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_clan_in`
-- 

CREATE TABLE `phaos_clan_in` (
  `id` int(11) NOT NULL auto_increment,
  `clanname` varchar(30) NOT NULL default '',
  `clanmember` varchar(30) NOT NULL default '',
  `clanindate` varchar(30) NOT NULL default '',
  `givegold` varchar(30) NOT NULL default '',
  `clanrank` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `clanname` (`clanname`)
) TYPE=MyISAM AUTO_INCREMENT=184 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_clan_search`
-- 

CREATE TABLE `phaos_clan_search` (
  `id` int(11) NOT NULL auto_increment,
  `clanname` varchar(30) NOT NULL default '',
  `charname` varchar(30) NOT NULL default '',
  `description` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `clanname` (`clanname`),
  KEY `charname` (`charname`)
) TYPE=MyISAM AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_classes`
-- 

CREATE TABLE `phaos_classes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `fight` int(5) NOT NULL default '1',
  `defence` int(5) NOT NULL default '1',
  `weaponless` int(5) NOT NULL default '1',
  `lockpick` int(5) NOT NULL default '1',
  `traps` int(5) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `fight` (`fight`),
  KEY `defence` (`defence`),
  KEY `weaponless` (`weaponless`),
  KEY `lockpick` (`lockpick`),
  KEY `traps` (`traps`)
) TYPE=MyISAM AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_gloves`
-- 

CREATE TABLE `phaos_gloves` (
  `name` varchar(100) NOT NULL default '',
  `armor_class` int(4) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `armor_class` (`armor_class`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=130 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_ground`
-- 

CREATE TABLE `phaos_ground` (
  `location` int(11) unsigned NOT NULL default '0',
  `item_id` int(11) NOT NULL default '0',
  `item_type` varchar(50) NOT NULL default '',
  `number` int(11) NOT NULL default '0',
  PRIMARY KEY  (`location`,`item_id`,`item_type`),
  KEY `location` (`location`),
  KEY `item_id` (`item_id`),
  KEY `item_type` (`item_type`),
  KEY `number` (`number`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_helmets`
-- 

CREATE TABLE `phaos_helmets` (
  `name` varchar(100) NOT NULL default '',
  `armor_class` int(4) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `armor_class` (`armor_class`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_help`
-- 

CREATE TABLE `phaos_help` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `body` longtext NOT NULL,
  `file` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`),
  KEY `category` (`file`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_level_chart`
-- 

CREATE TABLE `phaos_level_chart` (
  `id` int(11) NOT NULL auto_increment,
  `level` int(3) NOT NULL default '0',
  `xp_needed` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `level` (`level`),
  KEY `xp_needed` (`xp_needed`)
) TYPE=MyISAM AUTO_INCREMENT=1001 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_locations`
-- 

CREATE TABLE `phaos_locations` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default 'Wilderness',
  `above_left` int(11) NOT NULL default '0',
  `above` int(11) NOT NULL default '0',
  `above_right` int(11) NOT NULL default '0',
  `leftside` int(11) NOT NULL default '0',
  `rightside` int(11) NOT NULL default '0',
  `below_left` int(11) NOT NULL default '0',
  `below` int(11) NOT NULL default '0',
  `below_right` int(11) NOT NULL default '0',
  `image_path` varchar(100) NOT NULL default 'images/land/2.png',
  `special` int(11) NOT NULL default '0',
  `buildings` char(1) NOT NULL default 'n',
  `pass` char(1) NOT NULL default 'y',
  `explore` varchar(11) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `above_left` (`above_left`),
  KEY `above` (`above`),
  KEY `above_right` (`above_right`),
  KEY `leftside` (`leftside`),
  KEY `rightside` (`rightside`),
  KEY `below_left` (`below_left`),
  KEY `below` (`below`),
  KEY `below_right` (`below_right`),
  KEY `image_path` (`image_path`),
  KEY `special` (`special`),
  KEY `buildings` (`buildings`),
  KEY `pass` (`pass`),
  KEY `explore` (`explore`)
) TYPE=MyISAM AUTO_INCREMENT=103186 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_mail`
-- 

CREATE TABLE `phaos_mail` (
  `UserTo` tinytext NOT NULL,
  `UserFrom` tinytext NOT NULL,
  `Subject` mediumtext NOT NULL,
  `Message` longtext NOT NULL,
  `STATUS` text NOT NULL,
  `SentDate` text NOT NULL,
  `mail_id` int(80) NOT NULL auto_increment,
  PRIMARY KEY  (`mail_id`)
) TYPE=MyISAM AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_npcs`
-- 

CREATE TABLE `phaos_npcs` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `race` varchar(20) NOT NULL default '',
  `image_path` longtext NOT NULL,
  `location` int(11) NOT NULL default '0',
  `rumors` longtext NOT NULL,
  `quest` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `race` (`race`),
  KEY `location` (`location`),
  KEY `quest` (`quest`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_opponents`
-- 

CREATE TABLE `phaos_opponents` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `hit_points` int(3) NOT NULL default '0',
  `race` varchar(30) NOT NULL default '',
  `class` varchar(30) NOT NULL default '',
  `min_damage` int(3) NOT NULL default '0',
  `max_damage` varchar(100) NOT NULL default '',
  `AC` int(3) NOT NULL default '0',
  `xp_given` int(11) NOT NULL default '0',
  `gold_given` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `location` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `location` (`location`)
) TYPE=MyISAM AUTO_INCREMENT=201 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_potion`
-- 

CREATE TABLE `phaos_potion` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default 'potion',
  `image_path` longtext NOT NULL,
  `effect` varchar(100) NOT NULL default 'none',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `effect` (`effect`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_potions`
-- 

CREATE TABLE `phaos_potions` (
  `image_path` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `heal_amount` int(11) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `heal_amount` (`heal_amount`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_questhunters`
-- 

CREATE TABLE `phaos_questhunters` (
  `charid` int(11) NOT NULL default '0',
  `questid` int(11) NOT NULL default '0',
  `starttime` bigint(20) NOT NULL default '0',
  `startexp` int(11) NOT NULL default '0',
  `startgold` int(11) NOT NULL default '0',
  `monstkill` int(11) NOT NULL default '0',
  `battles` int(11) NOT NULL default '0',
  `expearned` int(11) NOT NULL default '0',
  `goldearned` int(11) NOT NULL default '0',
  `complete` int(11) NOT NULL default '0'
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_quests`
-- 

CREATE TABLE `phaos_quests` (
  `questid` int(11) NOT NULL auto_increment,
  `npc` int(11) NOT NULL default '0',
  `reqexp` int(11) NOT NULL default '0',
  `narrate` text NOT NULL,
  `tracemsg` text NOT NULL,
  `waitmsg` text NOT NULL,
  `completemsg` text NOT NULL,
  `rewarditemid` int(11) NOT NULL default '0',
  `rewarditemtype` int(11) NOT NULL default '0',
  `rewardgold` int(11) NOT NULL default '0',
  `rewardwexp` int(11) NOT NULL default '0',
  `monsterkillid` int(11) NOT NULL default '0',
  `monstercollectid` int(11) NOT NULL default '0',
  `monstercollectq` int(11) NOT NULL default '0',
  `battles` int(11) NOT NULL default '0',
  `tr_ar` int(11) NOT NULL default '0',
  `haveitemtype` int(11) NOT NULL default '0',
  `haveitemid` int(11) NOT NULL default '0',
  `paygold` int(11) NOT NULL default '0',
  `havegold` int(11) NOT NULL default '0',
  `earngold` int(11) NOT NULL default '0',
  `payexp` int(11) NOT NULL default '0',
  `haveexp` int(11) NOT NULL default '0',
  `earnexp` int(11) NOT NULL default '0',
  `complete` tinyint(4) NOT NULL default '0',
  `hunters` int(11) NOT NULL default '0',
  `maxtime` bigint(20) NOT NULL default '0',
  `restnum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`questid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_races`
-- 

CREATE TABLE `phaos_races` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `str` int(1) NOT NULL default '0',
  `dex` int(1) NOT NULL default '0',
  `wis` int(1) NOT NULL default '0',
  `con` int(1) NOT NULL default '0',
  `stamina_regen_time` smallint(5) unsigned NOT NULL default '0',
  `stamina_regen_rate` smallint(5) unsigned NOT NULL default '0',
  `healing_time` smallint(5) unsigned NOT NULL default '0',
  `healing_rate` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `stamina_regen_time` (`stamina_regen_time`),
  KEY `stamina_regen_rate` (`stamina_regen_rate`),
  KEY `healing_time` (`healing_time`),
  KEY `healing_rate` (`healing_rate`)
) TYPE=MyISAM AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_sex`
-- 

CREATE TABLE `phaos_sex` (
  `id` int(11) NOT NULL auto_increment,
  `sex` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `sex` (`sex`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_shields`
-- 

CREATE TABLE `phaos_shields` (
  `name` varchar(100) NOT NULL default '',
  `armor_class` int(4) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `armor_class` (`armor_class`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_shop_basics`
-- 

CREATE TABLE `phaos_shop_basics` (
  `shop_id` int(11) NOT NULL auto_increment,
  `shop_type` varchar(255) NOT NULL default 'blacksmith',
  `item_location_id` int(11) NOT NULL default '0',
  `restock_time` int(11) NOT NULL default '0',
  `restock_time_delta` int(11) NOT NULL default '3600',
  PRIMARY KEY  (`shop_id`),
  KEY `shop_type` (`shop_type`),
  KEY `item_location_id` (`item_location_id`),
  KEY `restock_time` (`restock_time`),
  KEY `restock_time_delta` (`restock_time_delta`)
) TYPE=MyISAM AUTO_INCREMENT=119 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_shop_inventory`
-- 

CREATE TABLE `phaos_shop_inventory` (
  `shop_id` int(11) NOT NULL default '0',
  `type` varchar(100) NOT NULL default 'potion',
  `item_id` int(11) NOT NULL default '1',
  `buy` int(11) NOT NULL default '1',
  `sell` int(11) NOT NULL default '2000000000',
  `quantity` int(6) NOT NULL default '1',
  `max` int(6) NOT NULL default '5',
  KEY `shop_id` (`shop_id`),
  KEY `type` (`type`),
  KEY `item_id` (`item_id`),
  KEY `buy` (`buy`),
  KEY `sell` (`sell`),
  KEY `quantity` (`quantity`),
  KEY `max` (`max`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_shop_refill`
-- 

CREATE TABLE `phaos_shop_refill` (
  `shop_id` int(11) NOT NULL default '0',
  `item_type` varchar(255) NOT NULL default 'armor',
  `item_value_min` int(11) NOT NULL default '1',
  `item_value_growth` decimal(9,2) NOT NULL default '2.00',
  `item_value_growth_probability` decimal(9,6) NOT NULL default '0.500000',
  `item_count_min` int(4) NOT NULL default '1',
  `item_name_like` varchar(255) NOT NULL default '%',
  PRIMARY KEY  (`shop_id`,`item_type`),
  KEY `shop_id` (`shop_id`),
  KEY `item_type` (`item_type`),
  KEY `item_value_min` (`item_value_min`),
  KEY `item_value_growth` (`item_value_growth`),
  KEY `item_value_growth_probability` (`item_value_growth_probability`),
  KEY `item_count_min` (`item_count_min`),
  KEY `item_name_like` (`item_name_like`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_shout`
-- 

CREATE TABLE `phaos_shout` (
  `id` int(11) NOT NULL auto_increment,
  `location` int(11) NOT NULL default '0',
  `postname` varchar(255) NOT NULL default '',
  `destname` varchar(255) NOT NULL default '',
  `postdate` int(11) NOT NULL default '0',
  `posttext` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `location` (`location`),
  KEY `destname` (`destname`),
  KEY `postname` (`postname`),
  KEY `postdate` (`postdate`)
) TYPE=MyISAM AUTO_INCREMENT=2449 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_spells_items`
-- 

CREATE TABLE `phaos_spells_items` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `min_damage` int(11) NOT NULL default '0',
  `max_damage` int(11) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `req_skill` int(11) NOT NULL default '0',
  `damage_mess` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `min_damage` (`min_damage`),
  KEY `max_damage` (`max_damage`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`),
  KEY `req_skill` (`req_skill`),
  KEY `damage_mess` (`damage_mess`)
) TYPE=MyISAM AUTO_INCREMENT=257 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_store_inventory`
-- 

CREATE TABLE `phaos_store_inventory` (
  `id` int(11) NOT NULL auto_increment,
  `weapon_1` int(11) NOT NULL default '0',
  `weapon_2` int(11) NOT NULL default '0',
  `weapon_3` int(11) NOT NULL default '0',
  `weapon_4` int(11) NOT NULL default '0',
  `weapon_5` int(11) NOT NULL default '0',
  `armor_1` int(11) NOT NULL default '0',
  `armor_2` int(11) NOT NULL default '0',
  `armor_3` int(11) NOT NULL default '0',
  `armor_4` int(11) NOT NULL default '0',
  `armor_5` int(11) NOT NULL default '0',
  `restock_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `weapon_1` (`weapon_1`),
  KEY `weapon_2` (`weapon_2`),
  KEY `weapon_3` (`weapon_3`),
  KEY `weapon_4` (`weapon_4`),
  KEY `weapon_5` (`weapon_5`),
  KEY `armor_1` (`armor_1`),
  KEY `armor_2` (`armor_2`),
  KEY `armor_3` (`armor_3`),
  KEY `armor_4` (`armor_4`),
  KEY `armor_5` (`armor_5`),
  KEY `restock_time` (`restock_time`)
) TYPE=MyISAM AUTO_INCREMENT=92361 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_users`
-- 

CREATE TABLE `phaos_users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(20) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `email_address` varchar(50) NOT NULL default '',
  `full_name` varchar(50) NOT NULL default '',
  `lang` varchar(10) NOT NULL default 'en',
  `grid_size` smallint(6) NOT NULL default '52',
  `grid_status` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`),
  KEY `lang` (`lang`),
  KEY `grid_size` (`grid_size`),
  KEY `grid_status` (`grid_status`)
) TYPE=MyISAM AUTO_INCREMENT=7072 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `phaos_weapons`
-- 

CREATE TABLE `phaos_weapons` (
  `name` varchar(100) NOT NULL default '',
  `min_damage` int(11) NOT NULL default '0',
  `max_damage` int(11) NOT NULL default '0',
  `buy_price` int(11) NOT NULL default '0',
  `sell_price` int(11) NOT NULL default '0',
  `image_path` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `min_damage` (`min_damage`),
  KEY `max_damage` (`max_damage`),
  KEY `buy_price` (`buy_price`),
  KEY `sell_price` (`sell_price`)
) TYPE=MyISAM AUTO_INCREMENT=262 ;
