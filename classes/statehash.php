<?php
/**
 * @package nxcImport
 * @class   nxcImportStateHash
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    08 Nov 2010
 **/

class nxcImportStateHash {

	private function __construct() {}

	public static function create( $remoteID, $stateHash ) {
		$db = eZDB::instance();
		$db->query(
			'INSERT INTO nxc_import_state_hashes ( object_remote_id, state_hash, time )
             VALUES ( "' .  $db->escapeString( $remoteID ) . '", "' .  $db->escapeString( $stateHash ) . '", ' . time() . ')'
		);
	}

	public static function get( $remoteID ) {
		$db = eZDB::instance();
        $result = $db->arrayQuery(
			'SELECT state_hash
			FROM nxc_import_state_hashes
			WHERE object_remote_id = "' . $remoteID . '"'
		);
		return ( count( $result ) == 1 ) ? $result[0]['state_hash'] : null;
	}

	public static function remove( $remoteID ) {
		$db = eZDB::instance();
		$db->query(
			'DELETE FROM nxc_import_state_hashes WHERE object_remote_id = "' . $db->escapeString( $remoteID ) . '"'
		);
	}

	public static function update( $remoteID, $stateHash ) {
		$db = eZDB::instance();
		if( self::get( $remoteID ) !== null ) {
			$db->query(
				'UPDATE nxc_import_state_hashes
				SET state_hash = "' . $db->escapeString( $stateHash ) . '"
				WHERE object_remote_id = "' . $db->escapeString( $remoteID ) . '"'
			);
		} else {
			self::create( $remoteID, $stateHash );
		}
	}
}
?>