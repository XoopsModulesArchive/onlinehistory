# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# --------------------------------------------------------

#
# Table structure for table `lastseen`
#

CREATE TABLE lastseen (
  uid int(10) NOT NULL default '0',
  username varchar(255) NOT NULL default '',
  time int(10) NOT NULL default '0',
  ip varchar(255) NOT NULL default '',
  online tinyint(1) unsigned NOT NULL default '0',
  uagent text NOT NULL,
  module int(10) NOT NULL default '0',
  KEY time (time),
  KEY uid (uid),
  KEY ip (ip)
);