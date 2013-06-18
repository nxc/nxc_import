CREATE TABLE IF NOT EXISTS `nxc_import_state_hashes` (
  `object_remote_id` varchar(255) NOT NULL,
  `state_hash` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`object_remote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `nxc_csv_import_config`;
CREATE TABLE `nxc_csv_import_config` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `class_id` int(11) unsigned NOT NULL,
  `parent_node_id` int(11) unsigned NOT NULL,
  `attributes_mapping_serialized` TEXT NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;