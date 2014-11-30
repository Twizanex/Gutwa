--CREATE TABLE IF NOT EXISTS `elgg_user_payment_details` (

--) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `elgg_user_payment_details` (
  `pay_id` int(11) NOT NULL auto_increment,
  `inventory_id` int(11) NOT NULL,
  `advertiser_id` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(5) NOT NULL,
  `amount` float(10,2) NOT NULL,
  PRIMARY KEY  (`pay_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
