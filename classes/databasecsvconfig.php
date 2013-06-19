<?php
/**
 * @package nxcImport
 * @class   nxcImportDatabaseCSVConfig
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    19 jun 2010
 **/

class nxcImportDatabaseCSVConfig extends nxcImportConfig
{
	protected $delimiter = ',';
	protected $enclouser = '"';

	private $DBConfig = null;

	public function __construct() {}

	public function setConfig( nxcCSVImportDBConfig $config ) {
		$this->DBConfig     = $config;
		$this->contentClass = $config->attribute( 'class' );
	}

	public function setSource( $filename ) {
		$this->source = $filename;
	}

	public function getDataList() {
		$this->dataList = array();

		if( file_exists( $this->source ) ) {
			$handle = fopen( $this->source, 'r' );
			while ( ( $row = fgetcsv( $handle, 0, $this->delimiter, $this->enclouser ) ) !== false ) {
				$objectData = array(
					'CSVRow' => $row
				);

				$this->dataList[] = $objectData;
			}
			fclose( $handle );
		}

		return $this->dataList;
	}

	protected function getTransformAttributesArray() {
		$mapping    = $this->DBConfig->attribute( 'attributes_mapping' );
		$attributes = array_keys( $mapping );
		$return     = array();
		$skip       = array( 'remote_id' );

		foreach( $attributes as $attribute ) {
			if( in_array( $attribute, $skip ) ) {
				continue;
			}

			$return[ $attribute ] = $attribute;
		}
		return $return;
	}

	protected function transformObjectAttribute( array $objectData, $attributeKey ) {
		return $this->processAttribute( $objectData, $attributeKey );
	}

	public function getMainParentNodeID( array $objectData ) {
		return $this->DBConfig->attribute( 'parent_node_id' );
	}

	public function getObjectRemoteID( array $objectData ) {
		$mapping  = $this->DBConfig->attribute( 'attributes_mapping' );
		$remoteID = $this->processAttribute( $objectData, 'remote_id' );
		if( $remoteID === null ) {
			$remoteID = md5( microtime() );
		} else {
			$remoteID = $this->DBConfig->attribute( 'class' )->attribute( 'identifier' ) . '-' . $remoteID;
		}

		return $remoteID;
	}

	private function processAttribute( array $objectData, $attribute ) {
		$mapping = $this->DBConfig->attribute( 'attributes_mapping' );
		$value   = null;

		// If there is any static value, we just return it
		if(
			isset( $mapping[ $attribute ]['static_value'] )
			&& strlen( $mapping[ $attribute ]['static_value'] ) > 0
		) {
			return $mapping['remote_id']['static_value'];
		}

		// Trying to get the value from CSV
		if(
			isset( $mapping[ $attribute ]['csv_column'] )
			&& (int) $mapping[ $attribute ]['csv_column'] > 0
		) {
			$value = $objectData['CSVRow'][ $mapping[ $attribute ]['csv_column'] - 1 ];
		}

		// Trying to call callback
		if(
			isset( $mapping[ $attribute ]['callback'] )
			&& strlen( $mapping[ $attribute ]['callback'] ) > 0
		) {
			$temp     = explode( ';', $mapping[ $attribute ]['callback'] );
			$callback = explode( '::', $temp[0] );
			$params   = array();
			if( isset( $temp[1] ) ) {
				$paramsStr = explode( ',', $temp[1] );
				foreach( $paramsStr as $paramStr ) {
					$paramTemp = explode( '=', $paramStr );
					if( count( $paramTemp ) === 2 ) {
						$params[ $paramTemp[0] ] = $paramTemp[1];
					}
				}
			}

			if( is_callable( $callback ) ) {
				$value = call_user_func( $callback, $value, $params );
			}
		}

		return $value;
	}
};
?>