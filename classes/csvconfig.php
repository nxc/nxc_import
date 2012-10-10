<?php
/**
 * @package nxcImport
 * @class   nxcImportCSVConfig
 * @author  Serhey Dolgushev <serhey.dolgushev@nxc.no>
 * @date    05 Nov 2010
 **/

abstract class nxcImportCSVConfig extends nxcImportConfig {

	protected $delimiter = ',';
	protected $enclouser = '"';

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

	protected function transformObjectAttribute( array $objectData, $attributeKey ) {
		if( isset( $objectData['CSVRow'] ) === true &&
			is_array( $objectData['CSVRow'] ) === true
		) {
			$row = $objectData['CSVRow'];
			if( isset( $row[ $attributeKey ] ) === true ) {
				return $row[ $attributeKey ];
			}
		}

		return null;
	}
};
?>