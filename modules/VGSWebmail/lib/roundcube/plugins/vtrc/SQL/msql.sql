CREATE TABLE IF NOT EXISTS `vtiger_emailtrack` (
  `vtid` varchar(10) NOT NULL,
  `rcid` varchar(50) NOT NULL
)

CREATE TABLE IF NOT EXISTS `vtiger_settings` (
  `url` text NOT NULL,
  `user` varchar(50) NOT NULL,
  `key` varchar(20) NOT NULL
)