/**
 * Pay Orders in Market DB installation
 */

CREATE TABLE IF NOT EXISTS `cot_payordersmarket` (
  `order_id` int unsigned NOT NULL auto_increment,
  `order_pid` int NOT NULL,
  `order_userid` int NOT NULL,
  `order_seller` int NOT NULL,
  `order_date` int UNSIGNED NOT NULL DEFAULT '0',
  `order_paid` int UNSIGNED NOT NULL DEFAULT '0',
  `order_claim` int UNSIGNED NOT NULL DEFAULT '0',
  `order_cancel` int UNSIGNED NOT NULL DEFAULT '0',
  `order_done` int UNSIGNED NOT NULL DEFAULT '0',
  `order_count` int NOT NULL,
  `order_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_title` varchar(255) default NULL,
  `order_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_claimtext` text default NULL,
  `order_email` varchar(255) DEFAULT '',
  `order_status` varchar(50) default NULL,
  PRIMARY KEY  (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;