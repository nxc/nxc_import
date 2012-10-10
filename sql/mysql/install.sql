CREATE TABLE IF NOT EXISTS `nxc_import_state_hashes` (
  `object_remote_id` varchar(255) NOT NULL,
  `state_hash` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`object_remote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;