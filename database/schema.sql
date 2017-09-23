DROP TABLE IF EXISTS `signup`;

CREATE TABLE `signup` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `id_ref` mediumint(8) unsigned DEFAULT NULL COMMENT 'Referral ID',
  `ref_code` char(10) NOT NULL COMMENT 'Unique referral code',
  `ref_count` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Referral count',
  `email` varchar(50) NOT NULL COMMENT 'Email',
  `user_agent` varchar(200) DEFAULT NULL COMMENT 'User-agent',
  `ip` varchar(50) DEFAULT NULL COMMENT 'IP',
  `inserted_at` datetime NOT NULL COMMENT 'Date and time of insert',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UQ_signup_refCode` (`ref_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;